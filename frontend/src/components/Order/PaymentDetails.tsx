import { CheckCircle2, CreditCard } from "lucide-react";

import { formatCurrency } from "../../lib/utils";
import type { IOrder } from "../../interface/order";
import SectionCard from "../../pages/Admin/ProductDetails/SectionCard";
import SectionHeader from "../../pages/Admin/ProductDetails/SectionHeader";
import InfoItem from "../ui/InfoItem";

interface PaymentDetailsProps {
  order: IOrder;
}

const PaymentDetails = ({ order }: PaymentDetailsProps) => {
  const payment = order.payment;

  if (!payment) {
    return null;
  }

  return (
    <SectionCard>
      <SectionHeader
        title="Payment"
        icon={<CreditCard size={18} className="text-primary" />}
      />

      <div className="grid gap-4 p-4 sm:grid-cols-2 sm:p-5">
        {/* Status */}
        <InfoItem
          label="Status"
          value={
            <span className="inline-flex items-center gap-1.5 font-semibold capitalize text-text-primary">
              <CheckCircle2 size={15} className="text-success" />

              {payment.status}
            </span>
          }
        />

        {/* Provider */}
        <InfoItem
          label="Provider"
          value={<span className="uppercase">{payment.provider}</span>}
        />

        {/* Amount */}
        <InfoItem
          label="Amount"
          value={
            <span className="font-bold text-primary">
              {formatCurrency(Number(payment.amount), payment.currency)}
            </span>
          }
        />

        {/* Payment Method */}
        {payment.payment_method && (
          <InfoItem
            label="Method"
            value={<span className="capitalize">{payment.payment_method}</span>}
          />
        )}
      </div>
    </SectionCard>
  );
};

export default PaymentDetails;
