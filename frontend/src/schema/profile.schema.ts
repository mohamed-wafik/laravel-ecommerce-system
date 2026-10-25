import { z } from "zod";

// ── Update profile (name only — avatar handled via FileInput, not RHF) ────────
export const updateProfileSchema = z.object({
  name: z
    .string()
    .min(3, "Name must be at least 3 characters")
    .max(50, "Name must be under 50 characters")
    .optional()
    .or(z.literal("")),
});

// ── Change password ───────────────────────────────────────────────────────────
export const changePasswordSchema = z
  .object({
    current_password: z.string().min(1, "Current password is required"),
    new_password: z
      .string()
      .min(1, "New password is required")
      .min(8, "Must be at least 8 characters")
      .regex(/[A-Z]/, "Must contain an uppercase letter")
      .regex(/[0-9]/, "Must contain a number")
      .regex(/[^A-Za-z0-9]/, "Must contain a special character"),
    new_password_confirmation: z
      .string()
      .min(1, "Please confirm your new password"),
  })
  .refine((d) => d.new_password === d.new_password_confirmation, {
    message: "Passwords do not match",
    path: ["new_password_confirmation"],
  })
  .refine((d) => d.current_password !== d.new_password, {
    message: "New password must differ from current password",
    path: ["new_password"],
  });

// ── Change email ──────────────────────────────────────────────────────────────
export const changeEmailSchema = z.object({
  new_email: z
    .string()
    .min(1, "Email is required")
    .email("Enter a valid email address"),
  password: z.string().min(1, "Password is required to confirm"),
});

// ── Inferred types ────────────────────────────────────────────────────────────
export type UpdateProfileInput = z.infer<typeof updateProfileSchema>;
export type ChangePasswordInput = z.infer<typeof changePasswordSchema>;
export type ChangeEmailInput = z.infer<typeof changeEmailSchema>;
