import { useSearchParams } from "react-router-dom";

/* -------------------------------------------------------------------------- */
/* Types                                                                      */
/* -------------------------------------------------------------------------- */

export interface IPaymentFilters {
  page: number;
  per_page: number;
  status: string;
  order_id: string;
  provider: string;
}

export type PaymentFilterField = "status" | "order_id" | "provider";

/* -------------------------------------------------------------------------- */
/* Defaults                                                                    */
/* -------------------------------------------------------------------------- */

const DEFAULT_FILTERS: IPaymentFilters = {
  page: 1,
  per_page: 15,
  status: "",
  order_id: "",
  provider: "",
};

/* -------------------------------------------------------------------------- */
/* Hook                                                                        */
/* -------------------------------------------------------------------------- */

const usePaymentFilters = () => {
  const [searchParams, setSearchParams] = useSearchParams();

  const filters: IPaymentFilters = {
    page: Number(searchParams.get("page")) || DEFAULT_FILTERS.page,

    per_page: Number(searchParams.get("per_page")) || DEFAULT_FILTERS.per_page,

    status: searchParams.get("status") ?? DEFAULT_FILTERS.status,

    order_id: searchParams.get("order_id") ?? DEFAULT_FILTERS.order_id,

    provider: searchParams.get("provider") ?? DEFAULT_FILTERS.provider,
  };

  /* ------------------------------------------------------------------------ */
  /* Update URL                                                               */
  /* ------------------------------------------------------------------------ */

  const updateParams = (updates: Partial<IPaymentFilters>) => {
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

  const setFilter = (field: PaymentFilterField, value: string) => {
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

export default usePaymentFilters;
