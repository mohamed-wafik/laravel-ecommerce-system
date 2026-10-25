import { z } from "zod";

export const supportReplySchema = z.object({
  message: z
    .string()
    .min(1, "Reply message is required")
    .max(5000, "Message must not exceed 5000 characters"),
});

export type SupportReplyFormValues = z.infer<typeof supportReplySchema>;
