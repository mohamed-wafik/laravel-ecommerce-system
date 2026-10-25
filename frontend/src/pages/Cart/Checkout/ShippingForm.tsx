import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { Truck, ChevronRight } from "lucide-react";
import {
  SHIPPING_COSTS,
  shippingSchema,
  type ShippingInput,
} from "../../../schema/checkout.schema";

// ── Shared field wrapper ──────────────────────────────────────────────────────
const Field = ({
  label,
  error,
  children,
  required = false,
}: {
  label: string;
  error?: string;
  children: React.ReactNode;
  required?: boolean;
}) => (
  <div className="flex flex-col gap-1.5">
    <label className="text-sm font-semibold text-dark">
      {label}
      {required && <span className="text-red-400 ml-0.5">*</span>}
    </label>
    {children}
    <p
      className={`text-xs min-h-4 ${error ? "text-red-500" : "text-transparent"}`}
    >
      {error ?? "error"}
    </p>
  </div>
);

const inputClass = (hasError?: string) =>
  `w-full px-4 py-3 rounded-xl border text-sm text-dark placeholder:text-gray-400 bg-white outline-none transition-colors
   ${
     hasError
       ? "border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-100"
       : "border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/10"
   }`;

// ── Shipping method option ────────────────────────────────────────────────────
const ShippingOption = ({
  label,
  description,
  price,
  selected,
  onClick,
}: {
  value: string;
  label: string;
  description: string;
  price: string;
  selected: boolean;
  onClick: () => void;
}) => (
  <button
    type="button"
    onClick={onClick}
    className={`w-full flex items-center gap-4 p-4 rounded-xl border-2 text-left transition-all
      ${
        selected
          ? "border-primary bg-primary/5"
          : "border-gray-200 hover:border-gray-300 bg-white"
      }`}
  >
    <div
      className={`w-4 h-4 rounded-full border-2 shrink-0 flex items-center justify-center
      ${selected ? "border-primary" : "border-gray-300"}`}
    >
      {selected && <div className="w-2 h-2 rounded-full bg-primary" />}
    </div>
    <div className="flex-1">
      <p className="text-sm font-semibold text-dark">{label}</p>
      <p className="text-xs text-gray-500 mt-0.5">{description}</p>
    </div>
    <span
      className={`text-sm font-bold shrink-0 ${selected ? "text-primary" : "text-dark"}`}
    >
      {price}
    </span>
  </button>
);

// ── Component ─────────────────────────────────────────────────────────────────
interface ShippingFormProps {
  onSubmit: (data: ShippingInput) => void;
  defaultValues?: Partial<ShippingInput>;
}

const ShippingForm = ({ onSubmit, defaultValues = {} }: ShippingFormProps) => {
  const {
    register,
    handleSubmit,
    watch,
    setValue,
    formState: { errors },
  } = useForm<ShippingInput>({
    resolver: zodResolver(shippingSchema),
    defaultValues: {
      customer_name: "",
      customer_email: "",
      customer_phone: "",
      shipping_address: "",
      city: "",
      postal_code: "",
      shipping_method: "standard",
      payment_method: "cod",
      ...defaultValues,
    },
  });

  const selectedMethod = watch("shipping_method");

  return (
    <form onSubmit={handleSubmit(onSubmit)} noValidate className="space-y-1">
      <div className="flex items-center gap-3 mb-6">
        <div className="w-1 h-6 bg-primary rounded-full" />
        <h2 className="text-xl font-extrabold text-dark">
          Shipping Information
        </h2>
      </div>

      {/* Personal info */}
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-x-4">
        <Field label="Full name" error={errors.customer_name?.message} required>
          <input
            type="text"
            placeholder="John Doe"
            autoComplete="name"
            className={inputClass(errors.customer_name?.message)}
            {...register("customer_name")}
          />
        </Field>

        <Field
          label="Email address"
          error={errors.customer_email?.message}
          required
        >
          <input
            type="email"
            placeholder="you@example.com"
            autoComplete="email"
            className={inputClass(errors.customer_email?.message)}
            {...register("customer_email")}
          />
        </Field>
      </div>

      <Field
        label="Phone number"
        error={errors.customer_phone?.message}
        required
      >
        <input
          type="tel"
          placeholder="+20 123 456 7890"
          autoComplete="tel"
          className={inputClass(errors.customer_phone?.message)}
          {...register("customer_phone")}
        />
      </Field>

      <Field
        label="Street address"
        error={errors.shipping_address?.message}
        required
      >
        <textarea
          rows={2}
          placeholder="Street, building, apartment…"
          className={`${inputClass(errors.shipping_address?.message)} resize-none`}
          {...register("shipping_address")}
        />
      </Field>

      <div className="grid grid-cols-2 gap-4">
        <Field label="City" error={errors.city?.message} required>
          <input
            type="text"
            placeholder="Cairo"
            autoComplete="address-level2"
            className={inputClass(errors.city?.message)}
            {...register("city")}
          />
        </Field>

        <Field label="Postal code" error={errors.postal_code?.message}>
          <input
            type="text"
            placeholder="12345"
            autoComplete="postal-code"
            className={inputClass()}
            {...register("postal_code")}
          />
        </Field>
      </div>

      {/* Shipping method */}
      <div className="pt-2">
        <div className="flex items-center gap-2 mb-3">
          <Truck className="w-4 h-4 text-primary" />
          <span className="text-sm font-semibold text-dark">
            Shipping method
          </span>
        </div>

        <div className="space-y-2">
          <ShippingOption
            value="standard"
            label="Standard Shipping"
            description="3–5 business days"
            price={`${SHIPPING_COSTS.standard} EGP`}
            selected={selectedMethod === "standard"}
            onClick={() => setValue("shipping_method", "standard")}
          />
          <ShippingOption
            value="express"
            label="Express Shipping"
            description="1–2 business days"
            price={`${SHIPPING_COSTS.express} EGP`}
            selected={selectedMethod === "express"}
            onClick={() => setValue("shipping_method", "express")}
          />
          <ShippingOption
            value="pickup"
            label="Store Pickup"
            description="Pick up from our store"
            price="FREE"
            selected={selectedMethod === "pickup"}
            onClick={() => setValue("shipping_method", "pickup")}
          />
        </div>
        {errors.shipping_method && (
          <p className="text-xs text-red-500 mt-1">
            {errors.shipping_method.message}
          </p>
        )}
      </div>

      <div className="pt-4">
        <button
          type="submit"
          className="w-full flex items-center justify-center gap-2 bg-primary text-white font-bold text-sm py-3.5 rounded-xl hover:opacity-90 active:scale-[0.98] transition-all"
        >
          Continue to Review
          <ChevronRight className="w-4 h-4" />
        </button>
      </div>
    </form>
  );
};

export default ShippingForm;
