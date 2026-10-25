import useFetch from "../../../hook/useAuthentienticatedQuery";
import { ENDPOINTS } from "../../endpoints";

export const adminPaymentApi = {
  getAll: () =>
    useFetch({
      queryKey: ["admin-payment"],
      url: ENDPOINTS.ADMIN.PAYMENTS.ALL,
    }),
  getById: (id: string) =>
    useFetch({
      queryKey: ["admin-payment"],
      url: ENDPOINTS.ADMIN.PAYMENTS.SHOW(id),
    }),
};
