import { useState } from "react";
import toast from "react-hot-toast";

import type { IPagination } from "../../../interface";

import Pagination from "../../../components/ui/Pagnation";

import OrderRow from "./OrderRow";
import OrderCardMobile from "./OrderCardMobile";
import OrderToolbar from "./Toolbar";

import type {
  IOrder,
  IOrderResponse,
  OrderStatus,
} from "../../../interface/order";

import PaginationSkeleton from "../../../components/Skeleton/PaginationSkeleton";
import StatsSectioin from "./StatsSectioin";

import { adminOrderAPI } from "../../../api/modules/Admin/order.api";
import { Table } from "../../../components/ui/Table";
import RefundPayment from "../Payments/RefundPayment";
import type { OrderFilterField } from "../../../hook/useOrderFilters";
import useOrderFilters from "../../../hook/useOrderFilters";

/* -------------------------------------------------------------------------- */
/* Component                                                                  */
/* -------------------------------------------------------------------------- */

const AdminOrders = () => {
  /* ------------------------------------------------------------------------ */
  /* Filters                                                                  */
  /* ------------------------------------------------------------------------ */

  const { filters, setFilter, setPage, setPerPage, clearFilters } =
    useOrderFilters();

  /* ------------------------------------------------------------------------ */
  /* Selected order / refund                                                  */
  /* ------------------------------------------------------------------------ */

  const [selectedOrder, setSelectedOrder] = useState<IOrder | null>(null);

  const [refundPayment, setRefundPayment] = useState<IOrder | null>(null);

  /* ------------------------------------------------------------------------ */
  /* Fetch Orders                                                             */
  /* ------------------------------------------------------------------------ */

  const { data, isLoading, isFetching, refetch } =
    adminOrderAPI.getAll(filters);

  /* ------------------------------------------------------------------------ */
  /* API Response                                                             */
  /* ------------------------------------------------------------------------ */

  const result: IOrderResponse = data?.data ?? {
    items: [],
    pagination: {
      current_page: filters.page,
      per_page: filters.per_page,
      total: 0,
      last_page: 1,
      from: 0,
      to: 0,
    } satisfies IPagination,
  };

  /* ------------------------------------------------------------------------ */
  /* Update Order Status                                                      */
  /* ------------------------------------------------------------------------ */

  const handleStatusChange = async (order: IOrder, status: OrderStatus) => {
    if (order.status === status) {
      return;
    }

    try {
      await adminOrderAPI.updateStatus(order.id, { status });

      /*
       * Update selected order immediately.
       */
      if (selectedOrder?.id === order.id) {
        setSelectedOrder((current) =>
          current
            ? {
                ...current,
                status,
                updated_at: new Date().toISOString(),
              }
            : null,
        );
      }

      /*
       * Refresh order list.
       */
      await refetch();

      toast.success("Order status updated successfully");
    } catch (error) {
      console.error("Failed to update order status:", error);

      toast.error("Failed to update order status");
    }
  };

  /* ------------------------------------------------------------------------ */
  /* Refund                                                                    */
  /* ------------------------------------------------------------------------ */

  const openRefundModal = (order: IOrder) => {
    if (order.payment && order.payment.status !== "succeeded") {
      return;
    }

    setRefundPayment(order);
  };

  const closeRefundModal = () => {
    setRefundPayment(null);
  };

  const handleRefundSuccess = async () => {
    if (selectedOrder?.id === refundPayment?.id) {
      setSelectedOrder(null);
    }

    setRefundPayment(null);

    await refetch();
  };

  /* ------------------------------------------------------------------------ */
  /* Render                                                                    */
  /* ------------------------------------------------------------------------ */

  return (
    <section className="space-y-6">
      {/* Header */}

      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-bold text-text-primary">Orders</h1>

          <p className="mt-1 text-sm text-text-secondary">
            Manage customer orders, payments, and order status.
          </p>
        </div>
      </div>

      {/* Stats */}

      <StatsSectioin />

      {/* Toolbar */}

      <OrderToolbar
        filters={filters}
        onFilterChange={(field: OrderFilterField, value: string) =>
          setFilter(field, value)
        }
        onPerPageChange={setPerPage}
        onClearFilters={clearFilters}
      />

      {/* Fetching Indicator */}

      {isFetching && !isLoading && (
        <div className="text-sm text-text-secondary">Updating orders...</div>
      )}

      {/* Desktop Table */}

      <Table
        isLoading={isLoading}
        length={result.items.length}
        emptyText="No orders found."
        width="950"
        itemsHeader={[
          "Order",
          "Customer",
          "Status",
          "Payment",
          "Total",
          "Date",
        ]}
      >
        {result.items.map((order) => (
          <OrderRow
            key={order.id}
            order={order}
            onRefund={openRefundModal}
            onStatusChange={handleStatusChange}
          />
        ))}
      </Table>

      {/* Mobile Cards */}

      <div className="space-y-3 md:hidden">
        {isLoading ? (
          Array.from({ length: 5 }).map((_, index) => (
            <div
              key={index}
              className="h-48 animate-pulse rounded-2xl bg-bg-tertiary"
            />
          ))
        ) : result.items.length === 0 ? (
          <div className="rounded-2xl border border-border bg-bg-primary px-5 py-12 text-center">
            <p className="text-sm text-text-secondary">No orders found.</p>
          </div>
        ) : (
          result.items.map((order) => (
            <OrderCardMobile
              key={order.id}
              order={order}
              onRefund={openRefundModal}
              onStatusChange={handleStatusChange}
            />
          ))
        )}
      </div>

      {/* Pagination */}

      {isLoading ? (
        <PaginationSkeleton />
      ) : (
        result.pagination &&
        result.pagination.last_page > 1 && (
          <Pagination pagination={result.pagination} setPage={setPage} />
        )
      )}

      {/* Refund Modal */}

      {refundPayment && (
        <RefundPayment
          order={refundPayment}
          closeAlert={closeRefundModal}
          onSuccess={handleRefundSuccess}
        />
      )}
    </section>
  );
};

export default AdminOrders;
