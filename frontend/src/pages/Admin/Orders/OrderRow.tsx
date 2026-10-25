import { Eye, RotateCcw } from "lucide-react";

import type { IOrder, OrderStatus } from "../../../interface/order";
import ActionButton from "../../../components/ui/ActionButton";
import { useNavigate } from "react-router-dom";
import { statusClass } from "../../../lib/utils";

export interface IProps {
  order: IOrder;
  onStatusChange: (order: IOrder, status: OrderStatus) => Promise<void>;
  onRefund: (val: IOrder) => void;
}

const formatStatus = (status: string) =>
  status.charAt(0).toUpperCase() + status.slice(1);

const OrderRow = ({ order, onStatusChange, onRefund }: IProps) => {
  const canRefund = order.payment && order.payment.status === "succeeded";
  const navigate = useNavigate();
  return (
    <tr className="transition hover:bg-bg-secondary/50">
      {/* Order */}
      <td className="px-5 py-4">
        <div>
          <p className="font-semibold text-text-primary">
            #{order.order_number}
          </p>

          <p className="mt-1 text-xs text-text-secondary">
            {new Date(order.created_at).toLocaleDateString()}
          </p>
        </div>
      </td>

      {/* Customer */}
      <td className="px-5 py-4">
        <div className="min-w-0">
          <p className="truncate font-medium text-text-primary">
            {order.user?.name ?? "Unknown customer"}
          </p>

          <p className="mt-1 truncate text-xs text-text-secondary">
            {order.user?.email ?? "No email"}
          </p>
        </div>
      </td>

      {/* Status */}
      <td className="px-5 py-4">
        <select
          value={order.status}
          onChange={(event) =>
            onStatusChange(order, event.target.value as OrderStatus)
          }
          className={`rounded-full border-0 px-3 py-1.5 text-xs font-semibold outline-none ${statusClass(
            order.status,
          )}`}
        >
          <option value="pending">Pending</option>
          <option value="processing">Processing</option>
          <option value="paid">Paid</option>
          <option value="shipped">Shipped</option>
          <option value="delivered">Delivered</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </td>

      {/* Payment */}
      <td className="px-5 py-4">
        <div>
          <p className="text-sm font-medium capitalize text-text-primary">
            {order.payment?.status ?? "N/A"}
          </p>

          <p className="mt-1 text-xs capitalize text-text-secondary">
            {order.payment?.provider ?? "N/A"}
          </p>
        </div>
      </td>

      {/* Total */}
      <td className="px-5 py-4">
        <span className="text-sm font-semibold text-text-primary">
          {Number(order.total).toFixed(2)} {order.currency}
        </span>
      </td>

      {/* Date */}
      <td className="px-5 py-4">
        <span className="text-sm text-text-secondary">
          {new Date(order.created_at).toLocaleDateString()}
        </span>
      </td>

      {/* Actions */}
      <td className="px-5 py-4">
        <div className="flex justify-end gap-2">
          <ActionButton
            label="View order"
            onClick={() =>
              navigate(`/admin/orders/${order.id}`, { replace: true })
            }
          >
            <Eye size={17} />
          </ActionButton>
          {canRefund && (
            <ActionButton
              label="Refund payment"
              onClick={() => onRefund(order)}
            >
              <RotateCcw size={17} />
            </ActionButton>
          )}
        </div>
      </td>
    </tr>
  );
};

export { statusClass, formatStatus };

export default OrderRow;
