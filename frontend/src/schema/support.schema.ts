import { z } from "zod";

export const contactSupportSchema = z.object({
  order_id: z
    .string()
    .uuid("Order ID must be a valid UUID")
    .optional()
    .or(z.literal("")),

  name: z
    .string()
    .min(1, "Name is required")
    .max(255, "Name must not exceed 255 characters"),

  email: z
    .string()
    .min(1, "Email is required")
    .email("Please enter a valid email")
    .max(255, "Email must not exceed 255 characters"),

  phone: z
    .string()
    .max(30, "Phone number must not exceed 30 characters")
    .optional()
    .or(z.literal("")),

  subject: z.enum(
    ["order_issue", "payment_issue", "product_question", "complaint", "other"],
    {
      message: "Please choose a subject",
    },
  ),

  message: z
    .string()
    .min(1, "Message is required")
    .max(5000, "Message must not exceed 5000 characters"),
});

export type ContactSupportInput = z.infer<typeof contactSupportSchema>;
