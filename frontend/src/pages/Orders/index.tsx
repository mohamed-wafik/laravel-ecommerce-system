import { CalendarDays, ChevronRight, CreditCard, Package } from "lucide-react";
import { useState } from "react";
import { Link } from "react-router-dom";

import { orderAPI } from "../../api/modules/Customer/order.api";
import Breadcrumb from "../../components/ui/Breadcrumb";
import Pagination from "../../components/ui/Pagnation";
import type { IOrder, IOrderItem } from "../../interface/order";
import { formatCurrency, formatDate } from "../../lib/utils";
import OrdersSkeleton from "./OrdersSkeleton";
import EmptyOrders from "./EmptyOrders";
import StatusBadge from "./StatusBadge";
import OrderCard from "./OrderCard";
import { Table } from "../../components/ui/Table";

export const getItemsLabel = (items: IOrderItem[]) => {
  const count = items.reduce((total, item) => total + item.quantity, 0);

  return `${count} ${count === 1 ? "item" : "items"}`;
};

const OrdersPage = () => {
  const [page, setPage] = useState(1);

  const { data, isLoading } = orderAPI.getAll(page);

  const orders: IOrder[] = data?.data?.items ?? [];
  const pagination = data?.data?.pagination;

  return (
    <section className="min-h-screen bg-gray-50 py-8 sm:py-10">
      <div className="container mx-auto max-w-6xl px-4">
        <Breadcrumb
          items={[{ label: "Home", href: "/" }, { label: "Orders" }]}
        />

        {/* Header */}
        <div className="mb-6 flex flex-col gap-4 sm:mb-8 sm:flex-row sm:items-center sm:justify-between">
          <div className="flex items-center gap-3">
            <div className="h-7 w-1 rounded-full bg-primary" />

            <div>
              <h1 className="text-xl font-extrabold text-dark sm:text-2xl">
                My Orders
              </h1>

              <p className="mt-1 text-sm text-gray-500">
                Track and manage your recent orders
              </p>
            </div>
          </div>

          {pagination && (
            <div className="hidden rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-600 sm:block">
              <span className="font-semibold text-dark">
                {pagination.total}
              </span>{" "}
              {pagination.total === 1 ? "order" : "orders"}
            </div>
          )}
        </div>

        {/* Loading */}
        {isLoading ? (
          <OrdersSkeleton />
        ) : orders.length === 0 ? (
          <EmptyOrders />
        ) : (
          <>
            {/* Desktop */}
            <Table
              length={orders.length}
              width="225"
              emptyText="Not Found Orders."
              itemsHeader={[
                "Order",
                "Date",
                "Items",
                "Payment",
                "Status",
                "Totabl",
              ]}
            >
              {orders.map((order) => (
                <tr key={order.id} className="transition hover:bg-gray-50">
                  {/* Order */}
                  <td className="px-6 py-5">
                    <div className="flex items-center gap-3">
                      <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary">
                        <Package size={19} />
                      </div>

                      <div>
                        <p className="font-bold text-dark">
                          {order.order_number}
                        </p>

                        <p className="mt-0.5 text-xs text-gray-500">
                          #{order.id.slice(0, 8)}
                        </p>
                      </div>
                    </div>
                  </td>

                  {/* Date */}
                  <td className="px-6 py-5">
                    <div className="flex items-center gap-2 text-sm text-gray-600">
                      <CalendarDays size={16} />
                      {formatDate(order.created_at)}
                    </div>
                  </td>

                  {/* Items */}
                  <td className="px-6 py-5">
                    <span className="text-sm font-medium text-gray-700">
                      {getItemsLabel(order.items)}
                    </span>
                  </td>

                  {/* Payment */}
                  <td className="px-6 py-5">
                    <div className="flex items-center gap-2">
                      <CreditCard size={16} className="text-gray-400" />

                      <span className="text-sm capitalize text-gray-600">
                        {order.payment?.status ?? "N/A"}
                      </span>
                    </div>
                  </td>

                  {/* Status */}
                  <td className="px-6 py-5">
                    <StatusBadge status={order.status} />
                  </td>

                  {/* Total */}
                  <td className="px-6 py-5">
                    <p className="font-bold text-dark">
                      {formatCurrency(Number(order.total), order.currency)}
                    </p>
                  </td>

                  {/* Action */}
                  <td className="px-6 py-5 flex justify-end">
                    <Link
                      to={`/orders/${order.id}`}
                      className="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:border-primary hover:bg-primary-50 hover:text-primary"
                      aria-label={`View ${order.order_number}`}
                    >
                      <ChevronRight size={18} />
                    </Link>
                  </td>
                </tr>
              ))}
            </Table>

            {/* Mobile / Tablet */}
            <div className="space-y-4 md:hidden">
              {orders.map((order) => (
                <OrderCard key={order.id} order={order} />
              ))}
            </div>

            {/* Pagination */}
            {pagination && pagination.last_page !== 1 && (
              <div className="mt-6">
                <Pagination
                  setPage={(val) => setPage(val)}
                  pagination={pagination}
                />
              </div>
            )}
          </>
        )}
      </div>
    </section>
  );
};

export default OrdersPage;
