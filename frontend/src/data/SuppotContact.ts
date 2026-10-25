import { Mail, MessageCircle, Phone } from "lucide-react";

export const SUPPORT_SUBJECTS = [
  {
    value: "order_issue",
    label: "Order issue",
  },
  {
    value: "payment_issue",
    label: "Payment issue",
  },
  {
    value: "product_question",
    label: "Product question",
  },
  {
    value: "complaint",
    label: "Complaint",
  },
  {
    value: "other",
    label: "Other",
  },
] as const;

export const CHANNELS = [
  {
    icon: MessageCircle,
    title: "Live chat",
    detail: "Get a reply in minutes",
    action: "Start chat",
    href: "#",
  },
  {
    icon: Mail,
    title: "Email us",
    detail: "support@example.com",
    action: "Send email",
    href: "mailto:support@example.com",
  },
  {
    icon: Phone,
    title: "Call us",
    detail: "+1 (800) 555-0134",
    action: "Call now",
    href: "tel:+18005550134",
  },
];
