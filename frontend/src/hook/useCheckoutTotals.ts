import { useMemo } from "react";
import type { ICartItem } from "../interface";

const SHIPPING_RATES: Record<"standard" | "express", number> = {
  standard: 0,
  express: 15,
};

const formatCurrency = (amount: number) =>
  new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
  }).format(amount);

export function useCheckoutTotals(
  cartItems: ICartItem[] | undefined,
  deliveryMethod: "standard" | "express",
) {
  return useMemo(() => {
    const subTotalValue = (cartItems ?? []).reduce(
      (sum, item) => sum + item.price_snapshot * item.quantity,
      0,
    );

    const shippingCost = SHIPPING_RATES[deliveryMethod] ?? 0;
    const totalValue = subTotalValue + shippingCost;

    return {
      subTotalValue,
      totalValue,
      subTotal: formatCurrency(subTotalValue),
      total: formatCurrency(totalValue),
    };
  }, [cartItems, deliveryMethod]);
}
