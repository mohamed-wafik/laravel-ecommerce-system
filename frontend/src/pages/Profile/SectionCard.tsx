import type { ReactNode } from "react";
import type { LucideIcon } from "lucide-react";

interface SectionCardProps {
  icon: LucideIcon;
  title: string;
  description?: string;
  children: ReactNode;
  /** Red tint for destructive sections */
  danger?: boolean;
}

const SectionCard = ({
  icon: Icon,
  title,
  description,
  children,
  danger = false,
}: SectionCardProps) => (
  <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    {/* Header */}
    <div
      className={`px-6 py-5 border-b border-gray-100 ${danger ? "bg-red-50/60" : ""}`}
    >
      <div className="flex items-center gap-3">
        <div
          className={`w-9 h-9 rounded-xl flex items-center justify-center ${
            danger ? "bg-red-100" : "bg-primary/10"
          }`}
        >
          <Icon
            className={`w-4 h-4 ${danger ? "text-red-500" : "text-primary"}`}
          />
        </div>
        <div>
          <h3
            className={`text-sm font-extrabold ${danger ? "text-red-600" : "text-dark"}`}
          >
            {title}
          </h3>
          {description && (
            <p className="text-xs text-gray-400 mt-0.5">{description}</p>
          )}
        </div>
      </div>
    </div>

    {/* Body */}
    <div className="p-6">{children}</div>
  </div>
);

export default SectionCard;
