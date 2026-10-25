import { useSearchParams } from "react-router-dom";

export interface IReviewFilters {
  page: number;
  per_page: number;
  product_id: string;
  is_approved: string;
  rating: string;
}

export type ReviewFilterField = "product_id" | "is_approved" | "rating";

const DEFAULT_FILTERS: IReviewFilters = {
  page: 1,
  per_page: 20,
  product_id: "",
  is_approved: "",
  rating: "",
};

const useReviewFilters = () => {
  const [searchParams, setSearchParams] = useSearchParams();

  const filters: IReviewFilters = {
    page: Number(searchParams.get("page")) || DEFAULT_FILTERS.page,

    per_page: Number(searchParams.get("per_page")) || DEFAULT_FILTERS.per_page,

    product_id: searchParams.get("product_id") ?? DEFAULT_FILTERS.product_id,

    is_approved: searchParams.get("is_approved") ?? DEFAULT_FILTERS.is_approved,

    rating: searchParams.get("rating") ?? DEFAULT_FILTERS.rating,
  };

  const updateParams = (updates: Partial<IReviewFilters>) => {
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

  const setFilter = (field: ReviewFilterField, value: string) => {
    updateParams({
      [field]: value,
      page: 1,
    });
  };

  const setPage = (page: number) => {
    updateParams({ page });
  };

  const setPerPage = (per_page: number) => {
    updateParams({
      per_page,
      page: 1,
    });
  };

  const clearFilters = () => {
    setSearchParams({});
  };

  return {
    filters,
    setFilter,
    setPage,
    setPerPage,
    clearFilters,
  };
};

export default useReviewFilters;
