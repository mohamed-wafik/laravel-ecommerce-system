import {
  Headphones,
  Shield,
  Truck,
  Undo2,
  type LucideIcon,
} from "lucide-react";

const BADGES: {
  icon: LucideIcon;
  label: string;
}[] = [
  {
    icon: Shield,
    label: "2-Year Warranty",
  },
  {
    icon: Truck,
    label: "Free Shipping",
  },
  {
    icon: Undo2,
    label: "30-Day Returns",
  },
  {
    icon: Headphones,
    label: "24/7 Support",
  },
];

const TrustBadges = () => {
  return (
    <div className="grid grid-cols-2 gap-2.5 border-t border-gray-100 pt-6 sm:gap-3">
      {BADGES.map(({ icon: Icon, label }) => (
        <div
          key={label}
          className="flex min-w-0 items-center gap-2 rounded-xl border border-gray-100 bg-gray-50 p-2.5 sm:gap-2.5 sm:p-3"
        >
          <Icon className="h-4 w-4 shrink-0 text-primary" />

          <span className="truncate text-[10px] font-medium text-gray-600 sm:text-xs">
            {label}
          </span>
        </div>
      ))}
    </div>
  );
};

export default TrustBadges;
