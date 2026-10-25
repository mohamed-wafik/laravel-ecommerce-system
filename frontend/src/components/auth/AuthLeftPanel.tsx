import type { LucideIcon } from "lucide-react";
import { ShieldCheck, Zap, Package, Headphones } from "lucide-react";

interface Feature {
  icon: LucideIcon;
  title: string;
  description: string;
}

interface AuthLeftPanelProps {
  headline: string;
  subheadline: string;
  features?: Feature[];
  quote?: {
    text: string;
    author: string;
    role: string;
  };
}

const DEFAULT_FEATURES: Feature[] = [
  {
    icon: ShieldCheck,
    title: "Secure & Private",
    description: "Your data is encrypted end-to-end",
  },
  {
    icon: Zap,
    title: "Lightning Fast",
    description: "Optimised for the best shopping experience",
  },
  {
    icon: Package,
    title: "Free Delivery",
    description: "On all orders over $50",
  },
  {
    icon: Headphones,
    title: "24/7 Support",
    description: "We're always here to help",
  },
];

const AuthLeftPanel = ({
  headline,
  subheadline,
  features = DEFAULT_FEATURES,
  quote,
}: AuthLeftPanelProps) => (
  <div className="relative w-full bg-primary overflow-hidden flex flex-col justify-between p-12">
    {/* ── Decorative blobs ── */}
    <div className="absolute -top-24 -left-24 w-96 h-96 bg-white/5 rounded-full" />
    <div className="absolute -bottom-32 -right-20 w-[28rem] h-[28rem] bg-white/5 rounded-full" />
    <div className="absolute top-1/2 -translate-y-1/2 -right-16 w-64 h-64 bg-white/5 rounded-full" />

    {/* ── Brand ── */}
    <div className="relative z-10">
      <div className="flex items-center gap-2 mb-16">
        <div className="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
          <span className="text-primary font-black text-sm">S</span>
        </div>
        <span className="text-white font-bold text-lg tracking-tight">
          ShopApp
        </span>
      </div>

      {/* Headline */}
      <h2 className="text-4xl xl:text-5xl font-black text-white leading-tight mb-4">
        {headline}
      </h2>
      <p className="text-white/70 text-base leading-relaxed max-w-sm">
        {subheadline}
      </p>
    </div>

    {/* ── Feature list ── */}
    <div className="relative z-10 space-y-5 my-10">
      {features.map(({ icon: Icon, title, description }) => (
        <div key={title} className="flex items-start gap-4">
          <div className="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
            <Icon className="w-4 h-4 text-white" />
          </div>
          <div>
            <p className="text-white font-semibold text-sm">{title}</p>
            <p className="text-white/60 text-xs mt-0.5">{description}</p>
          </div>
        </div>
      ))}
    </div>
  </div>
);

export default AuthLeftPanel;
