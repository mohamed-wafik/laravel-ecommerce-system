import { ArrowRight, CalendarDays, CreditCard, Package } from "lucide-react";
import { Link } from "react-router-dom";
import { formatCurrency, formatDate } from "../../lib/utils";
import StatusBadge from "./StatusBadge";
import type { IOrder } from "../../interface/order";
import { getItemsLabel } from ".";

const OrderCard = ({ order }: { order: IOrder }) => {
  return (
    <article className="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
      {/* Card Header */}
      <div className="flex items-start justify-between gap-3 border-b border-gray-100 p-4">
        <div className="flex min-w-0 items-center gap-3">
          <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary">
            <Package size={19} />
          </div>

          <div className="min-w-0">
            <p className="truncate text-sm font-bold text-dark">
              {order.order_number}
            </p>

            <p className="mt-1 flex items-center gap-1.5 text-xs text-gray-500">
              <CalendarDays size={13} />
              {formatDate(order.created_at)}
            </p>
          </div>
        </div>

        <StatusBadge status={order.status} />
      </div>

      {/* Items */}
      <div className="p-4">
        <div className="mb-4 flex items-center justify-between rounded-xl bg-gray-50 p-3">
          <div className="flex items-center gap-2 text-sm text-gray-600">
            <Package size={16} />
            <span>{getItemsLabel(order.items)}</span>
          </div>

          <span className="font-bold text-dark">
            {formatCurrency(Number(order.total), order.currency)}
          </span>
        </div>

        {/* Products */}
        <div className="space-y-3">
          {order.items.slice(0, 2).map((item) => (
            <div
              key={item.id}
              className="flex items-center justify-between gap-3"
            >
              <div className="min-w-0">
                <p className="truncate text-sm font-medium text-dark">
                  {item.product_name_snapshot}
                </p>

                <p className="mt-1 text-xs text-gray-500">
                  Qty: {item.quantity} ×{" "}
                  {formatCurrency(Number(item.price_snapshot), order.currency)}
                </p>
              </div>

              <span className="shrink-0 text-sm font-semibold text-gray-700">
                {formatCurrency(Number(item.subtotal), order.currency)}
              </span>
            </div>
          ))}

          {order.items.length > 2 && (
            <p className="text-xs font-medium text-primary">
              +{order.items.length - 2} more items
            </p>
          )}
        </div>

        {/* Summary */}
        <div className="mt-4 space-y-2 border-t border-gray-100 pt-4 text-sm">
          <div className="flex justify-between text-gray-500">
            <span>Subtotal</span>
            <span>
              {formatCurrency(Number(order.subtotal), order.currency)}
            </span>
          </div>

          <div className="flex justify-between text-gray-500">
            <span>Shipping</span>
            <span>
              {formatCurrency(Number(order.shipping_fee), order.currency)}
            </span>
          </div>

          <div className="flex justify-between text-gray-500">
            <span>Tax</span>
            <span>{formatCurrency(Number(order.tax), order.currency)}</span>
          </div>

          <div className="flex justify-between border-t border-gray-100 pt-2 font-bold text-dark">
            <span>Total</span>
            <span>{formatCurrency(Number(order.total), order.currency)}</span>
          </div>
        </div>

        {/* Payment */}
        <div className="mt-4 flex items-center justify-between rounded-lg border border-gray-100 px-3 py-2.5">
          <div className="flex items-center gap-2 text-sm text-gray-500">
            <CreditCard size={15} />
            <span>Payment</span>
          </div>

          <span className="text-sm font-semibold capitalize text-gray-700">
            {order.payment?.status ?? "N/A"}
          </span>
        </div>

        {/* Action */}
        <Link
          to={`/orders/${order.id}`}
          className="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3 text-sm font-bold text-white transition hover:bg-primary-dark"
        >
          View Order
          <ArrowRight size={16} />
        </Link>
      </div>
    </article>
  );
};

export default OrderCard;
