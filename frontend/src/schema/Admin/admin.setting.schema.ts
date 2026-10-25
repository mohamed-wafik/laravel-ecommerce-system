import z from "zod";

export const storeSettingsSchema = z.object({
  tax_rate: z
    .number()
    .min(0, "Tax rate cannot be less than 0")
    .max(1, "Tax rate cannot be greater than 1"),

  delivery_fee_standard: z
    .number()
    .min(0, "Standard delivery fee cannot be negative"),

  delivery_fee_express: z
    .number()
    .min(0, "Express delivery fee cannot be negative"),

  free_delivery_threshold: z
    .number()
    .min(0, "Free delivery threshold cannot be negative"),

  currency: z
    .string()
    .length(3, "Currency must contain exactly 3 characters")
    .transform((value) => value.toUpperCase()),

  store_name: z
    .string()
    .max(255, "Store name cannot exceed 255 characters")
    .min(1, "Store name is required"),
});

export type StoreSettingsFormValues = z.infer<typeof storeSettingsSchema>;
