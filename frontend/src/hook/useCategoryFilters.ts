import { useSearchParams } from "react-router-dom";

export interface ICategoryFilters {
  page: number;
  per_page: number;
  search: string;
  parent_id: string;
  is_active: string;
}

export type CategoryFilterField = "search" | "parent_id" | "is_active";

const DEFAULT_FILTERS: ICategoryFilters = {
  page: 1,
  per_page: 10,
  search: "",
  parent_id: "",
  is_active: "",
};

const useCategoryFilters = () => {
  const [searchParams, setSearchParams] = useSearchParams();

  const filters: ICategoryFilters = {
    page: Number(searchParams.get("page")) || DEFAULT_FILTERS.page,
    per_page: Number(searchParams.get("per_page")) || DEFAULT_FILTERS.per_page,
    search: searchParams.get("search") ?? DEFAULT_FILTERS.search,
    parent_id: searchParams.get("parent_id") ?? DEFAULT_FILTERS.parent_id,
    is_active: searchParams.get("is_active") ?? DEFAULT_FILTERS.is_active,
  };

  const updateParams = (updates: Partial<ICategoryFilters>) => {
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

  const setFilter = (field: CategoryFilterField, value: string) => {
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

export default useCategoryFilters;
