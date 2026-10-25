import type { IPagination, IProduct } from "../../interface";

import Pagination from "../../components/ui/Pagnation";
import ProductCard from "../../components/ProductCard";
import { ProductCardSkeleton } from "../../components/Skeleton/ProductCardSkeleton";
import PaginationSkeleton from "../../components/Skeleton/PaginationSkeleton";
import type { ProductFilterField } from "../../hook/useProductFilters";

interface IProps {
  isLoading: boolean;
  data: {
    items: IProduct[];
    pagination: IPagination;
  } | null;

  hasActiveFilters: boolean;
  clearAllFilters: () => void;

  sort: string;

  setFilter: (field: ProductFilterField, value: string) => void;

  setPage: (page: number) => void;
}

const Products = ({
  isLoading,
  data,
  clearAllFilters,
  hasActiveFilters,
  sort,
  setFilter,
  setPage,
}: IProps) => {
  // ─────────────────────────────────────────────
  // Loading State
  // ─────────────────────────────────────────────

  if (isLoading) {
    return (
      <main className="flex-1">
        {/* Header Skeleton */}
        <div className="mb-6 flex animate-pulse items-center justify-between rounded-xl border border-gray-100 bg-white px-5 py-3">
          {/* Products Count */}
          <div className="flex items-center gap-1">
            <div className="h-4 w-8 rounded bg-gray-200" />
            <div className="h-4 w-16 rounded bg-gray-200" />
          </div>

          {/* Sort */}
          <div className="h-10 w-48 rounded-lg bg-gray-200" />
        </div>

        {/* Products Skeleton */}
        <div className="mb-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
          {Array.from({ length: 10 }).map((_, index) => (
            <ProductCardSkeleton key={index} />
          ))}
        </div>

        <PaginationSkeleton />
      </main>
    );
  }

  // ─────────────────────────────────────────────
  // Empty State
  // ─────────────────────────────────────────────

  if (!data?.items?.length) {
    return (
      <main className="flex-1">
        <div className="py-12 text-center">
          <div className="mx-auto mb-4 h-24 w-24 text-gray-300">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={1}
                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
              />
            </svg>
          </div>

          <h3 className="mb-2 text-lg font-medium text-gray-900">
            No products found
          </h3>

          <p className="mb-4 text-gray-600">
            Try adjusting your filters or search terms
          </p>

          {hasActiveFilters && (
            <button
              type="button"
              onClick={clearAllFilters}
              className="rounded-lg bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-blue-700"
            >
              Clear all filters
            </button>
          )}
        </div>
      </main>
    );
  }

  // ─────────────────────────────────────────────
  // Products
  // ─────────────────────────────────────────────

  return (
    <main className="flex-1">
      {/* ─────────────────────────────────────────── */}
      {/* Products Header */}
      {/* ─────────────────────────────────────────── */}

      <div className="mb-6 flex items-center justify-between rounded-xl border border-gray-100 bg-white px-5 py-3">
        {/* Products Count */}
        <p className="text-sm text-gray-500">
          <span className="font-semibold text-dark">{data.items.length}</span>{" "}
          {data.items.length === 1 ? "product" : "products"}
        </p>

        {/* Sort */}
        <div className="flex items-center gap-3">
          <select
            value={sort}
            onChange={(e) => setFilter("sort", e.target.value)}
            className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition-shadow focus:border-transparent focus:ring-2 focus:ring-blue-500"
            aria-label="Sort products"
          >
            <option value="newest">Newest first</option>

            <option value="oldest">Oldest first</option>

            <option value="name_asc">Name (A–Z)</option>

            <option value="name_desc">Name (Z–A)</option>

            <option value="price_asc">Price (Low to High)</option>

            <option value="price_desc">Price (High to Low)</option>
          </select>
        </div>
      </div>

      {/* ─────────────────────────────────────────── */}
      {/* Product Grid */}
      {/* ─────────────────────────────────────────── */}

      <div className="mb-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
        {data.items.map((product) => (
          <ProductCard key={product.id} product={product} />
        ))}
      </div>

      {/* ─────────────────────────────────────────── */}
      {/* Pagination */}
      {/* ─────────────────────────────────────────── */}

      {data.pagination && data.pagination.last_page > 1 && (
        <Pagination setPage={setPage} pagination={data.pagination} />
      )}
    </main>
  );
};

export default Products;
