import { Search, X } from "lucide-react";

import FilterBadge from "../../../components/ui/FilterBadge";
import FilterField from "../../../components/ui/FilterField";
import { inputClass } from "../../../data/input";

export interface ISupportToolbarFilters {
  search: string;
  status: string;
  per_page: number;
}

type FilterFieldName = "search" | "status";

interface IProps {
  filters: ISupportToolbarFilters;

  onFilterChange: (field: FilterFieldName, value: string) => void;

  onPerPageChange: (value: number) => void;

  onClearFilters: () => void;
}

const Toolbar = ({
  filters,
  onFilterChange,
  onPerPageChange,
  onClearFilters,
}: IProps) => {
  const { search, status, per_page } = filters;

  const hasFilters = Boolean(search || status);

  return (
    <div className="overflow-hidden">
      <div className="py-4 sm:py-5">
        {/* Header */}

        <div className="flex items-start justify-between gap-4">
          <div className="mb-4">
            <div className="flex items-center gap-2">
              <span className="text-sm font-semibold text-text-primary">
                Filters
              </span>

              <span className="hidden text-xs text-text-tertiary sm:inline">
                Find and manage support tickets
              </span>
            </div>
          </div>

          {hasFilters && (
            <button
              type="button"
              onClick={onClearFilters}
              className="inline-flex h-10 shrink-0 items-center justify-center gap-2 rounded-xl px-3 text-sm font-medium text-text-secondary transition hover:bg-error-50 hover:text-error"
            >
              <X size={16} />

              <span className="hidden sm:inline">Clear filters</span>
            </button>
          )}
        </div>

        {/* Filters */}

        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {/* Search */}

          <FilterField label="Search">
            <div className="relative">
              <Search
                size={17}
                className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-text-tertiary"
              />

              <input
                type="text"
                value={search}
                onChange={(event) =>
                  onFilterChange("search", event.target.value)
                }
                placeholder="Search by name or email or subject ..."
                className={`${inputClass} pl-10`}
              />
            </div>
          </FilterField>

          {/* Status */}

          <FilterField label="Status">
            <select
              value={status}
              onChange={(event) => onFilterChange("status", event.target.value)}
              className={inputClass}
            >
              <option value="">All statuses</option>

              <option value="open">Open</option>

              <option value="in_progress">In Progress</option>

              <option value="resolved">Resolved</option>

              <option value="closed">Closed</option>
            </select>
          </FilterField>

          {/* Per page */}

          <FilterField label="Per page">
            <select
              value={per_page}
              onChange={(event) => onPerPageChange(Number(event.target.value))}
              className={inputClass}
            >
              <option value={10}>10</option>
              <option value={15}>15</option>
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

            {search && (
              <FilterBadge
                label={`Search: ${search}`}
                onRemove={() => onFilterChange("search", "")}
              />
            )}

            {status && (
              <FilterBadge
                label={`Status: ${status
                  .replace("_", " ")
                  .replace(/\b\w/g, (char) => char.toUpperCase())}`}
                onRemove={() => onFilterChange("status", "")}
              />
            )}
          </div>
        )}
      </div>
    </div>
  );
};

export default Toolbar;
