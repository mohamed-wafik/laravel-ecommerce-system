import { useEffect } from "react";
import { Eye, Package } from "lucide-react";
import {
  Link,
  useNavigate,
  useParams,
  useSearchParams,
} from "react-router-dom";

import type { IPagination } from "../../../interface";
import Pagination from "../../../components/ui/Pagnation";
import PaginationSkeleton from "../../../components/Skeleton/PaginationSkeleton";
import type { IOrder } from "../../../interface/order";
import { adminUserAPI } from "../../../api/modules/Admin/user.api";
import { statusClass } from "../Orders/OrderRow";
import { formatCurrency, formatDate } from "../../../lib/utils";
import { Table } from "../../../components/ui/Table";

interface IOrdersResponse {
  items: IOrder[];
  pagination: IPagination;
}

const UserOrders = () => {
  const [searchParams, setSearchParams] = useSearchParams();
  const navigate = useNavigate();

  const { userId } = useParams<{ userId: string }>();

  const page = Number(searchParams.get("page")) || 1;
  const perPage = Number(searchParams.get("per_page")) || 10;

  const filters = {
    page,
    per_page: perPage,
  };

  useEffect(() => {
    if (!userId) {
      navigate("/admin/users", { replace: true });
    }
  }, [userId, navigate]);

  const { data, isLoading, isFetching } = adminUserAPI.getOrdersByUser(
    userId ?? "",
    filters,
  );

  const result: IOrdersResponse = data?.data ?? {
    items: [],
    pagination: {} as IPagination,
  };

  const orders = result.items ?? [];

  const handlePageChange = (newPage: number) => {
    setSearchParams((params) => {
      params.set("page", String(newPage));

      return params;
    });
  };

  const handlePerPageChange = (newPerPage: number) => {
    setSearchParams((params) => {
      params.set("per_page", String(newPerPage));
      params.set("page", "1");

      return params;
    });
  };

  return (
    <section className="space-y-6">
      {/* Header */}
      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <div className="flex items-center gap-2">
            <Package className="h-5 w-5 text-primary" />

            <h2 className="text-xl font-semibold text-text-primary">
              User Orders
            </h2>
          </div>

          <p className="mt-1 text-sm text-text-secondary">
            Orders placed by this user
          </p>
        </div>

        <div className="flex items-center gap-3">
          {isFetching && !isLoading && (
            <span className="text-xs text-text-secondary">Updating...</span>
          )}

          <select
            value={perPage}
            onChange={(event) =>
              handlePerPageChange(Number(event.target.value))
            }
            className="rounded-lg border border-border bg-bg-secondary px-3 py-2 text-sm text-text-primary outline-none focus:border-primary"
          >
            <option value={10}>10 / page</option>
            <option value={15}>15 / page</option>
            <option value={20}>20 / page</option>
            <option value={30}>30 / page</option>
            <option value={50}>50 / page</option>
          </select>
        </div>
      </div>

      {/* Desktop Table */}
      <Table
        width="auto"
        isLoading={isLoading}
        itemsHeader={[
          "Order",
          "Status",
          "SubTotal",
          "Shipping",
          "Total",
          "Date",
        ]}
        length={orders.length}
        emptyText="No orders found for this user."
      >
        {orders.map((order) => (
          <tr
            key={order.id}
            className="transition-colors hover:bg-bg-tertiary/50"
          >
            {/* Order */}
            <td className="px-5 py-4">
              <div>
                <p className="font-medium text-text-primary">
                  {order.order_number}
                </p>

                <p className="mt-1 text-xs text-text-secondary">
                  ID: {order.id}
                </p>
              </div>
            </td>

            {/* Status */}
            <td className="px-5 py-4">
              <span
                className={`inline-flex rounded-full px-3 py-1 text-xs font-medium capitalize ${statusClass(
                  order.status,
                )}`}
              >
                {order.status}
              </span>
            </td>

            {/* Subtotal */}
            <td className="px-5 py-4 text-sm text-text-secondary">
              {formatCurrency(Number(order.subtotal), order.currency)}
            </td>

            {/* Shipping */}
            <td className="px-5 py-4 text-sm text-text-secondary">
              {formatCurrency(Number(order.shipping_fee), order.currency)}
            </td>

            {/* Total */}
            <td className="px-5 py-4">
              <span className="font-semibold text-text-primary">
                {formatCurrency(Number(order.total), order.currency)}
              </span>
            </td>

            {/* Date */}
            <td className="px-5 py-4 text-sm text-text-secondary">
              {formatDate(order.created_at)}
            </td>

            {/* Action */}
            <td className="px-5 py-4">
              <div className="flex justify-end">
                <Link
                  to={`/admin/orders/${order.id}`}
                  className="inline-flex h-9 w-9 items-center justify-center rounded-lg text-text-secondary transition hover:bg-bg-tertiary hover:text-primary"
                  title="View order"
                  aria-label={`View order ${order.order_number}`}
                >
                  <Eye className="h-4 w-4" />
                </Link>
              </div>
            </td>
          </tr>
        ))}
      </Table>

      {/* Mobile Cards */}
      <div className="space-y-4 md:hidden">
        {isLoading ? (
          Array.from({ length: 4 }).map((_, index) => (
            <div
              key={index}
              className="animate-pulse rounded-xl border border-border bg-bg-secondary p-4"
            >
              <div className="h-5 w-32 rounded bg-bg-tertiary" />
              <div className="mt-4 h-4 w-24 rounded bg-bg-tertiary" />
              <div className="mt-4 h-8 rounded bg-bg-tertiary" />
            </div>
          ))
        ) : orders.length === 0 ? (
          <div className="rounded-xl border border-border bg-bg-secondary px-5 py-12 text-center text-sm text-text-secondary">
            No orders found for this user.
          </div>
        ) : (
          orders.map((order) => (
            <div
              key={order.id}
              className="rounded-xl border border-border bg-bg-secondary p-4"
            >
              {/* Card Header */}
              <div className="flex items-start justify-between gap-3">
                <div className="min-w-0">
                  <p className="truncate font-semibold text-text-primary">
                    {order.order_number}
                  </p>

                  <p className="mt-1 text-xs text-text-secondary">
                    {formatDate(order.created_at)}
                  </p>
                </div>

                <span
                  className={`shrink-0 inline-flex rounded-full px-3 py-1 text-xs font-medium capitalize ${statusClass(
                    order.status,
                  )}`}
                >
                  {order.status}
                </span>
              </div>

              {/* Order Summary */}
              <div className="mt-4 grid grid-cols-2 gap-4">
                <div>
                  <p className="text-xs text-text-secondary">Subtotal</p>

                  <p className="mt-1 text-sm font-medium text-text-primary">
                    {formatCurrency(Number(order.subtotal), order.currency)}
                  </p>
                </div>

                <div>
                  <p className="text-xs text-text-secondary">Shipping</p>

                  <p className="mt-1 text-sm font-medium text-text-primary">
                    {formatCurrency(Number(order.shipping_fee), order.currency)}
                  </p>
                </div>

                <div>
                  <p className="text-xs text-text-secondary">Tax</p>

                  <p className="mt-1 text-sm font-medium text-text-primary">
                    {formatCurrency(Number(order.tax), order.currency)}
                  </p>
                </div>

                <div>
                  <p className="text-xs text-text-secondary">Total</p>

                  <p className="mt-1 text-sm font-semibold text-text-primary">
                    {formatCurrency(Number(order.total), order.currency)}
                  </p>
                </div>
              </div>

              {/* View Order */}
              <Link
                to={`/admin/orders/${order.id}`}
                className="mt-4 flex w-full items-center justify-center gap-2 rounded-lg border border-border px-4 py-2.5 text-sm font-medium text-text-primary transition hover:border-primary hover:text-primary"
              >
                <Eye className="h-4 w-4" />
                View Order
              </Link>
            </div>
          ))
        )}
      </div>

      {/* Pagination */}
      {isLoading ? (
        <PaginationSkeleton />
      ) : (
        result.pagination?.last_page > 1 && (
          <Pagination
            pagination={result.pagination}
            setPage={handlePageChange}
          />
        )
      )}
    </section>
  );
};

export default UserOrders;
