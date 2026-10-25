import { z } from "zod";

export const userFormSchema = z.object({
  name: z
    .string()
    .min(1, "Name is required")
    .max(255, "Name must not exceed 255 characters"),

  email: z
    .string()
    .min(1, "Email is required")
    .email("Enter a valid email address"),

  phone: z
    .string()
    .max(30, "Phone must not exceed 30 characters")
    .optional()
    .or(z.literal("")),

  password: z
    .string()
    .min(8, "Password must be at least 8 characters")
    .optional()
    .or(z.literal("")),

  role: z.enum(["customer", "admin"]),
});

export type UserFormInput = z.input<typeof userFormSchema>;

export type UserFormValues = z.output<typeof userFormSchema>;
