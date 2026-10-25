import {
  KeyRound,
  ShieldCheck,
  Clock,
  Mail,
  Lock,
  CheckCircle,
  Gift,
  Star,
  Users,
  Award,
  Package,
  Zap,
  MailCheck,
} from "lucide-react";

export const AUTH_CONTENT = {
  login: {
    headline: "Shop smarter, live better.",
    subheadline:
      "Sign in to access your personalised dashboard, track orders, and discover deals curated just for you.",
    features: [
      {
        icon: Gift,
        title: "Exclusive Deals",
        description: "Members get access to flash sales and early drops",
      },
      {
        icon: Star,
        title: "Rewards Points",
        description: "Earn points on every purchase, redeem for discounts",
      },
      {
        icon: Users,
        title: "Trusted by Thousands",
        description: "Join over 50,000 happy customers worldwide",
      },
    ],
  },

  register: {
    headline: "Your next favourite store starts here.",
    subheadline:
      "Create a free account and unlock personalised recommendations, exclusive offers, and seamless order management.",
    features: [
      {
        icon: Award,
        title: "Welcome Bonus",
        description: "Get 200 reward points when you complete signup",
      },
      {
        icon: Package,
        title: "Order Tracking",
        description: "Real-time updates on every package you order",
      },
      {
        icon: Zap,
        title: "Faster Checkout",
        description: "Save addresses and cards for one-tap purchases",
      },
      {
        icon: ShieldCheck,
        title: "Buyer Protection",
        description: "Full refund guaranteed if anything goes wrong",
      },
    ],
  },

  forgotPassword: {
    headline: "Locked out? We've got you.",
    subheadline:
      "Follow the steps to securely regain access to your account in seconds.",
    features: [
      {
        icon: KeyRound,
        title: "Quick recovery",
        description: "Reset your password in under two minutes",
      },
      {
        icon: ShieldCheck,
        title: "Secure process",
        description: "Codes are single-use and expire after 10 minutes",
      },
      {
        icon: Clock,
        title: "Instant delivery",
        description: "The reset code arrives in your inbox right away",
      },
    ],
  },

  verifyOtp: {
    headline: "Verify your identity",
    subheadline:
      "Enter the verification code sent to your email to continue securely.",
    features: [
      {
        icon: Mail,
        title: "Email verification",
        description: "Confirm ownership of your account",
      },
      {
        icon: ShieldCheck,
        title: "Extra security",
        description: "Prevent unauthorized access",
      },
      {
        icon: Clock,
        title: "Quick process",
        description: "Verification takes only a few seconds",
      },
    ],
  },

  resetPassword: {
    headline: "Create a new password",
    subheadline:
      "Choose a strong password to secure your account and continue safely.",
    features: [
      {
        icon: Lock,
        title: "Strong protection",
        description: "Use a secure password for maximum safety",
      },
      {
        icon: ShieldCheck,
        title: "Account security",
        description: "Keep your account protected from threats",
      },
      {
        icon: CheckCircle,
        title: "Ready to go",
        description: "Get back into your account immediately",
      },
    ],
  },

  verifyOtpReg: {
    headline: "One last step.",
    subheadline:
      "Verify your email address to unlock your account and start shopping.",
    features: [
      {
        icon: MailCheck,
        title: "Check your inbox",
        description: "The code was sent immediately after registration",
      },
      {
        icon: Lock,
        title: "One-time code",
        description: "Each code expires after 10 minutes for your security",
      },
      {
        icon: Zap,
        title: "Instant access",
        description: "Once verified you're taken straight to your dashboard",
      },
    ],
  },
};

export const STRENGTH = [
  { label: "Too short", color: "bg-gray-200" },
  { label: "Weak", color: "bg-red-400" },
  { label: "Fair", color: "bg-yellow-400" },
  { label: "Good", color: "bg-blue-400" },
  { label: "Strong", color: "bg-green-500" },
] as const;
