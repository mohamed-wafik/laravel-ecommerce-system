import { useSearchParams } from "react-router-dom";

/* -------------------------------------------------------------------------- */
/* Types                                                                      */
/* -------------------------------------------------------------------------- */

export interface IOrderFilters {
  page: number;
  per_page: number;
  search: string;
  status: string;
  user_id: string;
  date_from: string;
  date_to: string;
}

export type OrderFilterField =
  | "search"
  | "status"
  | "user_id"
  | "date_from"
  | "date_to";

/* -------------------------------------------------------------------------- */
/* Defaults                                                                    */
/* -------------------------------------------------------------------------- */

const DEFAULT_FILTERS: IOrderFilters = {
  page: 1,
  per_page: 10,
  search: "",
  status: "",
  user_id: "",
  date_from: "",
  date_to: "",
};

/* -------------------------------------------------------------------------- */
/* Hook                                                                        */
/* -------------------------------------------------------------------------- */

const useOrderFilters = () => {
  const [searchParams, setSearchParams] = useSearchParams();

  const filters: IOrderFilters = {
    page: Number(searchParams.get("page")) || DEFAULT_FILTERS.page,

    per_page: Number(searchParams.get("per_page")) || DEFAULT_FILTERS.per_page,

    search: searchParams.get("search") ?? DEFAULT_FILTERS.search,

    status: searchParams.get("status") ?? DEFAULT_FILTERS.status,

    user_id: searchParams.get("user_id") ?? DEFAULT_FILTERS.user_id,

    date_from: searchParams.get("date_from") ?? DEFAULT_FILTERS.date_from,

    date_to: searchParams.get("date_to") ?? DEFAULT_FILTERS.date_to,
  };

  /* ------------------------------------------------------------------------ */
  /* Update URL                                                               */
  /* ------------------------------------------------------------------------ */

  const updateParams = (updates: Partial<IOrderFilters>) => {
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

  /* ------------------------------------------------------------------------ */
  /* Filter                                                                    */
  /* ------------------------------------------------------------------------ */

  const setFilter = (field: OrderFilterField, value: string) => {
    updateParams({
      [field]: value,
      page: 1,
    });
  };

  /* ------------------------------------------------------------------------ */
  /* Pagination                                                                */
  /* ------------------------------------------------------------------------ */

  const setPage = (page: number) => {
    updateParams({ page });
  };

  const setPerPage = (per_page: number) => {
    updateParams({
      per_page,
      page: 1,
    });
  };

  /* ------------------------------------------------------------------------ */
  /* Reset                                                                      */
  /* ------------------------------------------------------------------------ */

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

export default useOrderFilters;
