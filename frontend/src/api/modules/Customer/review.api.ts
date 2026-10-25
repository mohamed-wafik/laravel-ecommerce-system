import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";
import type { IReviewCreate } from "../../type";
export const reviewAPI = {
  create: (productId: string, data: IReviewCreate) =>
    api.post(ENDPOINTS.CUSTOMER.REVIEWS.CREATE(productId), data),
};
