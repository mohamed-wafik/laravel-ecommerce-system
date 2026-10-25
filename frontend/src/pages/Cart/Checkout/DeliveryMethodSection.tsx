import { Truck } from "lucide-react";
import type { UseFormRegister, FieldError } from "react-hook-form";
import ErrorMessage from "../../../components/ui/ErrorMessage";
import Input from "../../../components/ui/Input";

interface DeliveryMethodSectionProps<
  T extends { delivery_method: "standard" | "express" },
> {
  register: UseFormRegister<T>;
  error?: FieldError;
}

function DeliveryMethodSection<
  T extends { delivery_method: "standard" | "express" },
>({ register, error }: DeliveryMethodSectionProps<T>) {
  return (
    <section className="rounded-2xl border border-border bg-bg-primary p-4">
      <div className="mb-5 flex items-center gap-3">
        <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-secondary-50 text-secondary">
          <Truck className="h-4 w-4" />
        </div>

        <div>
          <h2 className="font-medium text-text-primary">Delivery method</h2>
          <p className="text-xs text-text-tertiary">
            Choose how you want your order delivered
          </p>
        </div>
      </div>

      <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
        <label className="relative cursor-pointer">
          <Input
            type="radio"
            value="standard"
            {...register("delivery_method" as any)}
            className="peer sr-only"
          />

          <div className="rounded-xl border border-border bg-bg-primary p-4 transition-all hover:border-primary-light peer-checked:border-primary peer-checked:bg-primary-50">
            <div className="flex items-start justify-between gap-3">
              <div>
                <p className="text-sm font-semibold text-text-primary">
                  Standard
                </p>
                <p className="mt-1 text-xs text-text-tertiary">
                  3–5 business days
                </p>
              </div>

              <div className="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-border peer-checked:border-primary">
                <div className="h-2.5 w-2.5 rounded-full bg-transparent transition-colors peer-checked:bg-primary" />
              </div>
            </div>
          </div>
        </label>

        <label className="relative cursor-pointer">
          <Input
            type="radio"
            value="express"
            {...register("delivery_method" as any)}
            className="peer sr-only"
          />

          <div className="rounded-xl border border-border bg-bg-primary p-4 transition-all hover:border-primary-light peer-checked:border-primary peer-checked:bg-primary-50">
            <div className="flex items-start justify-between gap-3">
              <div>
                <p className="text-sm font-semibold text-text-primary">
                  Express
                </p>
                <p className="mt-1 text-xs text-text-tertiary">
                  1–2 business days
                </p>
              </div>

              <div className="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-border">
                <div className="h-2.5 w-2.5 rounded-full bg-transparent" />
              </div>
            </div>
          </div>
        </label>
      </div>

      {error && <ErrorMessage message={error.message} />}
    </section>
  );
}

export default DeliveryMethodSection;
