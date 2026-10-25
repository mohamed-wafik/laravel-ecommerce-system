import useFetch from "../../../hook/useAuthentienticatedQuery";
import { ENDPOINTS } from "../../endpoints";

export const categoryAPI = {
  /**
   * Get all categories
   */
  getAll: (params?: { page?: number }) =>
    useFetch({
      queryKey: ["categories", `page=${JSON.stringify(params)}`],
      url: ENDPOINTS.GUEST.CATEGORIES.ALL,
      config: { params },
    }),
  /**
   * Get single category by slug
   */
  getBySlug: (slug: string) =>
    useFetch({
      queryKey: ["category", slug],
      url: ENDPOINTS.GUEST.CATEGORIES.SHOW(slug),
    }),
};
