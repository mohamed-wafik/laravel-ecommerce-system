import z from "zod";

export const addressSchema = z.object({
  label: z
    .string()
    .min(2, "Label must be at least 2 characters")
    .max(50, "Label is too long"),

  full_name: z
    .string()
    .min(2, "Full name is required")
    .max(100, "Full name is too long"),

  phone: z
    .string()
    .min(8, "Phone number is required")
    .max(20, "Phone number is too long"),

  country: z.string().min(2, "Country is required").max(100),

  city: z.string().min(2, "City is required").max(100),

  street: z.string().min(3, "Street address is required").max(255),

  postal_code: z.string().min(2, "Postal code is required").max(20),

  is_default: z.boolean(),
});

export type AddressFormValues = z.infer<typeof addressSchema>;
// Form 1: New address
export const newAddressSchema = z.object({
  full_name: z
    .string()
    .trim()
    .min(2, "Full name must be at least 2 characters")
    .max(255, "Full name is too long"),

  phone: z
    .string()
    .trim()
    .min(7, "Please enter a valid phone number")
    .max(30, "Phone number is too long"),

  country: z.string().trim().min(2, "Country is required").max(100),

  city: z.string().trim().min(2, "City is required").max(100),

  street: z.string().trim().min(3, "Street is required").max(255),

  postal_code: z
    .string()
    .trim()
    .max(20, "Postal code is too long")
    .optional()
    .or(z.literal("")),

  label: z
    .string()
    .trim()
    .max(50, "Label is too long")
    .optional()
    .or(z.literal("")),

  delivery_method: z.enum(["standard", "express"], {
    message: "Please select a delivery method",
  }),
});

export type NewAddressFormValues = z.infer<typeof newAddressSchema>;

// Form 2: Saved address
export const savedAddressSchema = z.object({
  shipping_address_id: z
    .string()
    .min(1, "Please select a shipping address.")
    .uuid("Invalid address selected"),

  delivery_method: z.enum(["standard", "express"], {
    message: "Please select a delivery method",
  }),
});

export type SavedAddressFormValues = z.infer<typeof savedAddressSchema>;
