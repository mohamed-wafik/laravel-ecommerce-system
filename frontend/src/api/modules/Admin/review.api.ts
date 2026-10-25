import useFetch from "../../../hook/useAuthentienticatedQuery";
import type { IReviewFilters } from "../../../hook/useReviewFilters";

import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";

export const adminReviewAPI = {
  list: (params?: IReviewFilters) =>
    useFetch({
      queryKey: [
        "reviews",
        String(params?.page),
        String(params?.per_page),
        String(params?.is_approved),
        String(params?.product_id),
        String(params?.rating),
      ],
      url: ENDPOINTS.ADMIN.REVIEWS.ALL,
      config: {
        params,
      },
    }),

  getById: (id: string) =>
    useFetch({
      queryKey: ["review", id],
      url: ENDPOINTS.ADMIN.REVIEWS.SHOW(id),
    }),

  approve: (id: string) => api.post(ENDPOINTS.ADMIN.REVIEWS.APPROVE(id)),

  reject: (id: string) => api.post(ENDPOINTS.ADMIN.REVIEWS.REJECT(id)),

  delete: (id: string) => api.delete(ENDPOINTS.ADMIN.REVIEWS.DELETE(id)),
};
