import useFetch from "../../../hook/useAuthentienticatedQuery";
import type { IBrandFilters } from "../../../hook/useBrandFilters";
import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";

export const adminBrandAPI = {
  // GET /brands
  getAll: (params?: IBrandFilters) =>
    useFetch({
      queryKey: [
        "admin-brands",
        `${params?.is_active}`,
        `${params?.page}`,
        `${params?.per_page}`,
        `${params?.search}`,
      ],
      url: ENDPOINTS.ADMIN.BRANDS.ALL,
      config: {
        params,
      },
    }),

  // GET /brands/{id}
  getById: (id: string) =>
    useFetch({
      queryKey: ["admin-brand", id],
      url: ENDPOINTS.ADMIN.BRANDS.SHOW(id),
    }),

  // POST /brands
  create: async (data: FormData) => {
    const response = await api.post(ENDPOINTS.ADMIN.BRANDS.CREATE, data, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });

    return response.data;
  },

  // POST /brands/{id} using Laravel method spoofing
  update: async (id: string, data: FormData) => {
    data.append("_method", "PATCH");

    const response = await api.post(ENDPOINTS.ADMIN.BRANDS.UPDATE(id), data, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });

    return response.data;
  },

  // DELETE /brands/{id}
  delete: async (id: string) => {
    const response = await api.delete(ENDPOINTS.ADMIN.BRANDS.UPDATE(id));

    return response.data;
  },

  // POST /brands/{id}/toggle-active
  toggleActive: async (id: string, is_active: boolean) => {
    const response = await api.post(ENDPOINTS.ADMIN.BRANDS.TOGGLE_ACTIVE(id), {
      is_active,
    });

    return response.data;
  },
};
