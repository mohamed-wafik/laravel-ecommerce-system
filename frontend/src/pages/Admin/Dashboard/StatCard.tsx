import type { LucideIcon } from "lucide-react";

interface IProps {
  label: string;
  value: string | number;
  description: string;
  icon: LucideIcon;
  iconClass: string;
}

const StatCard = ({
  label,
  value,
  icon: Icon,
  description,
  iconClass,
}: IProps) => {
  return (
    <div className="group rounded-2xl border border-border bg-bg-primary p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
      <div className="flex items-start justify-between gap-4">
        <div className="min-w-0">
          <p className="text-sm font-medium text-text-secondary">{label}</p>

          <p className="mt-2 truncate text-2xl font-bold text-text-primary">
            {value}
          </p>
        </div>

        <div
          className={`flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ${iconClass}`}
        >
          <Icon className="h-5 w-5" />
        </div>
      </div>

      <p className="mt-4 text-xs text-text-tertiary">{description}</p>
    </div>
  );
};

export default StatCard;
