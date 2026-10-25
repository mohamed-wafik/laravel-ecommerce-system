import { z } from "zod";

/**
 * Validation schema for the contact support form.
 * Shared between the client (react-hook-form resolver) and, ideally,
 * the API route/handler that receives the submission — import this same
 * file server-side rather than re-declaring the rules there.
 */
export const contactSupportSchema = z.object({
  name: z
    .string()
    .trim()
    .min(2, "Enter your full name")
    .max(80, "Name is too long"),
  email: z
    .string()
    .trim()
    .min(1, "Email is required")
    .email("Enter a valid email address"),
  orderNumber: z
    .string()
    .trim()
    .max(40, "Order number is too long")
    .optional()
    .or(z.literal("")),
  subject: z.enum(
    [
      "Order status",
      "Returns & refunds",
      "Product question",
      "Payment issue",
      "Something else",
    ],
    { error: "Choose a topic" },
  ),
  message: z
    .string()
    .trim()
    .min(10, "Give us a few more details (10+ characters)")
    .max(2000, "Message is too long"),
});

export type ContactSupportInput = z.infer<typeof contactSupportSchema>;
