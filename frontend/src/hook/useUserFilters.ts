import { useSearchParams } from "react-router-dom";

export interface IUserFilters {
  page: number;
  per_page: number;
  search: string;
  role: string;
  is_active: string;
}

export type UserFilterField = "search" | "role" | "is_active";

const DEFAULT_FILTERS: IUserFilters = {
  page: 1,
  per_page: 10,
  search: "",
  role: "",
  is_active: "",
};

const useUserFilters = () => {
  const [searchParams, setSearchParams] = useSearchParams();

  const filters: IUserFilters = {
    page: Number(searchParams.get("page")) || DEFAULT_FILTERS.page,
    per_page: Number(searchParams.get("per_page")) || DEFAULT_FILTERS.per_page,
    search: searchParams.get("search") ?? "",
    role: searchParams.get("role") ?? "",
    is_active: searchParams.get("is_active") ?? "",
  };

  const updateParams = (updates: Partial<IUserFilters>) => {
    const params = new URLSearchParams(searchParams);

    Object.entries(updates).forEach(([key, value]) => {
      const isEmpty = value === "" || value === null || value === undefined;

      if (isEmpty) {
        params.delete(key);
        return;
      }

      params.set(key, String(value));
    });

    setSearchParams(params);
  };

  const setFilter = (field: UserFilterField, value: string) => {
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

export default useUserFilters;
