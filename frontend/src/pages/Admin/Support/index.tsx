import { useState } from "react";

import Pagination from "../../../components/ui/Pagnation";
import { Table } from "../../../components/ui/Table";

import PaginationSkeleton from "../../../components/Skeleton/PaginationSkeleton";

import useSupportTicketFilters from "../../../hook/useSupportTicketFilters";

import SupportStats from "./SupportStats";

import {
  adminSupportAPI,
  type ISupportTicket,
} from "../../../api/modules/Admin/contactSupport.api";

import Toolbar from "./Toolbar";
import SupportTicketRow from "./SupportTicketRow";
import SupportTicketCardMobile from "./SupportTicketCardMobile";
import SupportTicketModal from "./SupportTicketModal";

interface ISupportResponse {
  items: ISupportTicket[];
  pagination: any;
}

const AdminSupport = () => {
  const [selectedTicket, setSelectedTicket] = useState<ISupportTicket | null>(
    null,
  );

  const { filters, setFilter, setPage, setPerPage, clearFilters } =
    useSupportTicketFilters();

  const { data, isLoading, isFetching, refetch } =
    adminSupportAPI.list(filters);

  const result: ISupportResponse = data?.data ?? {
    items: [],
    pagination: {},
  };

  /*
   * Open ticket model
   */
  const openTicket = (ticket: ISupportTicket) => {
    setSelectedTicket(ticket);
  };

  /*
   * Close ticket model
   */
  const closeTicket = () => {
    setSelectedTicket(null);
  };

  /*
   * Called after reply/status update.
   */
  const handleSuccess = async () => {
    await refetch();
    closeTicket();
  };

  return (
    <section className="space-y-6">
      {/* Header */}

      <div>
        <h1 className="text-2xl font-bold text-text-primary">
          Support Tickets
        </h1>

        <p className="mt-1 text-sm text-text-secondary">
          Manage customer support requests and conversations.
        </p>
      </div>

      {/* Stats */}

      <SupportStats />

      {/* Toolbar */}

      <Toolbar
        filters={filters}
        onFilterChange={setFilter}
        onPerPageChange={setPerPage}
        onClearFilters={clearFilters}
      />

      {/* Fetching */}

      {isFetching && !isLoading && (
        <div className="text-sm text-text-secondary">
          Updating support tickets...
        </div>
      )}

      {/* Desktop */}

      <div className="hidden md:block">
        <Table
          isLoading={isLoading}
          length={result.items.length}
          emptyText="No support tickets found."
          width="1250"
          itemsHeader={[
            "Customer",
            "Subject",
            "Status",
            "Last Reply",
            "Created",
          ]}
        >
          {result.items.map((ticket) => (
            <SupportTicketRow
              key={ticket.id}
              ticket={ticket}
              openTicket={openTicket}
            />
          ))}
        </Table>
      </div>

      {/* Mobile */}

      <div className="space-y-3 md:hidden">
        {isLoading ? (
          Array.from({ length: 5 }).map((_, index) => (
            <div
              key={index}
              className="h-64 animate-pulse rounded-2xl bg-bg-tertiary"
            />
          ))
        ) : result.items.length === 0 ? (
          <div className="rounded-2xl border border-border bg-bg-primary px-5 py-12 text-center">
            <p className="text-sm text-text-secondary">
              No support tickets found.
            </p>
          </div>
        ) : (
          result.items.map((ticket) => (
            <SupportTicketCardMobile
              key={ticket.id}
              ticket={ticket}
              openTicket={openTicket}
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

      {/* Support Ticket Model */}

      {selectedTicket && (
        <SupportTicketModal
          ticket={selectedTicket}
          closeModal={closeTicket}
          onSuccess={handleSuccess}
        />
      )}
    </section>
  );
};

export default AdminSupport;
