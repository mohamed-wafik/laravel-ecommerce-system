import { z } from "zod";

export const brandFormSchema = z.object({
  name: z
    .string()
    .trim()
    .min(2, "Brand name must be at least 2 characters")
    .max(100, "Brand name must not exceed 100 characters"),

  slug: z
    .string()
    .trim()
    .max(120, "Slug must not exceed 120 characters")
    .optional()
    .or(z.literal("")),

  is_active: z.boolean(),
});

export type BrandFormInput = z.input<typeof brandFormSchema>;
export type BrandFormValues = z.output<typeof brandFormSchema>;
