import { z } from "zod";

export const categoryFormSchema = z.object({
  parent_id: z.string().optional(),

  name: z
    .string()
    .min(1, "Category name is required")
    .max(255, "Category name is too long"),

  slug: z.string().max(255, "Slug is too long").optional(),

  is_active: z.boolean(),

  sort_order: z.coerce
    .number()
    .int("Sort order must be an integer")
    .min(0, "Sort order cannot be negative"),
});

export type CategoryFormInput = z.input<typeof categoryFormSchema>;

export type CategoryFormValues = z.output<typeof categoryFormSchema>;
