import useFetch from "../../../hook/useAuthentienticatedQuery";
import type { IOrderFilters } from "../../../hook/useOrderFilters";
import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";

export interface IUpdateOrderStatusData {
  status:
    | "pending"
    | "processing"
    | "paid"
    | "shipped"
    | "delivered"
    | "cancelled";
}

export const adminOrderAPI = {
  // GET /admin/orders
  getAll: (params?: IOrderFilters) =>
    useFetch({
      queryKey: [
        "admin-orders",
        `${params?.search}`,
        `${params?.status}`,
        `${params?.user_id}`,
        `${params?.date_from}`,
        `${params?.date_to}`,
        `${params?.page}`,
        `${params?.per_page}`,
      ],
      url: ENDPOINTS.ADMIN.ORDERS.ALL,
      config: {
        params,
      },
    }),

  // GET /admin/orders/{id}
  getById: (id: string) =>
    useFetch({
      queryKey: ["admin-order", id],
      url: ENDPOINTS.ADMIN.ORDERS.SHOW(id),
    }),

  // PATCH /admin/orders/{id}/status
  updateStatus: async (id: string, data: IUpdateOrderStatusData) => {
    const response = await api.patch(
      ENDPOINTS.ADMIN.ORDERS.UPDATE_STATUS(id),
      data,
    );

    return response.data;
  },
  // POST /admin/order/{id}/refund
  refund: async (id: string) => {
    return api.post(ENDPOINTS.ADMIN.ORDERS.REFUND(id));
  },

  // GET /admin/orders/stats
  stats: () =>
    useFetch({
      queryKey: ["admin-order-stats"],
      url: ENDPOINTS.ADMIN.ORDERS.STATS,
    }),
};
