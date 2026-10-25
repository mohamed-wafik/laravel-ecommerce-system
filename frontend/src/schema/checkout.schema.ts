import { z } from "zod";

export const shippingSchema = z.object({
  customer_name: z
    .string()
    .min(1, "Full name is required")
    .min(2, "Name must be at least 2 characters"),

  customer_email: z
    .string()
    .min(1, "Email is required")
    .email("Enter a valid email address"),

  customer_phone: z
    .string()
    .min(1, "Phone number is required")
    .regex(/^\+?[\d\s\-()]{8,}$/, "Enter a valid phone number"),

  shipping_address: z
    .string()
    .min(1, "Address is required")
    .min(5, "Please enter a full address"),

  city: z.string().min(1, "City is required"),

  // Optional — no .default() so the inferred type stays string | undefined,
  // matching what RHF expects from an unfilled optional field.
  postal_code: z.string().optional(),

  // Required enums — no .default() on either.
  // Defaults belong in useForm({ defaultValues }) only.
  shipping_method: z.enum(["standard", "express", "pickup"] as const, {
    message: "Select a shipping method",
  }),

  payment_method: z.enum(["card", "cod", "wallet"] as const, {
    message: "Select a payment method",
  }),
});

export type ShippingInput = z.infer<typeof shippingSchema>;

// ── Shipping cost lookup (mirrors backend config) ─────────────────────────────
export const SHIPPING_COSTS: Record<ShippingInput["shipping_method"], number> =
  {
    standard: 30,
    express: 60,
    pickup: 0,
  };

export const TAX_RATE = 0.14;
