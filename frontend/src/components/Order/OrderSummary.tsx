import { Receipt } from "lucide-react";

import type { IOrder } from "../../interface/order";
import { formatCurrency } from "../../lib/utils";
import SectionCard from "../../pages/Admin/ProductDetails/SectionCard";
import SectionHeader from "../../pages/Admin/ProductDetails/SectionHeader";
import InfoItem from "../ui/InfoItem";

interface OrderSummaryProps {
  order: IOrder;
}

const OrderSummary = ({ order }: OrderSummaryProps) => {
  return (
    <SectionCard>
      <SectionHeader
        title="Order Summary"
        icon={<Receipt size={18} className="text-primary" />}
      />

      <div className="space-y-4 p-4 sm:p-5">
        <div className="grid gap-4 sm:grid-cols-2">
          <InfoItem
            label="Subtotal"
            value={formatCurrency(Number(order.subtotal), order.currency)}
          />

          <InfoItem
            label="Tax"
            value={formatCurrency(Number(order.tax), order.currency)}
          />

          <InfoItem
            label="Shipping"
            value={formatCurrency(Number(order.shipping_fee), order.currency)}
          />
        </div>

        <div className="border-t border-dashed border-border" />

        <div className="flex items-center justify-between gap-4">
          <span className="text-sm font-semibold text-text-primary">Total</span>

          <span className="text-lg font-extrabold text-primary">
            {formatCurrency(Number(order.total), order.currency)}
          </span>
        </div>
      </div>
    </SectionCard>
  );
};

export default OrderSummary;
