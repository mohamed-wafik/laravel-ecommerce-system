import { MapPin } from "lucide-react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import toast from "react-hot-toast";

import {
  newAddressSchema,
  type NewAddressFormValues,
} from "../../../schema/shippingAddress.schema";
import { checkoutApi } from "../../../api/modules/Customer/checkout.api";

import FormField from "../../../components/ui/FormField";
import Input from "../../../components/ui/Input";
import Button from "../../../components/ui/Button";
import DeliveryMethodSection from "./DeliveryMethodSection";

interface NewAddressFormProps {
  isCartEmpty: boolean;
  onClose: () => void;
}

const NewAddressForm = ({ isCartEmpty, onClose }: NewAddressFormProps) => {
  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
  } = useForm<NewAddressFormValues>({
    resolver: zodResolver(newAddressSchema),
    defaultValues: {
      full_name: "",
      phone: "",
      country: "",
      city: "",
      street: "",
      postal_code: "",
      label: "",
      delivery_method: "standard",
    },
  });

  const onSubmit = async (formData: NewAddressFormValues) => {
    if (isCartEmpty) {
      toast.error("Your cart is empty. Add products before checkout.");
      return;
    }

    try {
      const result = await checkoutApi.create({
        shipping_address: {
          full_name: formData.full_name,
          phone: formData.phone,
          country: formData.country,
          city: formData.city,
          street: formData.street,
          postal_code: formData.postal_code || undefined,
          label: formData.label || undefined,
        },
        delivery_method: formData.delivery_method,
      });

      const { checkout_url } = result.data.data;

      if (!checkout_url) {
        throw new Error("Checkout URL was not returned.");
      }

      window.location.href = checkout_url;
    } catch (error) {
      console.error("Checkout error:", error);
      toast.error("Something went wrong. Please try again.");
    }
  };

  const onInvalid = () => {
    toast.error("Please fix the highlighted fields.");
  };

  return (
    <form
      onSubmit={handleSubmit(onSubmit, onInvalid)}
      className="flex flex-col gap-5"
      noValidate
    >
      <section className="rounded-2xl border border-border bg-bg-primary p-4">
        <div className="mb-5 flex items-center gap-3">
          <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary">
            <MapPin className="h-4 w-4" />
          </div>

          <div>
            <h2 className="font-medium text-text-primary">Shipping address</h2>
            <p className="text-xs text-text-tertiary">
              Enter a new delivery address
            </p>
          </div>
        </div>

        <div className="flex flex-col gap-3">
          <FormField
            htmlFor="full_name"
            label="Full name"
            error={errors.full_name?.message}
            required
          >
            <Input
              id="full_name"
              type="text"
              placeholder="Enter your full name"
              autoComplete="name"
              aria-invalid={!!errors.full_name}
              variant={errors.full_name ? "error" : "default"}
              {...register("full_name")}
            />
          </FormField>

          <FormField
            htmlFor="label"
            label="Address label"
            error={errors.label?.message}
          >
            <Input
              id="label"
              type="text"
              placeholder="e.g. Home, Office (optional)"
              aria-invalid={!!errors.label}
              variant={errors.label ? "error" : "default"}
              {...register("label")}
            />
          </FormField>

          <FormField
            htmlFor="phone"
            label="Phone"
            error={errors.phone?.message}
            required
          >
            <Input
              id="phone"
              type="tel"
              placeholder="Enter your phone number"
              autoComplete="tel"
              aria-invalid={!!errors.phone}
              variant={errors.phone ? "error" : "default"}
              {...register("phone")}
            />
          </FormField>

          <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <FormField
              htmlFor="country"
              label="Country"
              error={errors.country?.message}
              required
            >
              <Input
                id="country"
                type="text"
                placeholder="Enter your country"
                autoComplete="country"
                aria-invalid={!!errors.country}
                variant={errors.country ? "error" : "default"}
                {...register("country")}
              />
            </FormField>

            <FormField
              htmlFor="city"
              label="City"
              error={errors.city?.message}
              required
            >
              <Input
                id="city"
                type="text"
                placeholder="Enter your city"
                autoComplete="address-level2"
                aria-invalid={!!errors.city}
                variant={errors.city ? "error" : "default"}
                {...register("city")}
              />
            </FormField>
          </div>

          <FormField
            htmlFor="street"
            label="Street"
            error={errors.street?.message}
            required
          >
            <Input
              id="street"
              type="text"
              placeholder="Enter your street"
              autoComplete="street-address"
              aria-invalid={!!errors.street}
              variant={errors.street ? "error" : "default"}
              {...register("street")}
            />
          </FormField>

          <FormField
            htmlFor="postal_code"
            label="Postal code"
            error={errors.postal_code?.message}
          >
            <Input
              id="postal_code"
              type="text"
              placeholder="Enter your postal code (optional)"
              autoComplete="postal-code"
              aria-invalid={!!errors.postal_code}
              variant={errors.postal_code ? "error" : "default"}
              {...register("postal_code")}
            />
          </FormField>
        </div>
      </section>

      <DeliveryMethodSection
        register={register}
        error={errors.delivery_method}
      />

      <div className="flex items-center gap-4">
        <Button type="submit" width="full" disabled={isSubmitting}>
          {isSubmitting ? (
            <span className="flex items-center justify-center gap-2">
              <span className="h-4 w-4 animate-spin rounded-full border-2 border-white/30 border-t-white" />
              Processing...
            </span>
          ) : (
            "Continue to payment"
          )}
        </Button>

        <Button
          type="button"
          width="full"
          variant="outline"
          disabled={isSubmitting}
          onClick={onClose}
        >
          Cancel
        </Button>
      </div>
    </form>
  );
};

export default NewAddressForm;
