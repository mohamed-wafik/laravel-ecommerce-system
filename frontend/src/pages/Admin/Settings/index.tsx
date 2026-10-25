import { useEffect } from "react";
import { useForm, type SubmitHandler } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import toast from "react-hot-toast";
import { Save, Settings } from "lucide-react";

import Button from "../../../components/ui/Button";
import FormField from "../../../components/ui/FormField";
import {
  storeSettingsSchema,
  type StoreSettingsFormValues,
} from "../../../schema/Admin/admin.setting.schema";
import { adminSettingAPI } from "../../../api/modules/Admin/setting.api";
import { useSettings } from "../../../hook/useSettings";

export interface IStoreSettings {
  tax_rate: number;
  delivery_fee_standard: number;
  delivery_fee_express: number;
  free_delivery_threshold: number;
  currency: string;
  store_name: string;
}

const defaultValues: StoreSettingsFormValues = {
  tax_rate: 0,
  delivery_fee_standard: 0,
  delivery_fee_express: 0,
  free_delivery_threshold: 0,
  currency: "USD",
  store_name: "",
};

const StoreSettings = () => {
  const { data, isLoading, isFetching } = useSettings();
  const {
    register,
    handleSubmit,
    reset,
    formState: { errors, isSubmitting },
  } = useForm<StoreSettingsFormValues>({
    resolver: zodResolver(storeSettingsSchema),
    defaultValues,
  });

  useEffect(() => {
    const settings: IStoreSettings | undefined = data;

    if (!settings) {
      return;
    }

    reset({
      tax_rate: Number(settings.tax_rate),
      delivery_fee_standard: Number(settings.delivery_fee_standard),
      delivery_fee_express: Number(settings.delivery_fee_express),
      free_delivery_threshold: Number(settings.free_delivery_threshold),
      currency: settings.currency,
      store_name: settings.store_name,
    });
  }, [data, reset]);

  const onSubmit: SubmitHandler<StoreSettingsFormValues> = async (values) => {
    try {
      const response = await adminSettingAPI.update(values);
      const updatedSettings: IStoreSettings = response.data?.data;

      if (updatedSettings) {
        reset({
          tax_rate: Number(updatedSettings.tax_rate),
          delivery_fee_standard: Number(updatedSettings.delivery_fee_standard),
          delivery_fee_express: Number(updatedSettings.delivery_fee_express),
          free_delivery_threshold: Number(
            updatedSettings.free_delivery_threshold,
          ),
          currency: updatedSettings.currency,
          store_name: updatedSettings.store_name,
        });
      }

      toast.success(
        response.data?.message ?? "Store settings updated successfully",
      );
    } catch (error) {
      console.error("Failed to update store settings:", error);

      toast.error("Failed to update store settings");
    }
  };

  const isBusy = isLoading || isFetching;

  return (
    <section className="space-y-6">
      {/* Header */}
      <div>
        <div className="flex items-center gap-3">
          <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
            <Settings size={21} />
          </div>

          <div>
            <h1 className="text-2xl font-bold text-text-primary">
              Store Settings
            </h1>

            <p className="mt-1 text-sm text-text-secondary">
              Manage your store information, taxes, and delivery fees.
            </p>
          </div>
        </div>
      </div>

      {/* Form */}
      <form
        onSubmit={handleSubmit(onSubmit)}
        className="rounded-2xl border border-border bg-bg-primary shadow-sm"
      >
        <div className="border-b border-border p-5">
          <h2 className="text-base font-semibold text-text-primary">
            General Settings
          </h2>

          <p className="mt-1 text-sm text-text-secondary">
            Configure the basic information and pricing rules for your store.
          </p>
        </div>

        <div className="space-y-6 p-5">
          {/* Store Information */}
          <div>
            <h3 className="mb-4 text-sm font-semibold text-text-primary">
              Store Information
            </h3>

            <div className="grid gap-5 md:grid-cols-2">
              <FormField
                htmlFor="store_name"
                label="Store Name"
                error={errors.store_name?.message}
              >
                <input
                  id="store_name"
                  type="text"
                  disabled={isBusy || isSubmitting}
                  {...register("store_name")}
                  placeholder="Enter store name..."
                  className="h-11 w-full rounded-xl border border-border bg-bg-secondary px-3.5 text-sm text-text-primary outline-none transition placeholder:text-text-tertiary focus:border-primary focus:ring-2 focus:ring-primary/10 disabled:cursor-not-allowed disabled:opacity-60"
                />
              </FormField>

              <FormField
                htmlFor="currency"
                label="Currency"
                error={errors.currency?.message}
              >
                <input
                  id="currency"
                  type="text"
                  maxLength={3}
                  disabled={isBusy || isSubmitting}
                  {...register("currency")}
                  placeholder="USD"
                  className="h-11 w-full rounded-xl border border-border bg-bg-secondary px-3.5 text-sm uppercase text-text-primary outline-none transition placeholder:text-text-tertiary focus:border-primary focus:ring-2 focus:ring-primary/10 disabled:cursor-not-allowed disabled:opacity-60"
                />
              </FormField>
            </div>
          </div>

          {/* Tax */}
          <div className="border-t border-border pt-6">
            <h3 className="mb-4 text-sm font-semibold text-text-primary">
              Tax
            </h3>

            <div className="grid gap-5 md:grid-cols-2">
              <FormField
                htmlFor="tax_rate"
                label="Tax Rate"
                error={errors.tax_rate?.message}
              >
                <div className="relative">
                  <input
                    id="tax_rate"
                    type="number"
                    step="0.01"
                    min="0"
                    max="1"
                    disabled={isBusy || isSubmitting}
                    {...register("tax_rate", {
                      valueAsNumber: true,
                    })}
                    placeholder="0.05"
                    className="h-11 w-full rounded-xl border border-border bg-bg-secondary px-3.5 pr-12 text-sm text-text-primary outline-none transition placeholder:text-text-tertiary focus:border-primary focus:ring-2 focus:ring-primary/10 disabled:cursor-not-allowed disabled:opacity-60"
                  />

                  <span className="absolute right-3.5 top-1/2 -translate-y-1/2 text-sm text-text-tertiary">
                    0–1
                  </span>
                </div>

                <p className="mt-1 text-xs text-text-tertiary">
                  Example: 0.05 = 5%
                </p>
              </FormField>
            </div>
          </div>

          {/* Delivery */}
          <div className="border-t border-border pt-6">
            <h3 className="mb-4 text-sm font-semibold text-text-primary">
              Delivery
            </h3>

            <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
              <FormField
                htmlFor="standard_delivery_fee"
                label="Standard Delivery Fee"
                error={errors.delivery_fee_standard?.message}
              >
                <input
                  id="standard_delivery_fee"
                  type="number"
                  step="0.01"
                  min="0"
                  disabled={isBusy || isSubmitting}
                  {...register("delivery_fee_standard", {
                    valueAsNumber: true,
                  })}
                  placeholder="5"
                  className="h-11 w-full rounded-xl border border-border bg-bg-secondary px-3.5 text-sm text-text-primary outline-none transition placeholder:text-text-tertiary focus:border-primary focus:ring-2 focus:ring-primary/10 disabled:cursor-not-allowed disabled:opacity-60"
                />
              </FormField>

              <FormField
                htmlFor="express_delivery_fee"
                label="Express Delivery Fee"
                error={errors.delivery_fee_express?.message}
              >
                <input
                  id="express_delivery_fee"
                  type="number"
                  step="0.01"
                  min="0"
                  disabled={isBusy || isSubmitting}
                  {...register("delivery_fee_express", {
                    valueAsNumber: true,
                  })}
                  placeholder="15"
                  className="h-11 w-full rounded-xl border border-border bg-bg-secondary px-3.5 text-sm text-text-primary outline-none transition placeholder:text-text-tertiary focus:border-primary focus:ring-2 focus:ring-primary/10 disabled:cursor-not-allowed disabled:opacity-60"
                />
              </FormField>

              <FormField
                htmlFor="fress_delivery_threshold"
                label="Free Delivery Threshold"
                error={errors.free_delivery_threshold?.message}
              >
                <input
                  id="fress_delivery_threshold"
                  type="number"
                  step="0.01"
                  min="0"
                  disabled={isBusy || isSubmitting}
                  {...register("free_delivery_threshold", {
                    valueAsNumber: true,
                  })}
                  placeholder="100"
                  className="h-11 w-full rounded-xl border border-border bg-bg-secondary px-3.5 text-sm text-text-primary outline-none transition placeholder:text-text-tertiary focus:border-primary focus:ring-2 focus:ring-primary/10 disabled:cursor-not-allowed disabled:opacity-60"
                />

                <p className="mt-1 text-xs text-text-tertiary">
                  Orders at or above this amount get free delivery.
                </p>
              </FormField>
            </div>
          </div>
        </div>

        {/* Footer */}
        <div className="flex flex-col-reverse gap-3 border-t border-border p-5 sm:flex-row sm:items-center sm:justify-end">
          <Button
            type="submit"
            disabled={isBusy || isSubmitting}
            className="inline-flex items-center justify-center gap-2"
          >
            <Save size={17} />

            {isSubmitting ? "Saving..." : "Save Changes"}
          </Button>
        </div>
      </form>
    </section>
  );
};

export default StoreSettings;
