import { X } from "lucide-react";

import FilterBadge from "../../../components/ui/FilterBadge";
import FilterField from "../../../components/ui/FilterField";
import { inputClass } from "../../../data/input";
import type {
  IPaymentFilters,
  PaymentFilterField,
} from "../../../hook/usePaymentFilters";

interface IProps {
  filters: IPaymentFilters;
  onFilterChange: (field: PaymentFilterField, value: string) => void;
  onPerPageChange: (value: number) => void;
  onClearFilters: () => void;
}

const PAYMENT_STATUSES = [
  {
    value: "pending",
    label: "Pending",
  },
  {
    value: "succeeded",
    label: "Succeeded",
  },
  {
    value: "failed",
    label: "Failed",
  },
  {
    value: "cancelled",
    label: "Cancelled",
  },
  {
    value: "refunded",
    label: "Refunded",
  },
];

const PAYMENT_PROVIDERS = [
  {
    value: "stripe",
    label: "Stripe",
  },
];

const PaymentToolbar = ({
  filters,
  onFilterChange,
  onPerPageChange,
  onClearFilters,
}: IProps) => {
  const { status, order_id, provider, per_page } = filters;

  const hasFilters = Boolean(status || order_id || provider);

  return (
    <div className="overflow-hidden">
      <div className="flex flex-col gap-4 border-b border-border py-4 sm:py-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
          <h2 className="text-sm font-semibold text-text-primary">
            Payment Filters
          </h2>

          <p className="mt-1 text-xs text-text-tertiary">
            Refine payments by status, order, or provider.
          </p>
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

      <div className="py-4 sm:py-5">
        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <FilterField label="Status">
            <select
              value={status}
              onChange={(event) => onFilterChange("status", event.target.value)}
              className={inputClass}
            >
              <option value="">All statuses</option>

              {PAYMENT_STATUSES.map((item) => (
                <option key={item.value} value={item.value}>
                  {item.label}
                </option>
              ))}
            </select>
          </FilterField>

          <FilterField label="Order ID">
            <input
              type="text"
              value={order_id}
              onChange={(event) =>
                onFilterChange("order_id", event.target.value)
              }
              placeholder="Enter order ID..."
              className={inputClass}
            />
          </FilterField>

          <FilterField label="Provider">
            <select
              value={provider}
              onChange={(event) =>
                onFilterChange("provider", event.target.value)
              }
              className={inputClass}
            >
              <option value="">All providers</option>

              {PAYMENT_PROVIDERS.map((item) => (
                <option key={item.value} value={item.value}>
                  {item.label}
                </option>
              ))}
            </select>
          </FilterField>

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

        {hasFilters && (
          <div className="mt-5 flex flex-wrap items-center gap-2 border-t border-border pt-4">
            <span className="mr-1 text-xs font-medium text-text-tertiary">
              Active filters:
            </span>

            {status && (
              <FilterBadge
                label={`Status: ${
                  PAYMENT_STATUSES.find((item) => item.value === status)
                    ?.label ?? status
                }`}
                onRemove={() => onFilterChange("status", "")}
              />
            )}

            {order_id && (
              <FilterBadge
                label={`Order: ${order_id}`}
                onRemove={() => onFilterChange("order_id", "")}
              />
            )}

            {provider && (
              <FilterBadge
                label={`Provider: ${
                  PAYMENT_PROVIDERS.find((item) => item.value === provider)
                    ?.label ?? provider
                }`}
                onRemove={() => onFilterChange("provider", "")}
              />
            )}
          </div>
        )}
      </div>
    </div>
  );
};

export default PaymentToolbar;
