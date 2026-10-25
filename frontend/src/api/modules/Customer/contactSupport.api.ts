import useFetch from "../../../hook/useAuthentienticatedQuery";
import type { ContactSupportInput } from "../../../schema/support.schema";
import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";

export const contactSupportAPI = {
  /**
   * Hook — use at the top level of a component.
   * Returns { data, isLoading, error } via React Query.
   */
  getAll: () =>
    useFetch({
      queryKey: ["contact-support"],
      url: ENDPOINTS.CUSTOMER.CONTACT_SUPPORT.ALL,
    }),
  /**
   * Hook — use at the top level of a component.
   * Returns
   * { data, isLoading, error } via React Query.
   * */
  create: (data: ContactSupportInput) =>
    api.post(ENDPOINTS.CUSTOMER.CONTACT_SUPPORT.CREATE, data),

  // sHOW: (id: string) =>
  //   useFetch({
  //     queryKey: ["contact-support", id],
  //     url: ENDPOINTS.CUSTOMER.CONTACT_SUPPORT.sHOW(id),
  //   }),
};
