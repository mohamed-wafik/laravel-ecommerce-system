import { X } from "lucide-react";

import FilterBadge from "../../../components/ui/FilterBadge";
import FilterField from "../../../components/ui/FilterField";
import { inputClass } from "../../../data/input";

export interface IReviewToolbarFilters {
  product_id: string;
  is_approved: string;
  rating: string;
  per_page: number;
}

type FilterFieldName = "product_id" | "is_approved" | "rating";

interface IProps {
  filters: IReviewToolbarFilters;

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
  const { product_id, is_approved, rating, per_page } = filters;

  const hasFilters = Boolean(product_id || is_approved || rating);

  return (
    <div className="overflow-hidden">
      <div className="py-4 sm:py-5">
        {/* Header */}

        <div className="flex justify-between">
          <div className="mb-4 flex items-center gap-2">
            <span className="text-sm font-semibold text-text-primary">
              Filters
            </span>

            <span className="text-xs text-text-tertiary">
              Refine your reviews
            </span>
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

        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
          {/* Product ID */}

          <FilterField label="Product ID">
            <input
              type="text"
              value={product_id}
              onChange={(event) =>
                onFilterChange("product_id", event.target.value)
              }
              placeholder="Enter product ID..."
              className={inputClass}
            />
          </FilterField>

          {/* Approval Status */}

          <FilterField label="Status">
            <select
              value={is_approved}
              onChange={(event) =>
                onFilterChange("is_approved", event.target.value)
              }
              className={inputClass}
            >
              <option value="">All status</option>

              <option value="1">Approved</option>

              <option value="0">Pending / Rejected</option>
            </select>
          </FilterField>

          {/* Rating */}

          <FilterField label="Rating">
            <select
              value={rating}
              onChange={(event) => onFilterChange("rating", event.target.value)}
              className={inputClass}
            >
              <option value="">All ratings</option>

              <option value="5">5 Stars</option>

              <option value="4">4 Stars</option>

              <option value="3">3 Stars</option>

              <option value="2">2 Stars</option>

              <option value="1">1 Star</option>
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

            {product_id && (
              <FilterBadge
                label={`Product: ${product_id}`}
                onRemove={() => onFilterChange("product_id", "")}
              />
            )}

            {is_approved && (
              <FilterBadge
                label={`Status: ${
                  is_approved === "1" ? "Approved" : "Pending / Rejected"
                }`}
                onRemove={() => onFilterChange("is_approved", "")}
              />
            )}

            {rating && (
              <FilterBadge
                label={`Rating: ${rating} Stars`}
                onRemove={() => onFilterChange("rating", "")}
              />
            )}
          </div>
        )}
      </div>
    </div>
  );
};

export default Toolbar;
