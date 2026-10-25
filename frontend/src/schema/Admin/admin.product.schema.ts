import z from "zod";

export const variantSchema = z.object({
  color: z.string().max(100).optional(),
  size: z.string().max(50).optional(),
  sku: z.string().min(1, "Variant SKU is required").max(100),
  price_override: z.union([z.coerce.number().min(0), z.literal("")]).optional(),
  stock_quantity: z.coerce.number().int().min(0).optional(),
});

export const productFormSchema = z.object({
  category_id: z.string().min(1, "Choose a category"),
  brand_id: z.string().optional(),
  name: z.string().min(2, "Name is required").max(255),
  slug: z.string().max(255).optional(),
  description: z.string().max(5000).optional(),
  price: z.coerce.number().min(0, "Price must be 0 or greater"),
  compare_price: z.union([z.coerce.number().min(0), z.literal("")]).optional(),
  sku: z.string().min(1, "SKU is required").max(100),
  stock_quantity: z.coerce.number().int().min(0).optional(),
  is_active: z.boolean(),
  variants: z.array(variantSchema).optional(),
});

export type ProductFormInput = z.input<typeof productFormSchema>;
export type ProductFormValues = z.output<typeof productFormSchema>;
