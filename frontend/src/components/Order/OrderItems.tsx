import { Package } from "lucide-react";

import type { IOrder } from "../../interface/order";
import { formatCurrency } from "../../lib/utils";
import SectionCard from "../../pages/Admin/ProductDetails/SectionCard";
import SectionHeader from "../../pages/Admin/ProductDetails/SectionHeader";

interface OrderItemsProps {
  order: IOrder;
}

const OrderItems = ({ order }: OrderItemsProps) => {
  const itemCount = order.items.length;

  return (
    <SectionCard>
      <SectionHeader
        title="Order Items"
        icon={<Package size={18} className="text-primary" />}
        subTotal={`${itemCount} ${itemCount === 1 ? "product" : "products"}`}
      />

      <div className="divide-y divide-border">
        {order.items.map((item) => (
          <div
            key={item.id}
            className="flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5"
          >
            {/* Product */}
            <div className="flex min-w-0 items-center gap-4">
              <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-bg-secondary text-text-tertiary">
                <Package size={20} />
              </div>

              <div className="min-w-0">
                <h3 className="truncate text-sm font-semibold text-text-primary">
                  {item.product_name_snapshot}
                </h3>

                <p className="mt-1 text-sm text-text-secondary">
                  Qty: {item.quantity}
                </p>
              </div>
            </div>

            {/* Price */}
            <div className="flex items-center justify-between gap-4 sm:block sm:text-right">
              <p className="text-sm text-text-secondary">
                {formatCurrency(Number(item.price_snapshot), order.currency)} ×{" "}
                {item.quantity}
              </p>

              <p className="mt-0.5 text-sm font-bold text-text-primary sm:mt-1">
                {formatCurrency(Number(item.subtotal), order.currency)}
              </p>
            </div>
          </div>
        ))}
      </div>
    </SectionCard>
  );
};

export default OrderItems;
