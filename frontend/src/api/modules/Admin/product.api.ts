import useFetch from "../../../hook/useAuthentienticatedQuery";
import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";
import type { IProductFilters } from "../../../hook/useProductFilters";

export const adminProductAPI = {
  list: (params?: IProductFilters) => {
    return useFetch({
      queryKey: [
        "admin-products",
        `${params?.brand_id}`,
        `${params?.category_id}`,
        `${params?.is_active}`,
        `${params?.min_price}`,
        `${params?.max_price}`,
        `${params?.page}`,
        `${params?.per_page}`,
        `${params?.search}`,
        `${params?.trashed}`,
      ],
      url: `${ENDPOINTS.ADMIN.PRODUCTS.ALL}`,

      config: { params },
    });
  },

  getById: (id: string) =>
    useFetch({
      queryKey: ["product", id],
      url: ENDPOINTS.ADMIN.PRODUCTS.SHOW(id),
    }),

  create: (data: FormData) =>
    api.post(ENDPOINTS.ADMIN.PRODUCTS.CREATE, data, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    }),

  update: (id: string, data: FormData) => {
    data.append("_method", "PUT");
    return api.post(ENDPOINTS.ADMIN.PRODUCTS.UPDATE(id), data, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
  },

  delete: (id: string) => api.delete(ENDPOINTS.ADMIN.PRODUCTS.DELETE(id)),

  lowStock: () => api.get(ENDPOINTS.ADMIN.PRODUCTS.LOW_STOCK),

  toggleActive: (id: string) =>
    api.post(ENDPOINTS.ADMIN.PRODUCTS.TOGGLE_ACTIVE(id)),

  adjustStock: (
    id: string,
    data: {
      quantity: number;
      type: "increase" | "decrease";
      reason?: string;
    },
  ) => api.post(ENDPOINTS.ADMIN.PRODUCTS.ADJUST_STOCK(id), data),
};
