import { X } from "lucide-react";

interface IFilterBadgeProps {
  label: string;
  onRemove: () => void;
}

const FilterBadge = ({ label, onRemove }: IFilterBadgeProps) => {
  return (
    <span className="inline-flex items-center gap-1.5 rounded-lg border border-primary/15 bg-primary-50 px-2.5 py-1.5 text-xs font-medium text-primary">
      {label}

      <button
        type="button"
        onClick={onRemove}
        aria-label={`Remove ${label} filter`}
        className="rounded-full p-0.5 transition hover:bg-primary/10"
      >
        <X size={13} />
      </button>
    </span>
  );
};

export default FilterBadge;
