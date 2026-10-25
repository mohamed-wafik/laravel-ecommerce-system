import { CreditCard, Eye, User } from "lucide-react";

import ActionButton from "../../../components/ui/ActionButton";

import { formatPaymentStatus } from "./PaymentRow";
import type { IPayment } from "../../../schema/Admin/admin.payment.schema";
import { statusClass } from "../Orders/OrderRow";

interface IProps {
  payment: IPayment;
  onView: (payment: IPayment) => void;
}

const PaymentCardMobile = ({ payment, onView }: IProps) => {
  return (
    <div className="rounded-2xl border border-border bg-bg-primary p-4 shadow-sm">
      <div className="flex items-start justify-between gap-3">
        <div className="min-w-0">
          <div className="flex items-center gap-2">
            <CreditCard size={17} className="shrink-0 text-primary" />

            <h3 className="truncate font-semibold text-text-primary">
              #{payment.order?.order_number ?? "N/A"}
            </h3>
          </div>

          <p className="mt-1 truncate text-xs text-text-tertiary">
            {payment.id}
          </p>
        </div>

        <span
          className={`shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold ${statusClass(
            payment.status,
          )}`}
        >
          {formatPaymentStatus(payment.status)}
        </span>
      </div>

      <div className="mt-4 rounded-xl bg-bg-secondary p-3">
        <div className="flex items-start gap-3">
          <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
            <User size={17} />
          </div>

          <div className="min-w-0 flex-1">
            <p className="text-xs text-text-secondary">Customer</p>

            <p className="mt-1 truncate text-sm font-semibold text-text-primary">
              {payment.user?.name ?? "Unknown customer"}
            </p>

            <p className="mt-0.5 truncate text-xs text-text-secondary">
              {payment.user?.email ?? "No email"}
            </p>

            {payment.user?.phone && (
              <p className="mt-0.5 text-xs text-text-secondary">
                {payment.user.phone}
              </p>
            )}
          </div>
        </div>
      </div>

      <div className="mt-4 grid grid-cols-2 gap-3">
        <div>
          <p className="text-xs text-text-secondary">Provider</p>

          <p className="mt-1 text-sm font-semibold capitalize text-text-primary">
            {payment.provider}
          </p>
        </div>

        <div className="text-right">
          <p className="text-xs text-text-secondary">Amount</p>

          <p className="mt-1 text-sm font-bold text-primary">
            {Number(payment.amount).toFixed(2)} {payment.currency}
          </p>
        </div>
      </div>

      <div className="mt-4 grid grid-cols-2 gap-3 border-t border-border pt-4">
        <div>
          <p className="text-xs text-text-secondary">Created</p>

          <p className="mt-1 text-sm text-text-primary">
            {new Date(payment.created_at).toLocaleDateString()}
          </p>
        </div>

        <div className="text-right">
          <p className="text-xs text-text-secondary">Payment method</p>

          <p className="mt-1 text-sm font-medium capitalize text-text-primary">
            {payment.payment_method ?? "Stripe"}
          </p>
        </div>
      </div>

      {payment.paid_at && (
        <div className="mt-4 border-t border-border pt-4">
          <p className="text-xs text-text-secondary">Paid at</p>

          <p className="mt-1 text-sm text-text-primary">
            {new Date(payment.paid_at).toLocaleString()}
          </p>
        </div>
      )}

      <div className="mt-4 flex justify-end gap-2 border-t border-border pt-3">
        <ActionButton label="View payment" onClick={() => onView(payment)}>
          <Eye size={17} />
        </ActionButton>
      </div>
    </div>
  );
};

export default PaymentCardMobile;
