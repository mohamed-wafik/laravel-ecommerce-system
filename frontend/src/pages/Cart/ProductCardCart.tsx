import { useState } from "react";
import { Heart, Trash2, Plus, Minus, Package } from "lucide-react";
import { Link } from "react-router-dom";
import toast from "react-hot-toast";

import type { IProduct, IVariant } from "../../interface";
import { useCart } from "../../store/useCart";
import useWishlist from "../../store/useWishlist";
import Image from "../../components/ui/Image";
import renderStars from "../../components/ui/renderStars";

interface CartItemCardProps {
  id: string;
  quantity: number;
  priceSnapshot: string;
  product: IProduct;
  variant: IVariant | null;
}

const CartItemCard = ({
  id,
  quantity,
  priceSnapshot,
  product,
  variant,
}: CartItemCardProps) => {
  const [loading, setLoading] = useState(false);

  const { removeItem, updateItem } = useCart();
  const { toggleWishlist, checkIfInWishlist } = useWishlist();

  const isWishlisted = checkIfInWishlist(product.id);
  const unitPrice = Number(priceSnapshot) || 0;
  const lineTotal = unitPrice * quantity;

  const comparePrice =
    product.compare_price !== null ? Number(product.compare_price) : null;

  const hasDiscount = comparePrice !== null && comparePrice > unitPrice;

  const lineSaving = hasDiscount ? (comparePrice - unitPrice) * quantity : 0;

  const discountPercentage = hasDiscount
    ? Math.round(((comparePrice - unitPrice) / comparePrice) * 100)
    : 0;

  const primaryImage =
    product.images?.find((image) => image.is_primary)?.url ??
    product.images?.[0]?.url ??
    "";

  const rating = Number(product.average_rating ?? 0);
  const reviewCount = Number(product.approved_reviews_count ?? 0);

  const stock = variant
    ? Number(variant.stock_quantity) || 0
    : Number(product.stock_quantity) || 0;

  const isOutOfStock = stock <= 0;
  const isMaxQuantity = quantity >= stock;
  const handleQty = async (type: "plus" | "minus") => {
    if (loading) return;

    const nextQuantity = type === "plus" ? quantity + 1 : quantity - 1;

    if (nextQuantity < 1) {
      toast.error("Minimum quantity is 1");
      return;
    }

    if (nextQuantity > stock) {
      toast.error(`Only ${stock} item${stock === 1 ? "" : "s"} available`);
      return;
    }

    setLoading(true);

    try {
      await updateItem(id, nextQuantity);
    } finally {
      setLoading(false);
    }
  };

  const handleRemove = async () => {
    if (loading) return;

    setLoading(true);

    try {
      await removeItem(id);
    } catch {
      setLoading(false);
    }
  };

  const handleSaveForLater = () => {
    toggleWishlist(product);

    toast.success(
      isWishlisted ? "Removed from wishlist" : "Saved to wishlist ❤️",
    );
  };

  return (
    <article
      className={`group relative border-b border-gray-100 p-4 transition-opacity sm:p-5 lg:p-6 ${
        loading ? "pointer-events-none opacity-50" : ""
      }`}
    >
      <div className="flex gap-3 sm:gap-5">
        {/* Image */}
        <Link
          to={`/products/${product.id}`}
          className="shrink-0"
          aria-label={`View ${product.name}`}
        >
          <div className="relative h-24 w-24 overflow-hidden rounded-xl border border-gray-100 bg-gray-50 sm:h-28 sm:w-28 lg:h-32 lg:w-32">
            <Image
              src={primaryImage}
              alt={product.name}
              className="h-full w-full object-contain p-2 transition-transform duration-300 group-hover:scale-105"
            />

            {hasDiscount && (
              <span className="absolute left-1.5 top-1.5 rounded-md bg-red-500 px-1.5 py-0.5 text-[10px] font-bold text-white sm:left-2 sm:top-2">
                -{discountPercentage}%
              </span>
            )}
          </div>
        </Link>

        {/* Content */}
        <div className="min-w-0 flex-1">
          {/* Top */}
          <div className="flex items-start justify-between gap-3">
            <div className="min-w-0">
              <Link
                to={`/products/${product.id}`}
                className="line-clamp-2 text-sm font-semibold leading-snug text-dark transition-colors hover:text-primary sm:text-base"
              >
                {product.name}
              </Link>

              {/* Product SKU */}
              <p className="mt-1 hidden text-[11px] text-gray-400 sm:block">
                SKU: {product.sku}
              </p>

              {/* Variant */}
              {variant && (
                <div className="mt-2 flex flex-wrap items-center gap-2">
                  {/* Color */}
                  {variant.color && (
                    <span className="inline-flex items-center gap-1.5 rounded-md border border-gray-200 bg-gray-50 px-2 py-1 text-[11px] font-medium text-gray-600">
                      <span className="text-gray-400">Color:</span>
                      <span className="font-semibold text-dark">
                        {variant.color}
                      </span>
                    </span>
                  )}

                  {/* Size */}
                  {variant.size && (
                    <span className="inline-flex items-center gap-1.5 rounded-md border border-gray-200 bg-gray-50 px-2 py-1 text-[11px] font-medium text-gray-600">
                      <span className="text-gray-400">Size:</span>
                      <span className="font-semibold text-dark">
                        {variant.size}
                      </span>
                    </span>
                  )}
                </div>
              )}

              {/* Variant SKU */}
              {variant?.sku && (
                <p className="mt-1.5 text-[10px] text-gray-400">
                  Variant SKU: {variant.sku}
                </p>
              )}
            </div>

            {/* Desktop price */}
            <div className="hidden shrink-0 text-right sm:block">
              <p className="text-base font-black text-dark lg:text-lg">
                {lineTotal.toFixed(2)} EGP
              </p>

              {hasDiscount && (
                <p className="text-xs text-gray-400 line-through">
                  {(comparePrice * quantity).toFixed(2)} EGP
                </p>
              )}
            </div>
          </div>

          {/* Rating */}
          <div className="mt-2 flex items-center gap-1.5">
            {renderStars(rating)}

            {reviewCount > 0 && (
              <span className="text-[11px] text-gray-400">({reviewCount})</span>
            )}
          </div>

          {/* Mobile price */}
          <div className="mt-2 sm:hidden">
            <div className="flex items-center gap-2">
              <span className="text-base font-black text-dark">
                {lineTotal.toFixed(2)} EGP
              </span>

              {hasDiscount && (
                <span className="text-xs text-gray-400 line-through">
                  {(comparePrice * quantity).toFixed(2)} EGP
                </span>
              )}
            </div>
          </div>

          {/* Variant Price */}
          {variant?.price_override !== null &&
            variant?.price_override !== undefined && (
              <div className="mt-2">
                <span className="inline-flex items-center rounded-md bg-primary/5 px-2 py-1 text-[10px] font-semibold text-primary">
                  Variant price: {Number(variant.price_override).toFixed(2)} EGP
                </span>
              </div>
            )}

          {/* Stock */}
          <div className="mt-2 flex items-center gap-1.5">
            <Package className="h-3.5 w-3.5 text-gray-400" />

            <span
              className={`text-[11px] font-medium ${
                isOutOfStock
                  ? "text-red-500"
                  : isMaxQuantity
                    ? "text-orange-500"
                    : "text-green-600"
              }`}
            >
              {isOutOfStock
                ? "Out of stock"
                : isMaxQuantity
                  ? "Maximum available"
                  : `${stock} available`}
            </span>
          </div>

          {/* Bottom controls */}
          <div className="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            {/* Quantity */}
            <div className="flex items-center justify-between gap-3 sm:justify-start">
              <div className="flex items-center overflow-hidden rounded-lg border border-gray-200">
                <button
                  type="button"
                  onClick={() => handleQty("minus")}
                  disabled={loading || quantity <= 1}
                  aria-label="Decrease quantity"
                  className="flex h-8 w-8 items-center justify-center text-gray-500 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40"
                >
                  <Minus className="h-3.5 w-3.5" />
                </button>

                <span className="flex h-8 min-w-9 items-center justify-center border-x border-gray-200 px-2 text-sm font-bold text-dark">
                  {quantity}
                </span>

                <button
                  type="button"
                  onClick={() => handleQty("plus")}
                  disabled={loading || isMaxQuantity}
                  aria-label="Increase quantity"
                  className="flex h-8 w-8 items-center justify-center text-gray-500 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40"
                >
                  <Plus className="h-3.5 w-3.5" />
                </button>
              </div>

              {/* Mobile saving */}
              {lineSaving > 0 && (
                <span className="text-[11px] font-semibold text-green-600 sm:hidden">
                  Save {lineSaving.toFixed(2)} EGP
                </span>
              )}
            </div>

            {/* Actions */}
            <div className="flex items-center gap-4">
              <button
                type="button"
                onClick={handleSaveForLater}
                className={`flex items-center gap-1.5 text-xs font-medium transition-colors ${
                  isWishlisted
                    ? "text-primary"
                    : "text-gray-400 hover:text-primary"
                }`}
              >
                <Heart
                  className={`h-3.5 w-3.5 ${
                    isWishlisted ? "fill-primary" : ""
                  }`}
                />

                <span>{isWishlisted ? "Saved" : "Save"}</span>
              </button>

              <span className="h-3 w-px bg-gray-200" />

              <button
                type="button"
                onClick={handleRemove}
                className="flex items-center gap-1.5 text-xs font-medium text-gray-400 transition-colors hover:text-red-500"
              >
                <Trash2 className="h-3.5 w-3.5" />

                <span>Remove</span>
              </button>
            </div>
          </div>

          {/* Saving */}
          {lineSaving > 0 && (
            <div className="mt-2 hidden sm:block">
              <span className="inline-flex rounded-full border border-green-200 bg-green-50 px-2.5 py-1 text-[11px] font-semibold text-green-600">
                You save {lineSaving.toFixed(2)} EGP
              </span>
            </div>
          )}
        </div>
      </div>
    </article>
  );
};

export default CartItemCard;
