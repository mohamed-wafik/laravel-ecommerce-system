import { MapPin } from "lucide-react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import toast from "react-hot-toast";

import type { IAddress } from "../../../interface";
import {
  savedAddressSchema,
  type SavedAddressFormValues,
} from "../../../schema/shippingAddress.schema";
import { checkoutApi } from "../../../api/modules/Customer/checkout.api";

import FormField from "../../../components/ui/FormField";
import Button from "../../../components/ui/Button";
import DeliveryMethodSection from "./DeliveryMethodSection";

interface SavedAddressFormProps {
  addresses: IAddress[];
  isLoadingAddresses: boolean;
  addressError: unknown;
  isCartEmpty: boolean;
  onClose: () => void;
}

const SavedAddressForm = ({
  addresses,
  isLoadingAddresses,
  addressError,
  isCartEmpty,
  onClose,
}: SavedAddressFormProps) => {
  const {
    register,
    handleSubmit,

    formState: { errors, isSubmitting },
  } = useForm<SavedAddressFormValues>({
    resolver: zodResolver(savedAddressSchema),
    defaultValues: {
      shipping_address_id: "",
      delivery_method: "standard",
    },
  });

  const onSubmit = async (formData: SavedAddressFormValues) => {
    if (isCartEmpty) {
      toast.error("Your cart is empty. Add products before checkout.");
      return;
    }

    try {
      const result = await checkoutApi.create({
        shipping_address_id: formData.shipping_address_id,
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
      {addressError
        ? true
        : false && (
            <div
              role="alert"
              className="rounded-xl border border-error-100 bg-error-50 px-4 py-3 text-sm text-error"
            >
              Unable to load your saved addresses.
            </div>
          )}

      <section className="rounded-2xl border border-border bg-bg-primary p-4">
        <div className="mb-5 flex items-center gap-3">
          <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary">
            <MapPin className="h-4 w-4" />
          </div>

          <div>
            <h2 className="font-medium text-text-primary">Shipping address</h2>
            <p className="text-xs text-text-tertiary">
              Choose one of your saved addresses
            </p>
          </div>
        </div>

        {isLoadingAddresses ? (
          <div className="h-11 animate-pulse rounded-xl bg-gray-100" />
        ) : (
          <FormField
            htmlFor="shipping_address_id"
            label="Saved address"
            error={errors.shipping_address_id?.message}
            required
          >
            <select
              id="shipping_address_id"
              {...register("shipping_address_id")}
              aria-invalid={!!errors.shipping_address_id}
              className={`h-11 w-full appearance-none rounded-xl border bg-bg-primary px-3 text-sm text-text-primary outline-none transition-all focus:ring-2 ${
                errors.shipping_address_id
                  ? "border-error focus:border-error focus:ring-error-100"
                  : "border-border focus:border-primary focus:ring-primary-100"
              }`}
            >
              <option value="">Choose an address...</option>

              {addresses.map((address) => (
                <option key={address.id} value={address.id}>
                  {address.full_name} — {address.street}, {address.city}
                </option>
              ))}
            </select>
          </FormField>
        )}
      </section>

      <DeliveryMethodSection
        register={register}
        error={errors.delivery_method}
      />

      <div className="flex items-center gap-4">
        <Button
          type="submit"
          width="full"
          disabled={isSubmitting || isLoadingAddresses}
        >
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

export default SavedAddressForm;
