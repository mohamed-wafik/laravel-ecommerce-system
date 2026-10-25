import { useState } from "react";

import useFetch from "../../../hook/useAuthentienticatedQuery";

import Pagination from "../../../components/ui/Pagnation";

import PaymentToolbar from "./Toolbar";
import PaymentRow from "./PaymentRow";
import PaymentCardMobile from "./PaymentCardMobile";
import PaymentDetails from "./PaymentDetails";

import type {
  IPayment,
  IPaymentResponse,
} from "../../../schema/Admin/admin.payment.schema";

import PaginationSkeleton from "../../../components/Skeleton/PaginationSkeleton";
import { ENDPOINTS } from "../../../api/endpoints";
import { Table } from "../../../components/ui/Table";
import usePaymentFilters, {
  type PaymentFilterField,
} from "../../../hook/usePaymentFilters";

/* -------------------------------------------------------------------------- */
/* Component                                                                  */
/* -------------------------------------------------------------------------- */

const AdminPayments = () => {
  /* ------------------------------------------------------------------------ */
  /* Filters                                                                  */
  /* ------------------------------------------------------------------------ */

  const { filters, setFilter, setPage, setPerPage, clearFilters } =
    usePaymentFilters();

  /* ------------------------------------------------------------------------ */
  /* Payment details                                                          */
  /* ------------------------------------------------------------------------ */

  const [selectedPayment, setSelectedPayment] = useState<IPayment | null>(null);

  const [isDetailsOpen, setIsDetailsOpen] = useState(false);

  /* ------------------------------------------------------------------------ */
  /* API params                                                               */
  /* ------------------------------------------------------------------------ */

  /* ------------------------------------------------------------------------ */
  /* Payments Query                                                           */
  /* ------------------------------------------------------------------------ */

  const { data, isLoading, isFetching } = useFetch({
    queryKey: [
      "admin-payments",
      String(filters.page),
      String(filters.per_page),
      filters.status,
      filters.order_id.trim(),
      filters.provider.trim(),
    ],

    url: ENDPOINTS.ADMIN.PAYMENTS.ALL,

    config: {
      params: filters,
    },
  });

  /* ------------------------------------------------------------------------ */
  /* Payment response                                                         */
  /* ------------------------------------------------------------------------ */

  const result: IPaymentResponse = data?.data ?? {
    items: [],
    pagination: {
      current_page: filters.page,
      per_page: filters.per_page,
      total: 0,
      last_page: 1,
      from: null,
      to: null,
    },
  };

  /* ------------------------------------------------------------------------ */
  /* Payment details                                                          */
  /* ------------------------------------------------------------------------ */

  const openPaymentDetails = (payment: IPayment) => {
    setSelectedPayment(payment);
    setIsDetailsOpen(true);
  };

  const closePaymentDetails = () => {
    setIsDetailsOpen(false);
    setSelectedPayment(null);
  };

  /* ------------------------------------------------------------------------ */
  /* Render                                                                    */
  /* ------------------------------------------------------------------------ */

  return (
    <section className="space-y-6">
      {/* Header */}

      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-bold text-text-primary">Payments</h1>

          <p className="mt-1 text-sm text-text-secondary">
            Manage payments, payment status, and refunds.
          </p>
        </div>
      </div>

      {/* Toolbar */}

      <PaymentToolbar
        filters={{
          page: filters.page,
          status: filters.status,
          order_id: filters.order_id,
          provider: filters.provider,
          per_page: filters.per_page,
        }}
        onFilterChange={(field: PaymentFilterField, value: string) =>
          setFilter(field, value)
        }
        onPerPageChange={setPerPage}
        onClearFilters={clearFilters}
      />

      {/* Updating indicator */}

      {isFetching && !isLoading && (
        <div className="text-sm text-text-secondary">Updating payments...</div>
      )}

      {/* Desktop */}

      <Table
        isLoading={isLoading}
        length={result.items.length}
        emptyText="No payments found."
        width="1050"
        itemsHeader={[
          "Payment",
          "Customer",
          "Status",
          "Provider",
          "Amount",
          "Date",
        ]}
      >
        {result.items.map((payment) => (
          <PaymentRow
            key={payment.id}
            payment={payment}
            onView={openPaymentDetails}
          />
        ))}
      </Table>

      {/* Mobile */}

      <div className="space-y-3 md:hidden">
        {isLoading ? (
          Array.from({ length: 5 }).map((_, index) => (
            <div
              key={index}
              className="h-56 animate-pulse rounded-2xl bg-bg-tertiary"
            />
          ))
        ) : result.items.length === 0 ? (
          <div className="rounded-2xl border border-border bg-bg-primary px-5 py-12 text-center">
            <p className="text-sm text-text-secondary">No payments found.</p>
          </div>
        ) : (
          result.items.map((payment) => (
            <PaymentCardMobile
              key={payment.id}
              payment={payment}
              onView={openPaymentDetails}
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

      {/* Payment Details */}

      <PaymentDetails
        payment={selectedPayment}
        isOpen={isDetailsOpen}
        onClose={closePaymentDetails}
      />
    </section>
  );
};

export default AdminPayments;
