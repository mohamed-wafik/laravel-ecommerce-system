import { z } from "zod";

export const adminUserSchema = z.object({
  name: z.string().min(2, "Name is too short").max(255),
  email: z.string().email("Enter a valid email address"),
  phone: z.string().max(30).optional().or(z.literal("")),
  password: z
    .string()
    .min(8, "Must be at least 8 characters")
    .optional()
    .or(z.literal("")),
  role: z.enum(["customer", "admin"]),
});

export type AdminUserFormValues = z.infer<typeof adminUserSchema>;

export const categorySchema = z.object({
  parent_id: z.string().uuid().optional().or(z.literal("")),
  name: z.string().min(2, "Name is required").max(255),
  is_active: z.boolean().optional(),
  sort_order: z.coerce.number().int().min(0).optional(),
});

export type CategoryFormValues = z.infer<typeof categorySchema>;

export const brandSchema = z.object({
  name: z.string().min(2, "Name is required").max(255),
  is_active: z.boolean().optional(),
});

export type BrandFormValues = z.infer<typeof brandSchema>;

/**
 * `images`/`variants` are handled outside this schema (real File objects
 * and a dynamic field array respectively — see AdminProductForm) but
 * every scalar product field is validated the same way the backend's
 * StoreProductRequest validates them, so an invalid submission is caught
 * client-side before a round-trip.
 */
export const productSchema = z.object({
  category_id: z.string().uuid("Choose a category"),
  brand_id: z.string().uuid().optional().or(z.literal("")),
  name: z.string().min(2, "Name is required").max(255),
  description: z.string().max(5000).optional().or(z.literal("")),
  price: z.coerce.number().positive("Price must be greater than 0"),
  compare_price: z.coerce.number().positive().optional(),
  sku: z.string().min(1, "SKU is required").max(100),
  stock_quantity: z.coerce.number().int().min(0),
  is_active: z.boolean().optional(),
});

export type ProductFormValues = z.infer<typeof productSchema>;

export const productVariantSchema = z.object({
  color: z.string().max(100).optional().or(z.literal("")),
  size: z.string().max(50).optional().or(z.literal("")),
  sku: z.string().min(1, "Variant SKU is required").max(100),
  stock_quantity: z.coerce.number().int().min(0),
});

export type ProductVariantFormValues = z.infer<typeof productVariantSchema>;

export const settingsSchema = z.object({
  tax_rate: z.coerce.number().min(0).max(1),
  delivery_fee_standard: z.coerce.number().min(0),
  delivery_fee_express: z.coerce.number().min(0),
  free_delivery_threshold: z.coerce.number().min(0),
  currency: z.string().length(3, "Use a 3-letter currency code"),
  store_name: z.string().min(1).max(255),
});

export type SettingsFormValues = z.infer<typeof settingsSchema>;
