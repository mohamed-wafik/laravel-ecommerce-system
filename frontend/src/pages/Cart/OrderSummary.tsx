import { useMemo, useState } from "react";
import { Lock, ChevronRight, Truck } from "lucide-react";

import Row from "./Row";

import Button from "../../components/ui/Button";
import Checkout from "./Checkout/Checkout";
import { useSettings } from "../../hook/useSettings";
import OrderSummarySkeleton from "./OrderSummarySkeleton";

interface OrderSummaryProps {
  subtotal: number;
  productDiscount: number;

  deliveryType?: "standard" | "express";
}

const OrderSummary = ({
  subtotal,
  productDiscount,
  deliveryType = "standard",
}: OrderSummaryProps) => {
  const [isOpen, setIsOpen] = useState(false);

  const { data: settings, isLoading } = useSettings();

  const currency = settings?.currency ?? "USD";

  const formatPrice = (value: number) => `${value.toFixed(2)} ${currency}`;

  const discountedSubtotal = useMemo(
    () => Math.max(subtotal - productDiscount, 0),
    [subtotal, productDiscount],
  );

  const tax = useMemo(() => {
    if (!settings?.tax_rate) return 0;

    return discountedSubtotal * settings.tax_rate;
  }, [discountedSubtotal, settings?.tax_rate]);

  const deliveryFee = useMemo(() => {
    if (!settings) return 0;

    // Free delivery threshold reached
    if (
      settings.free_delivery_threshold > 0 &&
      discountedSubtotal >= settings.free_delivery_threshold
    ) {
      return 0;
    }

    return deliveryType === "express"
      ? settings.delivery_fee_express
      : settings.delivery_fee_standard;
  }, [settings, discountedSubtotal, deliveryType]);

  /*
   * This is only a frontend display calculation.
   * Laravel CheckoutService should remain the source of truth.
   */
  const displayTotal = useMemo(
    () => discountedSubtotal + tax + deliveryFee,
    [discountedSubtotal, tax, deliveryFee],
  );

  if (isLoading) {
    return <OrderSummarySkeleton />;
  }

  return (
    <>
      <div className="w-full lg:w-1/3">
        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5 lg:p-6 lg:sticky lg:top-20">
          {/* Header */}
          <div className="flex items-center gap-3 mb-5">
            <div className="w-1 h-5 bg-primary rounded-full shrink-0" />

            <h2 className="text-base sm:text-lg font-extrabold text-dark">
              Order Summary
            </h2>
          </div>

          {/* Price rows */}
          <div className="space-y-3 mb-5">
            <Row label="Subtotal" value={formatPrice(subtotal)} />

            {productDiscount > 0 && (
              <Row
                label="Product discounts"
                value={`-${formatPrice(productDiscount)}`}
                green
              />
            )}

            {/* Delivery */}
            <Row
              label={
                deliveryType === "express"
                  ? "Express delivery"
                  : "Standard delivery"
              }
              value={deliveryFee === 0 ? "FREE" : formatPrice(deliveryFee)}
              green={deliveryFee === 0}
            />

            {/* Tax */}
            {tax > 0 && (
              <Row
                label={`Tax (${settings?.tax_rate ? settings.tax_rate * 100 : 0}%)`}
                value={formatPrice(tax)}
              />
            )}

            <div className="h-px bg-gray-100" />

            {/* Total */}
            <Row label="Total" value={formatPrice(displayTotal)} bold />
          </div>

          {/* Free delivery message */}
          {settings?.free_delivery_threshold > 0 &&
            discountedSubtotal < settings.free_delivery_threshold && (
              <div className="flex items-start gap-2 p-3 mb-4 rounded-xl bg-gray-50">
                <Truck className="w-4 h-4 text-primary shrink-0 mt-0.5" />

                <p className="text-xs leading-relaxed text-gray-500">
                  Add{" "}
                  <span className="font-bold text-dark">
                    {formatPrice(
                      settings.free_delivery_threshold - discountedSubtotal,
                    )}
                  </span>{" "}
                  more to get free standard delivery.
                </p>
              </div>
            )}

          {/* Free delivery achieved */}
          {settings?.free_delivery_threshold > 0 &&
            discountedSubtotal >= settings.free_delivery_threshold && (
              <div className="flex items-center gap-2 p-3 mb-4 rounded-xl bg-green-50">
                <Truck className="w-4 h-4 text-green-600 shrink-0" />

                <p className="text-xs font-semibold text-green-600">
                  You unlocked free standard delivery!
                </p>
              </div>
            )}

          {/* Checkout CTA */}
          <Button
            onClick={() => setIsOpen(true)}
            className="w-full flex items-center justify-center gap-2 bg-primary text-white py-3 sm:py-3.5 rounded-xl text-sm font-bold hover:opacity-90 active:scale-[0.98] transition-all mb-4"
          >
            Proceed to Checkout
            <ChevronRight className="w-4 h-4" />
          </Button>

          {/* Security note */}
          <div className="flex items-center justify-center gap-1.5 text-[11px] sm:text-xs text-gray-400 mb-5">
            <Lock className="w-3.5 h-3.5 shrink-0" />

            <span>Your data is securely encrypted</span>
          </div>

          {/* Payment methods */}
          <div className="border-t border-gray-100 pt-5">
            <p className="text-xs text-gray-400 mb-3 font-medium">We accept</p>

            <div className="grid grid-cols-4 gap-2">
              {["Visa", "Mastercard", "PayPal", "Amex"].map((payment) => (
                <div
                  key={payment}
                  className="flex items-center justify-center h-8 px-2 rounded-lg bg-gray-50 text-[10px] sm:text-xs font-semibold text-gray-500"
                >
                  {payment}
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>

      <Checkout isOpen={isOpen} setIsOpted={setIsOpen} />
    </>
  );
};

export default OrderSummary;
