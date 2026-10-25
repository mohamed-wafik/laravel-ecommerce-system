import { Menu } from "lucide-react";
import { useState } from "react";

import CategoriesFilter from "./CategoriesFilter";
import type {
  IProductFilters,
  ProductFilterField,
} from "../../hook/useProductFilters";

interface IProps {
  filters: IProductFilters;
  maxPrice: number;

  hasActiveFilters: boolean;
  clearAllFilters: () => void;

  setFilter: (field: ProductFilterField, value: string) => void;

  setPriceRange: (minPrice: string, maxPrice: string) => void;
}

const SiderbarFilter = ({
  filters,
  maxPrice,
  clearAllFilters,
  hasActiveFilters,
  setFilter,
  setPriceRange,
}: IProps) => {
  const [isOpen, setIsOpen] = useState(false);

  // ─────────────────────────────────────────────
  // Filter Values
  // ─────────────────────────────────────────────

  const { category_id, min_price, max_price, only_discounted, min_rating } =
    filters;

  const currentMinPrice = Number(min_price) || 0;

  const currentMaxPrice = max_price !== "" ? Number(max_price) : maxPrice;

  // ─────────────────────────────────────────────
  // Sidebar
  // ─────────────────────────────────────────────

  const toggleSidebar = () => {
    setIsOpen((prev) => !prev);
  };

  // ─────────────────────────────────────────────
  // Price
  // ─────────────────────────────────────────────

  // ─────────────────────────────────────────────
  // Price
  // ─────────────────────────────────────────────

  const handlePriceChange = (type: "min" | "max", value: number) => {
    if (type === "min") {
      const nextMin = Math.min(value, currentMaxPrice);

      setPriceRange(nextMin > 0 ? String(nextMin) : "", max_price);

      return;
    }

    const nextMax = Math.max(value, currentMinPrice);

    setPriceRange(min_price, nextMax < maxPrice ? String(nextMax) : "");
  };
  // ─────────────────────────────────────────────
  // Render
  // ─────────────────────────────────────────────

  return (
    <>
      {/* Mobile Overlay */}
      {isOpen && (
        <div
          className="fixed inset-0 z-50 bg-black/5 backdrop-blur-sm lg:hidden"
          onClick={toggleSidebar}
          aria-label="Close sidebar"
        />
      )}

      {/* Sidebar */}
      <aside className="shrink-0 lg:w-80">
        {/* Mobile Toggle */}
        <button
          type="button"
          className="cursor-pointer p-2 text-gray-700 transition hover:text-primary lg:hidden"
          onClick={toggleSidebar}
          aria-label="Toggle filters"
        >
          <Menu size={28} />
        </button>

        <div
          className={`fixed inset-0 z-50 h-full w-[300px] overflow-y-auto rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-transform duration-300 lg:sticky lg:top-30 lg:z-0 lg:h-auto lg:w-full lg:translate-x-0 ${
            isOpen ? "translate-x-0" : "-translate-x-full"
          }`}
        >
          {/* ─────────────────────────────────────── */}
          {/* Header */}
          {/* ─────────────────────────────────────── */}

          <div className="mb-6 flex items-center justify-between">
            <h2 className="text-xl font-semibold text-gray-900">Filters</h2>

            {hasActiveFilters && (
              <button
                type="button"
                onClick={clearAllFilters}
                className="text-sm font-medium text-blue-600 transition-colors hover:text-blue-700"
              >
                Clear all
              </button>
            )}
          </div>

          {/* ─────────────────────────────────────── */}
          {/* Categories */}
          {/* ─────────────────────────────────────── */}

          <div className="mb-6">
            <h3 className="mb-3 font-medium text-gray-900">Categories</h3>

            <CategoriesFilter
              selectedCategory={category_id || null}
              setSelectedCategory={(value) =>
                setFilter("category_id", value ?? "")
              }
            />
          </div>

          {/* ─────────────────────────────────────── */}
          {/* Price Range */}
          {/* ─────────────────────────────────────── */}

          <div className="mb-6">
            <h3 className="mb-3 font-medium text-gray-900">Price Range</h3>

            <div className="space-y-4">
              <div className="flex items-center justify-between text-sm text-gray-600">
                <span>${currentMinPrice}</span>
                <span>${currentMaxPrice}</span>
              </div>

              {/* Minimum Price */}
              <input
                type="range"
                min={0}
                max={maxPrice}
                value={currentMinPrice}
                onChange={(e) =>
                  handlePriceChange("min", Number(e.target.value))
                }
                className="slider h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200"
                aria-label="Minimum price"
              />

              {/* Maximum Price */}
              <input
                type="range"
                min={0}
                max={maxPrice}
                value={currentMaxPrice}
                onChange={(e) =>
                  handlePriceChange("max", Number(e.target.value))
                }
                className="slider h-2 w-full cursor-pointer appearance-none rounded-lg bg-gray-200"
                aria-label="Maximum price"
              />
            </div>
          </div>

          {/* ─────────────────────────────────────── */}
          {/* Discount */}
          {/* ─────────────────────────────────────── */}

          <div className="mb-6">
            <h3 className="mb-3 font-medium text-gray-900">Discount</h3>

            <label className="group flex cursor-pointer items-center">
              <input
                type="checkbox"
                checked={only_discounted === "1"}
                onChange={(e) =>
                  setFilter("only_discounted", e.target.checked ? "1" : "")
                }
                className="sr-only"
              />

              <div
                className={`mr-3 flex h-5 w-5 items-center justify-center rounded border-2 transition-colors ${
                  only_discounted === "1"
                    ? "border-blue-500 bg-blue-500"
                    : "border-gray-300 group-hover:border-gray-400"
                }`}
              >
                {only_discounted === "1" && (
                  <svg
                    className="h-3 w-3 text-white"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth={3}
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                )}
              </div>

              <span className="text-gray-700 group-hover:text-gray-900">
                On Sale Only
              </span>
            </label>
          </div>

          {/* ─────────────────────────────────────── */}
          {/* Rating */}
          {/* ─────────────────────────────────────── */}

          <div className="mb-6">
            <h3 className="mb-3 font-medium text-gray-900">Minimum Rating</h3>

            <select
              value={min_rating}
              onChange={(e) => setFilter("min_rating", e.target.value)}
              className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition-shadow focus:border-transparent focus:ring-2 focus:ring-blue-500"
            >
              <option value="">All ratings</option>
              <option value="1">⭐ 1 star & up</option>
              <option value="2">⭐⭐ 2 stars & up</option>
              <option value="3">⭐⭐⭐ 3 stars & up</option>
              <option value="4">⭐⭐⭐⭐ 4 stars & up</option>
              <option value="5">⭐⭐⭐⭐⭐ 5 stars only</option>
            </select>
          </div>
        </div>
      </aside>
    </>
  );
};

export default SiderbarFilter;
