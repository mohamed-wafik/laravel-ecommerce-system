import { create } from "zustand";
import toast from "react-hot-toast";

import {
  addCartItem,
  clearCartItems,
  getCartItems,
  removeCartItem,
  updateCartItem,
} from "../Service/cart.service";
import type { IAddCartItemPayload, IProduct, IVariant } from "../interface";

interface ICartState {
  cartItems: ICartItem[];
  countItems: number;
  subtotal: number;
  total: number;
  discount: number;
  isLoadingCart: boolean;

  fetchCart: () => Promise<void>;
  addItem: (payload: IAddCartItemPayload) => Promise<boolean>;
  updateItem: (id: string, quantity: number) => Promise<void>;
  removeItem: (id: string) => Promise<void>;
  clear: () => Promise<void>;
  getSummary: (items: ICartItem[]) => void;
}

export interface ICartItem {
  id: string;
  cart_id: string;
  product_id: string;
  variant_id: string | null;
  quantity: number;
  price_snapshot: string;
  product: IProduct;
  variant: IVariant | null;
}

export const useCart = create<ICartState>((set, get) => ({
  cartItems: [],
  countItems: 0,
  subtotal: 0,
  total: 0,
  discount: 0,
  isLoadingCart: false,

  /**
   * Calculate cart summary from cart items.
   *
   * total:
   *   Current/snapshot price × quantity
   *
   * subtotal:
   *   Original/compare price × quantity when available,
   *   otherwise current price × quantity
   *
   * discount:
   *   subtotal - total
   */
  getSummary: (items) => {
    let countItems = 0;
    let subtotal = 0;
    let total = 0;

    items.forEach((item) => {
      const quantity = Number(item.quantity) || 0;
      const price = Number(item.price_snapshot) || 0;
      const comparePrice = Number(item.product?.compare_price);

      countItems += quantity;

      // Current selling price
      total += price * quantity;

      // Original price if available
      subtotal +=
        item.product?.compare_price !== null &&
        item.product?.compare_price !== undefined &&
        !Number.isNaN(comparePrice)
          ? comparePrice * quantity
          : price * quantity;
    });

    const discount = Math.max(subtotal - total, 0);

    set({
      countItems,
      subtotal,
      total,
      discount,
    });
  },

  /**
   * Fetch cart from API
   */
  fetchCart: async () => {
    set({ isLoadingCart: true });

    try {
      const cart = await getCartItems();
      console.log(cart);

      const items = cart?.items ?? [];

      set({
        cartItems: items,
      });

      get().getSummary(items);
    } catch (error) {
      console.error("Error fetching cart:", error);
      toast.error("Failed to load cart items");
    } finally {
      set({ isLoadingCart: false });
    }
  },

  /**
   * Add product to cart
   */
  addItem: async (payload: IAddCartItemPayload) => {
    try {
      await addCartItem(payload);

      await get().fetchCart();

      toast.success("Item added to cart");

      return true;
    } catch (error) {
      console.error("Error adding item:", error);

      toast.error("Failed to add item to cart");

      return false;
    }
  },

  /**
   * Update cart item quantity
   */
  updateItem: async (id, quantity) => {
    try {
      if (quantity <= 0) {
        await get().removeItem(id);
        return;
      }

      await updateCartItem(id, quantity);

      await get().fetchCart();

      toast.success("Cart updated");
    } catch (error) {
      console.error("Error updating item:", error);

      toast.error("Failed to update cart item");
    }
  },

  /**
   * Remove cart item
   */
  removeItem: async (id: string) => {
    try {
      await removeCartItem(id);

      await get().fetchCart();

      toast.success("Item removed from cart");
    } catch (error) {
      console.error("Error removing item:", error);

      toast.error("Failed to remove item");
    }
  },

  /**
   * Clear entire cart
   */
  clear: async () => {
    try {
      await clearCartItems();

      set({
        cartItems: [],
        countItems: 0,
        subtotal: 0,
        total: 0,
        discount: 0,
      });

      toast.success("Cart cleared");
    } catch (error) {
      console.error("Error clearing cart:", error);

      toast.error("Failed to clear cart");
    }
  },
}));
