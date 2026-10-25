import { Search, X } from "lucide-react";

import FilterBadge from "../../../components/ui/FilterBadge";

import FilterField from "../../../components/ui/FilterField";
import { inputClass } from "../../../data/input";
import type {
  IUserFilters,
  UserFilterField,
} from "../../../hook/useUserFilters";

interface IProps {
  filters: IUserFilters;

  onFilterChange: (field: UserFilterField, value: string) => void;

  onPerPageChange: (value: number) => void;

  onClearFilters: () => void;
}
const Toolbar = ({
  filters,
  onFilterChange,
  onPerPageChange,
  onClearFilters,
}: IProps) => {
  const { search, role, is_active, per_page } = filters;

  const hasFilters = Boolean(search || role || is_active);

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
            placeholder="Search by name or email..."
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

          <span className="text-xs text-text-tertiary">Refine your users</span>
        </div>

        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {/* Role */}

          <FilterField label="Role">
            <select
              value={role}
              onChange={(event) => onFilterChange("role", event.target.value)}
              className={inputClass}
            >
              <option value="">All roles</option>

              <option value="customer">Customer</option>

              <option value="admin">Admin</option>
            </select>
          </FilterField>

          {/* Status */}

          <FilterField label="Status">
            <select
              value={is_active}
              onChange={(event) =>
                onFilterChange("is_active", event.target.value)
              }
              className={inputClass}
            >
              <option value="">All status</option>

              <option value="1">Active</option>

              <option value="0">Inactive</option>
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

            {role && (
              <FilterBadge
                label={`Role: ${role}`}
                onRemove={() => onFilterChange("role", "")}
              />
            )}

            {is_active && (
              <FilterBadge
                label={`Status: ${is_active === "1" ? "Active" : "Inactive"}`}
                onRemove={() => onFilterChange("is_active", "")}
              />
            )}
          </div>
        )}
      </div>
    </div>
  );
};

export default Toolbar;
