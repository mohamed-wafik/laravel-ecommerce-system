import { Check, Loader2 } from "lucide-react";
import Button from "../../components/ui/Button";
import type { AddressFormValues } from "../../schema/shippingAddress.schema";
import type { useForm } from "react-hook-form";
import type { IAddress } from "../../interface";
import Model from "../../components/ui/Model";
import Input from "../../components/ui/Input";
import FormField from "../../components/ui/FormField";

interface IProps {
  editingAddress: IAddress | null;
  register: ReturnType<typeof useForm<AddressFormValues>>["register"];
  errors: ReturnType<typeof useForm<AddressFormValues>>["formState"]["errors"];
  isSubmitting: boolean;
  onSubmit: React.FormEventHandler<HTMLFormElement>;
  onClose: () => void;
}

const AddressModal = ({
  editingAddress,
  register,
  errors,
  isSubmitting,
  onSubmit,
  onClose,
}: IProps) => {
  return (
    <Model
      onClose={onClose}
      title={editingAddress ? "Edit Address" : "Add New Address"}
      subTitle={
        editingAddress
          ? "Update your delivery information."
          : "Add a new delivery address."
      }
      isDisabled={isSubmitting}
    >
      {/* Form */}
      <form onSubmit={onSubmit} className="p-5 sm:p-6">
        <div className="grid gap-5 sm:grid-cols-2">
          <FormField
            htmlFor="label"
            label="Address Label"
            error={errors.label?.message}
          >
            <Input
              id="label"
              variant={errors.label?.message ? "error" : "default"}
              {...register("label")}
              placeholder="Home"
            />
          </FormField>

          <FormField
            htmlFor="full_name"
            label="Full Name"
            error={errors.full_name?.message}
          >
            <Input
              id="full_name"
              variant={errors.full_name?.message ? "error" : "default"}
              {...register("full_name")}
              placeholder="Mohamed Wafik"
            />
          </FormField>

          <FormField
            htmlFor="phpone"
            label="Phone"
            error={errors.phone?.message}
          >
            <Input
              id="phone"
              variant={errors.phone?.message ? "error" : "default"}
              {...register("phone")}
              type="tel"
              placeholder="01134897507"
            />
          </FormField>

          <FormField
            htmlFor="countru"
            label="Country"
            error={errors.country?.message}
          >
            <Input
              id="country"
              variant={errors.country?.message ? "error" : "default"}
              {...register("country")}
              placeholder="Egypt"
            />
          </FormField>

          <FormField htmlFor="city" label="City" error={errors.city?.message}>
            <Input
              id="city"
              variant={errors.city?.message ? "error" : "default"}
              {...register("city")}
              placeholder="Cairo"
            />
          </FormField>

          <FormField
            htmlFor="postal_code"
            label="Postal Code"
            error={errors.postal_code?.message}
          >
            <Input
              id="postal_code"
              variant={errors.postal_code?.message ? "error" : "default"}
              {...register("postal_code")}
              placeholder="1234"
            />
          </FormField>

          <div className="sm:col-span-2">
            <FormField
              htmlFor="street"
              label="Street Address"
              error={errors.street?.message}
            >
              <textarea
                id="street"
                {...register("street")}
                rows={3}
                placeholder="10th street"
                className={`${inputClass(!!errors.street)} resize-none`}
              />
            </FormField>
          </div>

          {/* Default */}
          <div className="sm:col-span-2">
            <label className="flex cursor-pointer items-center gap-3 rounded-xl border border-border bg-bg-secondary p-4 transition hover:border-primary/40">
              <input
                {...register("is_default")}
                type="checkbox"
                className="h-4 w-4 rounded border-border text-primary accent-primary focus:ring-primary"
              />

              <div>
                <p className="text-sm font-semibold text-text-primary">
                  Make this my default address
                </p>

                <p className="mt-0.5 text-xs text-text-secondary">
                  Use this address automatically during checkout.
                </p>
              </div>
            </label>
          </div>
        </div>

        {/* Actions */}
        <div className="mt-7 flex flex-col-reverse gap-3 border-t border-border-light pt-5 sm:flex-row sm:justify-end">
          <Button
            type="button"
            onClick={onClose}
            disabled={isSubmitting}
            variant={"outline"}
          >
            Cancel
          </Button>

          <Button type="submit" className="flex gap-2" disabled={isSubmitting}>
            {isSubmitting ? (
              <>
                <Loader2 size={17} className="animate-spin" />
                Saving...
              </>
            ) : (
              <>
                <Check size={17} />
                {editingAddress ? "Save Changes" : "Add Address"}
              </>
            )}
          </Button>
        </div>
      </form>
    </Model>
  );
};

/* =========================================================
   Input Class
========================================================= */

const inputClass = (hasError: boolean) =>
  [
    "w-full rounded-xl border bg-bg-primary px-4 py-3 text-sm text-text-primary",
    "outline-none transition",
    "placeholder:text-text-tertiary",
    "focus:ring-2 focus:ring-primary/10",
    hasError
      ? "border-error focus:border-error"
      : "border-border focus:border-primary",
  ].join(" ");

export default AddressModal;
