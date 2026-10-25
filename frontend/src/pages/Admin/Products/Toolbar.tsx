import { Search, X } from "lucide-react";

import FilterField from "../../../components/ui/FilterField";
import FilterBadge from "../../../components/ui/FilterBadge";
import type { IBrand, ICategory } from "../../../interface";
import { inputClass } from "../../../data/input";
import type {
  IProductFilters,
  ProductFilterField,
} from "../../../hook/useProductFilters";

interface IProps {
  filters: IProductFilters;

  onFilterChange: (field: ProductFilterField, value: string) => void;

  onPerPageChange: (value: number) => void;

  onClearFilters: () => void;
  categories: ICategory[] | null;
  brands: IBrand[] | null;
}

/* -------------------------------------------------------------------------- */
/* Component                                                                  */
/* -------------------------------------------------------------------------- */

const Toolbar = ({
  filters,
  onFilterChange,
  onPerPageChange,
  onClearFilters,
  brands,
  categories,
}: IProps) => {
  const {
    search,
    category_id,
    brand_id,
    is_active,
    min_price,
    max_price,
    trashed,
    per_page,
  } = filters;

  const hasFilters =
    search ||
    category_id ||
    brand_id ||
    is_active ||
    min_price ||
    max_price ||
    trashed;

  return (
    <div className="overflow-hidden">
      {/* Search header */}

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
            placeholder="Search by product name or SKU..."
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

          <span className="text-xs text-text-tertiary">
            Refine your products
          </span>
        </div>

        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-7">
          {/* Category */}

          <FilterField label="Category">
            <select
              value={category_id}
              onChange={(event) =>
                onFilterChange("category_id", event.target.value)
              }
              className={inputClass}
            >
              <option value="">All categories</option>

              {categories &&
                categories.map((category) => (
                  <option key={category.id} value={category.id}>
                    {category.name}
                  </option>
                ))}
            </select>
          </FilterField>

          {/* Brand */}

          <FilterField label="Brand">
            <select
              value={brand_id}
              onChange={(event) =>
                onFilterChange("brand_id", event.target.value)
              }
              className={inputClass}
            >
              <option value="">All brands</option>

              {brands &&
                brands.map((brand) => (
                  <option key={brand.id} value={brand.id}>
                    {brand.name}
                  </option>
                ))}
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

          {/* Min Price */}

          <FilterField label="Min price">
            <div className="relative">
              <span className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs font-medium text-text-tertiary">
                $
              </span>

              <input
                type="number"
                min="0"
                step="0.01"
                value={min_price}
                onChange={(event) =>
                  onFilterChange("min_price", event.target.value)
                }
                placeholder="0.00"
                className={`${inputClass} pl-7`}
              />
            </div>
          </FilterField>

          {/* Max Price */}

          <FilterField label="Max price">
            <div className="relative">
              <span className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs font-medium text-text-tertiary">
                $
              </span>

              <input
                type="number"
                min="0"
                step="0.01"
                value={max_price}
                onChange={(event) =>
                  onFilterChange("max_price", event.target.value)
                }
                placeholder="0.00"
                className={`${inputClass} pl-7`}
              />
            </div>
          </FilterField>

          {/* Trashed */}

          <FilterField label="Deleted products">
            <select
              value={trashed}
              onChange={(event) =>
                onFilterChange("trashed", event.target.value)
              }
              className={inputClass}
            >
              <option value="">Active products</option>
              <option value="with">With deleted</option>
              <option value="only">Only deleted</option>
            </select>
          </FilterField>

          {/* Per Page */}

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

        {/* Active filter summary */}

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

            {category_id && (
              <FilterBadge
                label="Category"
                onRemove={() => onFilterChange("category_id", "")}
              />
            )}

            {brand_id && (
              <FilterBadge
                label="Brand"
                onRemove={() => onFilterChange("brand_id", "")}
              />
            )}

            {is_active && (
              <FilterBadge
                label={`Status: ${is_active === "1" ? "Active" : "Inactive"}`}
                onRemove={() => onFilterChange("is_active", "")}
              />
            )}

            {min_price && (
              <FilterBadge
                label={`Min: $${min_price}`}
                onRemove={() => onFilterChange("min_price", "")}
              />
            )}

            {max_price && (
              <FilterBadge
                label={`Max: $${max_price}`}
                onRemove={() => onFilterChange("max_price", "")}
              />
            )}

            {trashed && (
              <FilterBadge
                label={`Deleted: ${trashed === "only" ? "Only" : "With"}`}
                onRemove={() => onFilterChange("trashed", "")}
              />
            )}
          </div>
        )}
      </div>
    </div>
  );
};

export default Toolbar;
