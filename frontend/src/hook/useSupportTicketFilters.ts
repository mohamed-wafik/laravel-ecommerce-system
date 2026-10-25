import { useSearchParams } from "react-router-dom";

const DEFAULT_FILTERS = {
  page: 1,
  per_page: 15,
  search: "",
  status: "",
};

const useSupportTicketFilters = () => {
  const [searchParams, setSearchParams] = useSearchParams();

  const filters = {
    page: Number(searchParams.get("page")) || DEFAULT_FILTERS.page,
    per_page: Number(searchParams.get("per_page")) || DEFAULT_FILTERS.per_page,
    search: searchParams.get("search") || DEFAULT_FILTERS.search,
    status: searchParams.get("status") || DEFAULT_FILTERS.status,
  };

  const setFilter = (field: "search" | "status", value: string) => {
    const params = new URLSearchParams(searchParams);

    if (value) {
      params.set(field, value);
    } else {
      params.delete(field);
    }

    params.set("page", "1");

    setSearchParams(params);
  };

  const setPage = (page: number) => {
    const params = new URLSearchParams(searchParams);

    params.set("page", String(page));

    setSearchParams(params);
  };

  const setPerPage = (perPage: number) => {
    const params = new URLSearchParams(searchParams);

    params.set("per_page", String(perPage));
    params.set("page", "1");

    setSearchParams(params);
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

export default useSupportTicketFilters;
