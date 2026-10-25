import type { IAddCartItemPayload } from "../../../interface";

import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";

export const cartAPI = {
  getCart: () => api.get(ENDPOINTS.CUSTOMER.CART.ALL),

  addToCart: (data: IAddCartItemPayload) =>
    api.post(ENDPOINTS.CUSTOMER.CART.ADD, data),

  updateCart: (id: string, data: { quantity: number }) =>
    api.patch(ENDPOINTS.CUSTOMER.CART.UPDATE(id), data),

  deleteItem: (id: string) => api.delete(ENDPOINTS.CUSTOMER.CART.DELETE(id)),

  clear: () => api.delete(ENDPOINTS.CUSTOMER.CART.CLEAR),
};
