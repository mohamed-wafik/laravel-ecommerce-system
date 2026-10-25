import { Search, X } from "lucide-react";

import FilterBadge from "../../../components/ui/FilterBadge";
import FilterField from "../../../components/ui/FilterField";
import { inputClass } from "../../../data/input";
import type {
  IOrderFilters,
  OrderFilterField,
} from "../../../hook/useOrderFilters";

interface IProps {
  filters: IOrderFilters;

  onFilterChange: (field: OrderFilterField, value: string) => void;

  onPerPageChange: (value: number) => void;

  onClearFilters: () => void;
}

const ORDER_STATUSES = [
  { value: "pending", label: "Pending" },
  { value: "processing", label: "Processing" },
  { value: "paid", label: "Paid" },
  { value: "shipped", label: "Shipped" },
  { value: "delivered", label: "Delivered" },
  { value: "cancelled", label: "Cancelled" },
];

const OrderToolbar = ({
  filters,
  onFilterChange,
  onPerPageChange,
  onClearFilters,
}: IProps) => {
  const { search, status, user_id, date_from, date_to, per_page } = filters;

  const hasFilters = Boolean(
    search || status || user_id || date_from || date_to,
  );

  return (
    <div className="overflow-hidden">
      {/* Search */}

      <div className="flex flex-col gap-4 border-b border-border py-4 sm:py-5 lg:flex-row lg:items-center lg:justify-between">
        <div className="relative w-full lg:max-w-md">
          <Search
            size={18}
            className="absolute left-3.5 top-1/2 -translate-y-1/2 text-text-tertiary"
          />

          <input
            type="search"
            value={search}
            onChange={(event) => onFilterChange("search", event.target.value)}
            placeholder="Search by order number or customer..."
            className={`${inputClass} pl-10 pr-4`}
          />
        </div>

        {hasFilters && (
          <button
            type="button"
            onClick={onClearFilters}
            className="inline-flex h-10 shrink-0 items-center justify-center gap-2 self-start rounded-xl px-3 text-sm font-medium text-text-secondary transition hover:bg-error-50 hover:text-error lg:self-auto"
          >
            <X size={16} />
            Clear filters
          </button>
        )}
      </div>

      {/* Filters */}

      <div className="py-4 sm:py-5">
        <div className="mb-4 flex items-center gap-2">
          <span className="text-sm font-semibold text-text-primary">
            Filters
          </span>

          <span className="text-xs text-text-tertiary">Refine your orders</span>
        </div>

        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
          {/* Status */}

          <FilterField label="Status">
            <select
              value={status}
              onChange={(event) => onFilterChange("status", event.target.value)}
              className={inputClass}
            >
              <option value="">All status</option>

              {ORDER_STATUSES.map((item) => (
                <option key={item.value} value={item.value}>
                  {item.label}
                </option>
              ))}
            </select>
          </FilterField>

          {/* User ID */}

          <FilterField label="User ID">
            <input
              type="text"
              value={user_id}
              onChange={(event) =>
                onFilterChange("user_id", event.target.value)
              }
              placeholder="Enter user ID..."
              className={inputClass}
            />
          </FilterField>

          {/* Date From */}

          <FilterField label="Date from">
            <input
              type="date"
              value={date_from}
              onChange={(event) =>
                onFilterChange("date_from", event.target.value)
              }
              className={inputClass}
            />
          </FilterField>

          {/* Date To */}

          <FilterField label="Date to">
            <input
              type="date"
              value={date_to}
              onChange={(event) =>
                onFilterChange("date_to", event.target.value)
              }
              className={inputClass}
            />
          </FilterField>

          {/* Per page */}

          <FilterField label="Per page">
            <select
              value={per_page}
              onChange={(event) => onPerPageChange(Number(event.target.value))}
              className={inputClass}
            >
              <option value={10}>10</option>
              <option value={20}>20</option>
              <option value={30}>30</option>
              <option value={50}>50</option>
              <option value={100}>100</option>
            </select>
          </FilterField>
        </div>

        {/* Active filters */}

        {hasFilters && (
          <div className="mt-5 flex flex-wrap items-center gap-2 border-t border-border pt-4">
            <span className="mr-1 text-xs font-medium text-text-tertiary">
              Active filters:
            </span>

            {/* Search */}

            {search && (
              <FilterBadge
                label={`Search: ${search}`}
                onRemove={() => onFilterChange("search", "")}
              />
            )}

            {/* Status */}

            {status && (
              <FilterBadge
                label={`Status: ${
                  ORDER_STATUSES.find((item) => item.value === status)?.label ??
                  status
                }`}
                onRemove={() => onFilterChange("status", "")}
              />
            )}

            {/* User */}

            {user_id && (
              <FilterBadge
                label={`User: ${user_id}`}
                onRemove={() => onFilterChange("user_id", "")}
              />
            )}

            {/* Date From */}

            {date_from && (
              <FilterBadge
                label={`From: ${date_from}`}
                onRemove={() => onFilterChange("date_from", "")}
              />
            )}

            {/* Date To */}

            {date_to && (
              <FilterBadge
                label={`To: ${date_to}`}
                onRemove={() => onFilterChange("date_to", "")}
              />
            )}
          </div>
        )}
      </div>
    </div>
  );
};

export default OrderToolbar;
