import cookieService from "../Service/cookie.service";
import { cartAPI } from "../api/modules/Customer/cart.api";
import type { IAddCartItemPayload } from "../interface";

export const getCartItems = async () => {
  const res = await cartAPI.getCart();

  console.log(res.data);

  return res.data.data;
};

export const addCartItem = async (payload: IAddCartItemPayload) => {
  const res = await cartAPI.addToCart(payload);

  return res.data.data;
};

export const updateCartItem = async (id: string, quantity: number) => {
  const res = await cartAPI.updateCart(id, { quantity });

  return res.data.data;
};

export const removeCartItem = async (id: string) => {
  const res = await cartAPI.deleteItem(id);

  return res.data.data;
};

export const clearCartItems = async () => {
  const res = await cartAPI.clear();

  return res.data.data;
};
