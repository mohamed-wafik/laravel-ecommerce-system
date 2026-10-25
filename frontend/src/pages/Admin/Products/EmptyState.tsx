import { Package } from "lucide-react";

const EmptyState = () => {
  return (
    <div className="flex min-h-60 flex-col items-center justify-center p-6 text-center">
      <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-bg-tertiary">
        <Package className="h-6 w-6 text-text-tertiary" />
      </div>

      <h3 className="mt-4 font-semibold text-text-primary">
        No products found
      </h3>

      <p className="mt-1 max-w-sm text-sm text-text-tertiary">
        Try changing your filters or add a new product to your store.
      </p>
    </div>
  );
};
export default EmptyState;
