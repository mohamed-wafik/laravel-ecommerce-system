import useFetch from "../../../hook/useAuthentienticatedQuery";
import type { IUserFilters } from "../../../hook/useUserFilters";

import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";

export const adminUserAPI = {
  getAll: (params?: IUserFilters) =>
    useFetch({
      queryKey: [
        "admin-users",
        String(params?.page),
        String(params?.per_page),
        params?.search ?? "",
        params?.role ?? "",
        params?.is_active ?? "",
      ],
      url: ENDPOINTS.ADMIN.USERS.ALL,
      config: {
        params,
      },
    }),

  getById: (id: string) =>
    useFetch({
      queryKey: ["admin-user", id],
      url: ENDPOINTS.ADMIN.USERS.SHOW(id),
    }),

  getOrdersByUser: (userId: string, params?: Record<string, unknown>) =>
    useFetch({
      queryKey: ["admin-user-orders", userId, JSON.stringify(params ?? {})],
      url: ENDPOINTS.ADMIN.USERS.ORDERS(userId),
      config: {
        params,
      },
    }),

  create: (data: FormData) =>
    api.post(ENDPOINTS.ADMIN.USERS.CREATE, data, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    }),

  update: (id: string, data: FormData) =>
    api.post(ENDPOINTS.ADMIN.USERS.UPDATE(id), data, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    }),

  toggleActive: (id: string) =>
    api.post(ENDPOINTS.ADMIN.USERS.TOGGLE_ACTIVE(id)),

  delete: (id: string) => api.delete(ENDPOINTS.ADMIN.USERS.DELETE(id)),
};
