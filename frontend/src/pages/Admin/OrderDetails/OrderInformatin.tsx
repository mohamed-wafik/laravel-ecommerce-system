import { Hash } from "lucide-react";

import type { IOrder } from "../../../interface/order";
import { formatDate } from "../../../lib/utils";
import InfoItem from "../../../components/ui/InfoItem";
import SectionCard from "../ProductDetails/SectionCard";
import SectionHeader from "../ProductDetails/SectionHeader";

interface OrderInformationProps {
  order: IOrder;
}

const OrderInformation = ({ order }: OrderInformationProps) => {
  return (
    <SectionCard>
      <SectionHeader
        title="Order Information"
        icon={<Hash size={18} className="text-primary" />}
      />

      <div className="grid gap-4 p-4 sm:grid-cols-2 sm:p-5">
        <InfoItem
          label="Order ID"
          value={
            <span className="break-all font-mono text-xs">{order.id}</span>
          }
        />

        <InfoItem
          label="Idempotency Key"
          value={
            <span className="break-all font-mono text-xs">
              {order.idempotency_key}
            </span>
          }
        />

        <InfoItem label="Created" value={formatDate(order.created_at)} />

        <InfoItem label="Updated" value={formatDate(order.updated_at)} />

        {order.payment?.checkout_session_id && (
          <InfoItem
            label="Checkout Session"
            value={
              <span className="break-all font-mono text-xs">
                {order.payment.checkout_session_id}
              </span>
            }
          />
        )}
      </div>
    </SectionCard>
  );
};

export default OrderInformation;
