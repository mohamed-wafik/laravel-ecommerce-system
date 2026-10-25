import useFetch from "../../../hook/useAuthentienticatedQuery";
import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";

export const addressApi = {
  list: (query: string = "") =>
    useFetch({
      queryKey: ["addresses", query],
      url: `${ENDPOINTS.CUSTOMER.ADDRESS.ALL}${query}`,
    }),

  create: (data: any) => api.post(ENDPOINTS.CUSTOMER.ADDRESS.CREATE, data),

  update: (id: string, data: any) =>
    api.patch(ENDPOINTS.CUSTOMER.ADDRESS.UPDATE(id), data),

  delete: (id: string) => api.delete(ENDPOINTS.CUSTOMER.ADDRESS.DELETE(id)),
};
