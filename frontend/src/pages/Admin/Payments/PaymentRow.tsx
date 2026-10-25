import { Eye } from "lucide-react";

import ActionButton from "../../../components/ui/ActionButton";
import type { IPayment } from "../../../schema/Admin/admin.payment.schema";
import { statusClass } from "../Orders/OrderRow";

interface IProps {
  payment: IPayment;
  onView: (payment: IPayment) => void;
}

export const formatPaymentStatus = (status: string) =>
  status.charAt(0).toUpperCase() + status.slice(1);

const PaymentRow = ({ payment, onView }: IProps) => {
  return (
    <tr className="transition hover:bg-bg-secondary/50">
      <td className="px-5 py-4">
        <div className="min-w-[220px]">
          <p className="font-semibold text-text-primary">
            #{payment.order?.order_number ?? "N/A"}
          </p>

          <p className="mt-1 max-w-[220px] truncate text-xs text-text-tertiary">
            {payment.id}
          </p>
        </div>
      </td>

      <td className="px-5 py-4">
        <div className="min-w-[180px]">
          <p className="font-medium text-text-primary">
            {payment.user?.name ?? "Unknown customer"}
          </p>

          <p className="mt-1 truncate text-xs text-text-secondary">
            {payment.user?.email ?? "No email"}
          </p>
        </div>
      </td>

      <td className="px-5 py-4">
        <span
          className={`inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ${statusClass(
            payment.status,
          )}`}
        >
          {formatPaymentStatus(payment.status)}
        </span>
      </td>

      <td className="px-5 py-4">
        <div>
          <p className="font-medium capitalize text-text-primary">
            {payment.provider}
          </p>

          <p className="mt-1 text-xs text-text-secondary">
            {payment.payment_method ?? "Card / Stripe"}
          </p>
        </div>
      </td>

      <td className="px-5 py-4">
        <p className="font-bold text-text-primary">
          {Number(payment.amount).toFixed(2)} {payment.currency}
        </p>
      </td>

      <td className="px-5 py-4">
        <div>
          <p className="text-sm text-text-primary">
            {new Date(payment.created_at).toLocaleDateString()}
          </p>

          {payment.paid_at && (
            <p className="mt-1 text-xs text-text-secondary">
              Paid {new Date(payment.paid_at).toLocaleDateString()}
            </p>
          )}
        </div>
      </td>

      <td className="px-5 py-4">
        <div className="flex justify-end gap-2">
          <ActionButton label="View payment" onClick={() => onView(payment)}>
            <Eye size={17} />
          </ActionButton>
        </div>
      </td>
    </tr>
  );
};

export default PaymentRow;
