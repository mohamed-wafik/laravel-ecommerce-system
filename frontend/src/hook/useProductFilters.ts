import { useSearchParams } from "react-router-dom";

// ─────────────────────────────────────────────
// Types
// ─────────────────────────────────────────────

export interface IProductFilters {
  page: number;
  per_page: number;

  search: string;
  category_id: string;
  brand_id: string;

  is_active: string;
  trashed: string;

  min_price: string;
  max_price: string;

  only_discounted: string;
  min_rating: string;

  sort: string;
}

export type ProductFilterField =
  | "search"
  | "category_id"
  | "brand_id"
  | "is_active"
  | "trashed"
  | "min_price"
  | "max_price"
  | "only_discounted"
  | "min_rating"
  | "sort";

// ─────────────────────────────────────────────
// Defaults
// ─────────────────────────────────────────────

const DEFAULT_FILTERS: IProductFilters = {
  page: 1,
  per_page: 10,

  search: "",
  category_id: "",
  brand_id: "",

  is_active: "",
  trashed: "",

  min_price: "",
  max_price: "",

  only_discounted: "",
  min_rating: "",

  sort: "",
};

// ─────────────────────────────────────────────
// Hook
// ─────────────────────────────────────────────

const useProductFilters = () => {
  const [searchParams, setSearchParams] = useSearchParams();

  const filters: IProductFilters = {
    page: Number(searchParams.get("page")) || DEFAULT_FILTERS.page,

    per_page: Number(searchParams.get("per_page")) || DEFAULT_FILTERS.per_page,

    search: searchParams.get("search") ?? DEFAULT_FILTERS.search,

    category_id: searchParams.get("category_id") ?? DEFAULT_FILTERS.category_id,

    brand_id: searchParams.get("brand_id") ?? DEFAULT_FILTERS.brand_id,

    is_active: searchParams.get("is_active") ?? DEFAULT_FILTERS.is_active,

    trashed: searchParams.get("trashed") ?? DEFAULT_FILTERS.trashed,

    min_price: searchParams.get("min_price") ?? DEFAULT_FILTERS.min_price,

    max_price: searchParams.get("max_price") ?? DEFAULT_FILTERS.max_price,

    only_discounted:
      searchParams.get("only_discounted") ?? DEFAULT_FILTERS.only_discounted,

    min_rating: searchParams.get("min_rating") ?? DEFAULT_FILTERS.min_rating,

    sort: searchParams.get("sort") ?? DEFAULT_FILTERS.sort,
  };

  // ─────────────────────────────────────────────
  // Update URL params
  // ─────────────────────────────────────────────

  const updateParams = (updates: Partial<IProductFilters>) => {
    const params = new URLSearchParams(searchParams);

    Object.entries(updates).forEach(([key, value]) => {
      if (value === "" || value === null || value === undefined) {
        params.delete(key);
        return;
      }

      params.set(key, String(value));
    });

    setSearchParams(params);
  };

  // ─────────────────────────────────────────────
  // Filters
  // ─────────────────────────────────────────────

  const setFilter = (field: ProductFilterField, value: string) => {
    updateParams({
      [field]: value,
      page: 1,
    });
  };

  // ─────────────────────────────────────────────
  // Pagination
  // ─────────────────────────────────────────────

  const setPage = (page: number) => {
    updateParams({ page });
  };

  const setPerPage = (per_page: number) => {
    updateParams({
      per_page,
      page: 1,
    });
  };

  // ─────────────────────────────────────────────
  // Price Range
  // ─────────────────────────────────────────────

  const setPriceRange = (min_price: string, max_price: string) => {
    updateParams({
      min_price,
      max_price,
      page: 1,
    });
  };

  // ─────────────────────────────────────────────
  // Clear Filters
  // ─────────────────────────────────────────────

  const clearFilters = () => {
    setSearchParams({});
  };

  return {
    filters,

    setFilter,
    setPage,
    setPerPage,
    setPriceRange,
    clearFilters,
  };
};

export default useProductFilters;
