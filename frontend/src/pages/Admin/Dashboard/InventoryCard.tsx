import { Boxes, Package } from "lucide-react";

const InventoryCard = ({
  inventory,
}: {
  inventory: {
    total: number;
    active: number;
    low_stock: number;
    out_of_stock: number;
  };
}) => {
  return (
    <div className="rounded-2xl border border-border bg-bg-primary p-5 shadow-sm">
      <div className="mb-5 flex items-center justify-between">
        <div>
          <h2 className="font-semibold text-text-primary">
            Inventory Overview
          </h2>

          <p className="mt-1 text-xs text-text-tertiary">
            Current product stock status
          </p>
        </div>

        <Boxes className="h-5 w-5 text-secondary" />
      </div>

      <div className="grid grid-cols-2 gap-4">
        <InventoryItem
          label="Total Products"
          value={inventory.total}
          iconClass="bg-primary-50 text-primary"
        />

        <InventoryItem
          label="Active Products"
          value={inventory.active}
          iconClass="bg-success-50 text-success"
        />

        <InventoryItem
          label="Low Stock"
          value={inventory.low_stock}
          iconClass="bg-warning-50 text-warning"
        />

        <InventoryItem
          label="Out of Stock"
          value={inventory.out_of_stock}
          iconClass="bg-error-50 text-error"
        />
      </div>
    </div>
  );
};

export const InventoryItem = ({
  label,
  value,
  iconClass,
}: {
  label: string;
  value: number;
  iconClass: string;
}) => {
  return (
    <div className="flex items-center gap-3 rounded-xl border border-border-light p-3">
      <div
        className={`flex h-9 w-9 shrink-0 items-center justify-center rounded-lg ${iconClass}`}
      >
        <Package className="h-4 w-4" />
      </div>

      <div className="min-w-0">
        <p className="truncate text-xs text-text-tertiary">{label}</p>

        <p className="mt-1 text-lg font-bold text-text-primary">{value}</p>
      </div>
    </div>
  );
};

export default InventoryCard;
