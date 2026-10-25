import { CreditCard, Package, Wallet } from "lucide-react";

import Model from "../../../components/ui/Model";
import InfoItem from "../../../components/ui/InfoItem";
import { formatPaymentStatus } from "./PaymentRow";
import type { IPayment } from "../../../schema/Admin/admin.payment.schema";
import { statusClass } from "../Orders/OrderRow";
import SectionCard from "../ProductDetails/SectionCard";
import SectionHeader from "../ProductDetails/SectionHeader";
import CustomerInformation from "../../../components/Order/CustomerInformation";

interface IProps {
  payment: IPayment | null;
  isOpen: boolean;
  onClose: () => void;
}

const PaymentDetails = ({ payment, isOpen, onClose }: IProps) => {
  if (!isOpen || !payment) {
    return null;
  }

  const orderTotal = payment.order
    ? Number(payment.order.total)
    : Number(payment.amount);

  return (
    <Model
      title={`Payment #${payment.id.slice(-8)}`}
      subTitle="View complete payment information"
      isDisabled={false}
      onClose={onClose}
    >
      <div className="max-h-[75vh] space-y-6 overflow-y-auto p-4 sm:p-6">
        {/* Payment Overview */}
        <SectionCard>
          <div className="p-4 sm:p-5">
            <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
              <div className="flex min-w-0 items-center gap-3">
                <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary">
                  <CreditCard size={20} />
                </div>

                <div className="min-w-0">
                  <p className="text-xs text-text-secondary">Payment ID</p>

                  <p className="mt-1 break-all text-sm font-semibold text-text-primary">
                    {payment.id}
                  </p>
                </div>
              </div>

              <span
                className={`self-start rounded-full px-3 py-1.5 text-xs font-semibold sm:self-auto ${statusClass(
                  payment.status,
                )}`}
              >
                {formatPaymentStatus(payment.status)}
              </span>
            </div>
          </div>
        </SectionCard>

        {/* Customer */}
        {payment.user && <CustomerInformation user={payment.user} />}

        <SectionCard>
          <SectionHeader
            title="Order"
            icon={<Package size={18} className="text-primary" />}
          />

          <div className="grid gap-4 p-4 sm:grid-cols-2 sm:p-5">
            <InfoItem
              label="Order number"
              value={`#${payment.order?.order_number ?? "N/A"}`}
            />

            <InfoItem
              label="Order status"
              value={payment.order?.status ?? "N/A"}
            />

            <InfoItem label="Order ID" value={payment.order_id} />

            <InfoItem
              label="Order total"
              value={
                <span className="font-bold text-primary">
                  {orderTotal.toFixed(2)} {payment.currency}
                </span>
              }
            />
          </div>
        </SectionCard>

        {/* Payment Information */}
        <SectionCard>
          <SectionHeader
            title="Payment Information"
            icon={<Wallet size={18} className="text-primary" />}
          />

          <div className="grid gap-4 p-4 sm:grid-cols-2 sm:p-5">
            <InfoItem label="Provider" value={payment.provider} />

            <InfoItem
              label="Payment method"
              value={payment.payment_method ?? "Not specified"}
            />

            <InfoItem
              label="Amount"
              value={
                <span className="text-lg font-bold text-primary">
                  {Number(payment.amount).toFixed(2)} {payment.currency}
                </span>
              }
            />

            <InfoItem
              label="Paid at"
              value={
                payment.paid_at
                  ? new Date(payment.paid_at).toLocaleString()
                  : "Not paid"
              }
            />

            <InfoItem
              label="Payment intent ID"
              value={payment.payment_intent_id ?? "N/A"}
            />

            <InfoItem
              label="Checkout session"
              value={payment.checkout_session_id ?? "N/A"}
            />
          </div>
        </SectionCard>

        {/* Technical Information */}
        <SectionCard>
          <SectionHeader
            title="Technical Information"
            icon={<CreditCard size={18} className="text-primary" />}
          />

          <div className="grid gap-4 p-4 sm:grid-cols-2 sm:p-5">
            <InfoItem
              label="Idempotency key"
              value={
                <span className="break-all font-mono text-xs">
                  {payment.idempotency_key}
                </span>
              }
            />

            <InfoItem
              label="Created at"
              value={new Date(payment.created_at).toLocaleString()}
            />
          </div>
        </SectionCard>
      </div>
    </Model>
  );
};

export default PaymentDetails;
