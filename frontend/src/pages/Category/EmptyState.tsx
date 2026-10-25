import { PackageX } from "lucide-react";

const EmptyState = () => {
  return (
    <div className="flex flex-col items-center gap-3 rounded-2xl border border-dashed border-border-dark bg-bg-secondary px-6 py-16 text-center">
      <PackageX className="h-10 w-10 text-text-tertiary" strokeWidth={1.5} />
      <p className="font-medium text-text-primary">No categories yet</p>
      <p className="max-w-xs text-sm text-text-tertiary">
        Categories will show up here as soon as they're added.
      </p>
    </div>
  );
};

export default EmptyState;
