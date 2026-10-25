import { create } from "zustand";
import { persist } from "zustand/middleware";
import type { IProduct } from "../interface";

// Wishlist items don't need quantity — that belongs to the cart.
// We store only the product itself.
interface IWishlistState {
  items: IProduct[];
  addToWishlist: (product: IProduct) => void;
  removeFromWishlist: (productId: string) => void;
  toggleWishlist: (product: IProduct) => void;
  checkIfInWishlist: (productId: string) => boolean;
  clearWishlist: () => void;
}

const useWishlist = create<IWishlistState>()(
  // persist keeps items alive across page refreshes
  persist(
    (set, get) => ({
      items: [],

      addToWishlist: (product: IProduct) => {
        const alreadyAdded = get().items.some((item) => item.id === product.id);
        if (alreadyAdded) return; // idempotent — don't duplicate
        set((state) => ({ items: [...state.items, product] }));
      },

      removeFromWishlist: (productId: string) => {
        set((state) => ({
          items: state.items.filter((item) => item.id !== productId),
        }));
      },

      toggleWishlist: (product: IProduct) => {
        const exists = get().items.some((item) => item.id === product.id);
        if (exists) {
          get().removeFromWishlist(product.id);
        } else {
          get().addToWishlist(product);
        }
      },

      checkIfInWishlist: (productId: string) => {
        return get().items.some((item) => item.id === productId);
      },

      clearWishlist: () => set({ items: [] }),
    }),
    {
      name: "wishlist-storage", // localStorage key
    },
  ),
);

export default useWishlist;
