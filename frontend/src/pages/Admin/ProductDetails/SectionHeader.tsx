import type { ReactNode } from "react";

interface SectionHeaderProps {
  title: string;
  icon?: ReactNode;
  subTotal?: string;
}

const SectionHeader = ({ title, icon, subTotal }: SectionHeaderProps) => {
  return (
    <div className="flex items-center justify-between gap-4 border-b border-border px-4 py-4 sm:px-5">
      <div className="flex min-w-0 items-center gap-3">
        {icon && (
          <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary">
            {icon}
          </div>
        )}

        <h2 className="truncate text-sm font-semibold text-text-primary">
          {title}
        </h2>
      </div>

      {subTotal && (
        <span className="shrink-0 text-xs font-medium text-text-secondary">
          {subTotal}
        </span>
      )}
    </div>
  );
};

export default SectionHeader;
