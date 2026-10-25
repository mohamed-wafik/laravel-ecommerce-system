import useFetch from "../../../hook/useAuthentienticatedQuery";
import type { ICategoryFilters } from "../../../hook/useCategoryFilters";
import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";

export const adminCategoryAPI = {
  /**
   * Get paginated categories with filters
   */
  getAll: (params?: ICategoryFilters) =>
    useFetch({
      queryKey: [
        "admin-categories",
        String(params?.page),
        String(params?.per_page),
        params?.search ?? "",
        params?.parent_id ?? "",
        params?.is_active ?? "",
      ],
      url: ENDPOINTS.ADMIN.CATEGORIES.ALL,
      config: {
        params,
      },
    }),

  /**
   * Get complete category tree
   */
  getTree: async () => {
    return api.get(ENDPOINTS.ADMIN.CATEGORIES.TREE);
  },

  /**
   * Get single category
   */
  getById: async (id: string) => {
    return api.get(`${ENDPOINTS.ADMIN.CATEGORIES.ALL}/${id}`);
  },

  /**
   * Create category
   *
   * Uses FormData because category supports image upload.
   */
  create: async (formData: FormData) => {
    return api.post(ENDPOINTS.ADMIN.CATEGORIES.ALL, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
  },

  /**
   * Update category
   *
   * Laravel receives the multipart request using
   * method spoofing because the request contains a file.
   */
  update: async (id: string, formData: FormData) => {
    formData.append("_method", "PATCH");

    return api.post(`${ENDPOINTS.ADMIN.CATEGORIES.ALL}/${id}`, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
  },

  /**
   * Delete category
   */
  delete: async (id: string) => {
    return api.delete(`${ENDPOINTS.ADMIN.CATEGORIES.ALL}/${id}`);
  },

  /**
   * Toggle category active status
   */
  toggleActive: async (id: string) => {
    return api.patch(`${ENDPOINTS.ADMIN.CATEGORIES.ALL}/${id}/toggle-active`);
  },

  /**
   * Reorder categories
   */
  reorder: async (
    categories: Array<{
      id: string;
      sort_order: number;
    }>,
  ) => {
    return api.post(ENDPOINTS.ADMIN.CATEGORIES.REORDER, {
      categories,
    });
  },
};
