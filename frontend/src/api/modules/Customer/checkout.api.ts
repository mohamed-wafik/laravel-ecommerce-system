import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";

export const checkoutApi = {
  /**
   *  Hook — use at the top level of a component.
   *  Returns
   *    *  { data, isLoading, error } via React Query.
   *   */
  create: (data: any) => api.post(ENDPOINTS.CUSTOMER.CHECKOUT.CREATE, data),
};
