import { Eye, Package, RotateCcw, User } from "lucide-react";

import type { IProps } from "./OrderRow";
import { statusClass } from "./OrderRow";
import ActionButton from "../../../components/ui/ActionButton";
import { useNavigate } from "react-router-dom";

const OrderCardMobile = ({ order, onStatusChange, onRefund }: IProps) => {
  const canRefund = order.payment && order.payment.status === "succeeded";
  const navigate = useNavigate();
  return (
    <div className="rounded-2xl border border-border bg-bg-primary p-4 shadow-sm">
      {/* Header */}
      <div className="flex items-start justify-between gap-3">
        <div className="min-w-0">
          <div className="flex items-center gap-2">
            <Package size={17} className="shrink-0 text-primary" />

            <h3 className="truncate font-semibold text-text-primary">
              #{order.order_number}
            </h3>
          </div>

          <p className="mt-1 text-xs text-text-secondary">
            {new Date(order.created_at).toLocaleDateString()}
          </p>
        </div>

        <select
          value={order.status}
          onChange={(event) =>
            onStatusChange(
              order,
              event.target.value as IProps["order"]["status"],
            )
          }
          className={`shrink-0 rounded-full border-0 px-2.5 py-1.5 text-xs font-semibold outline-none ${statusClass(
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
      </div>

      {/* Customer */}
      <div className="mt-4 rounded-xl bg-bg-secondary p-3">
        <div className="flex items-start gap-3">
          <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
            <User size={17} />
          </div>

          <div className="min-w-0">
            <p className="text-xs text-text-secondary">Customer</p>

            <p className="mt-1 truncate text-sm font-semibold text-text-primary">
              {order.user?.name ?? "Unknown customer"}
            </p>

            <p className="mt-0.5 truncate text-xs text-text-secondary">
              {order.user?.email ?? "No email"}
            </p>

            {order.user?.phone && (
              <p className="mt-0.5 text-xs text-text-secondary">
                {order.user.phone}
              </p>
            )}
          </div>
        </div>
      </div>

      {/* Order Summary */}
      <div className="mt-4 grid grid-cols-2 gap-3">
        <div>
          <p className="text-xs text-text-secondary">Items</p>

          <p className="mt-1 text-sm font-semibold text-text-primary">
            {order.items?.reduce((total, item) => total + item.quantity, 0) ??
              0}
          </p>
        </div>

        <div className="text-right">
          <p className="text-xs text-text-secondary">Total</p>

          <p className="mt-1 text-sm font-bold text-primary">
            {Number(order.total).toFixed(2)} {order.currency}
          </p>
        </div>
      </div>

      {/* Payment */}
      <div className="mt-4 grid grid-cols-2 gap-3 border-t border-border pt-4">
        <div>
          <p className="text-xs text-text-secondary">Payment</p>

          <p className="mt-1 text-sm font-medium capitalize text-text-primary">
            {order.payment?.status ?? "N/A"}
          </p>

          {order.payment?.provider && (
            <p className="mt-0.5 text-xs capitalize text-text-secondary">
              {order.payment.provider}
            </p>
          )}
        </div>

        <div className="text-right">
          <p className="text-xs text-text-secondary">Products</p>

          <p className="mt-1 text-sm font-medium text-text-primary">
            {order.items?.length ?? 0}
          </p>
        </div>
      </div>

      {/* Actions */}
      <div className="mt-4 flex justify-end gap-2 border-t border-border pt-3">
        <ActionButton
          label="View order"
          onClick={() =>
            navigate(`/admin/orders/${order.id}`, { replace: true })
          }
        >
          <Eye size={17} />
        </ActionButton>
        {canRefund && (
          <ActionButton label="Refund payment" onClick={() => onRefund(order)}>
            <RotateCcw size={17} />
          </ActionButton>
        )}
      </div>
    </div>
  );
};

export default OrderCardMobile;
