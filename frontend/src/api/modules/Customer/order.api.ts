import useFetch from "../../../hook/useAuthentienticatedQuery";
import { ENDPOINTS } from "../../endpoints";

export const orderAPI = {
  /**
   * Hook — use at the top level of a component.
   * Returns { data, isLoading, error } via React Query.
   */
  getAll: (page: number) =>
    useFetch({
      queryKey: ["orders", `${page}`],
      url: ENDPOINTS.CUSTOMER.ORDERS.ALL,
      config: {
        params: {
          page,
        },
      },
    }),

  /**
   * Hook — use at the top level of a component (e.g. OrderTracking page).
   * Do NOT call inside useEffect or with await.
   */
  getById: (id: string) =>
    useFetch({
      queryKey: ["order", `${id}`],
      url: ENDPOINTS.CUSTOMER.ORDERS.SHOW(id),
    }),
};
