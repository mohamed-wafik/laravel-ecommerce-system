import { ShoppingCart, X, Package } from "lucide-react";
import { useState } from "react";
import { Link } from "react-router-dom";

import type { IProduct, IVariant } from "../../interface";
import useWishlist from "../../store/useWishlist";
import { useCart } from "../../store/useCart";

import Image from "../../components/ui/Image";
import ProductVariantSelector from "../../components/ProductVariantSelector";
import renderStars from "../../components/ui/renderStars";

interface WishlistCardProps {
  product: IProduct;
}

const WishlistCard = ({ product }: WishlistCardProps) => {
  const { removeFromWishlist } = useWishlist();
  const { addItem, isLoadingCart } = useCart();

  const [movingToCart, setMovingToCart] = useState(false);
  const [selectedVariant, setSelectedVariant] = useState<IVariant | undefined>(
    undefined,
  );

  /*
   * ─────────────────────────────────────────────
   * Variants
   * ─────────────────────────────────────────────
   */
  const variants = product.variants ?? [];
  const hasVariants = variants.length > 0;

  /*
   * ─────────────────────────────────────────────
   * Product image
   * ─────────────────────────────────────────────
   */
  const primaryImage =
    product.images?.find((image) => image.is_primary)?.url ??
    product.images?.[0]?.url ??
    "";

  /*
   * ─────────────────────────────────────────────
   * Price
   * ─────────────────────────────────────────────
   *
   * Variant price overrides product price when available.
   */
  const basePrice = Number(product.price) || 0;

  const variantPrice =
    selectedVariant?.price_override !== null &&
    selectedVariant?.price_override !== undefined
      ? Number(selectedVariant.price_override)
      : null;

  const price = variantPrice !== null ? variantPrice : basePrice;

  const comparePrice =
    product.compare_price !== null ? Number(product.compare_price) : null;

  const hasDiscount = comparePrice !== null && comparePrice > price;

  const discountPercentage = hasDiscount
    ? Math.round(((comparePrice - price) / comparePrice) * 100)
    : 0;

  /*
   * ─────────────────────────────────────────────
   * Stock
   * ─────────────────────────────────────────────
   */
  const productStock = Number(product.stock_quantity) || 0;

  const stock = selectedVariant
    ? Number(selectedVariant.stock_quantity) || 0
    : productStock;

  const isOutOfStock = stock <= 0;

  /*
   * ─────────────────────────────────────────────
   * Variant selection
   * ─────────────────────────────────────────────
   *
   * If the product has variants, the user must
   * select a valid variant before adding it.
   */
  const variantSelectionRequired = hasVariants && !selectedVariant;

  /*
   * ─────────────────────────────────────────────
   * Rating
   * ─────────────────────────────────────────────
   */
  const avgRating = Math.floor(Number(product.average_rating ?? 0));

  const reviewsCount = Number(product.approved_reviews_count ?? 0);

  /*
   * ─────────────────────────────────────────────
   * Variant change
   * ─────────────────────────────────────────────
   */
  const handleVariantChange = (variant: IVariant | undefined) => {
    setSelectedVariant(variant);
  };

  /*
   * ─────────────────────────────────────────────
   * Move to cart
   * ─────────────────────────────────────────────
   */
  const handleMoveToCart = async () => {
    if (
      movingToCart ||
      isLoadingCart ||
      isOutOfStock ||
      variantSelectionRequired
    ) {
      return;
    }

    setMovingToCart(true);

    try {
      const success = await addItem({
        product_id: product.id,
        quantity: 1,
        ...(selectedVariant && {
          variant_id: selectedVariant.id,
        }),
      });

      if (success) {
        removeFromWishlist(product.id);
      }
    } finally {
      setMovingToCart(false);
    }
  };

  /*
   * ─────────────────────────────────────────────
   * Remove from wishlist
   * ─────────────────────────────────────────────
   */
  const handleRemove = () => {
    removeFromWishlist(product.id);
  };

  return (
    <article className="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white transition-all duration-300 hover:-translate-y-1 hover:border-gray-200 hover:shadow-lg">
      {/* ───────────────── Remove ───────────────── */}
      <button
        type="button"
        onClick={handleRemove}
        aria-label={`Remove ${product.name} from wishlist`}
        className="absolute right-3 top-3 z-20 flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 bg-white/95 text-gray-400 shadow-sm backdrop-blur-sm transition-all hover:border-red-200 hover:bg-red-50 hover:text-red-500"
      >
        <X className="h-3.5 w-3.5" />
      </button>

      {/* ───────────────── Discount ───────────────── */}
      {hasDiscount && (
        <span className="absolute left-3 top-3 z-20 rounded-full bg-primary px-2.5 py-1 text-[10px] font-bold text-white shadow-sm sm:text-xs">
          -{discountPercentage}%
        </span>
      )}

      {/* ───────────────── Image ───────────────── */}
      <Link
        to={`/products/${product.slug ?? product.id}`}
        aria-label={`View ${product.name}`}
        className="block"
      >
        <div className="relative flex aspect-square items-center justify-center overflow-hidden bg-gray-50">
          <Image
            src={primaryImage}
            alt={product.name}
            className="h-full w-full object-contain transition-transform duration-500 group-hover:scale-105"
          />

          {/* Out of stock overlay */}
          {isOutOfStock && (
            <div className="absolute inset-0 flex items-center justify-center bg-white/65 backdrop-blur-[1px]">
              <span className="rounded-full bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white">
                Out of Stock
              </span>
            </div>
          )}
        </div>
      </Link>

      {/* ───────────────── Content ───────────────── */}
      <div className="p-4 sm:p-5">
        {/* Rating */}
        {avgRating > 0 && (
          <div className="mb-2 flex items-center gap-1.5">
            {renderStars(avgRating)}

            {reviewsCount > 0 && (
              <span className="text-[11px] text-gray-400">
                ({reviewsCount})
              </span>
            )}
          </div>
        )}

        {/* Product name */}
        <Link to={`/products/${product.slug ?? product.id}`} className="block">
          <h3 className="mb-3 line-clamp-2 min-h-10 text-sm font-semibold leading-5 text-dark transition-colors hover:text-primary">
            {product.name}
          </h3>
        </Link>

        {/* ───────────────── Variants ───────────────── */}
        {hasVariants && (
          <div className="mb-4">
            <ProductVariantSelector
              variants={variants}
              onChange={handleVariantChange}
            />
          </div>
        )}

        {/* ───────────────── Price ───────────────── */}
        <div className="mb-3 flex flex-wrap items-baseline gap-2">
          <span className="text-lg font-black text-dark sm:text-xl">
            {price.toFixed(2)}
          </span>

          <span className="text-xs font-medium text-gray-500">EGP</span>

          {hasDiscount && (
            <span className="text-xs text-gray-400 line-through">
              {comparePrice.toFixed(2)} EGP
            </span>
          )}
        </div>

        {/* ───────────────── Stock ───────────────── */}
        <div className="mb-4">
          <span
            className={`inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-semibold sm:text-xs ${
              isOutOfStock
                ? "bg-red-50 text-red-600"
                : "bg-green-50 text-green-700"
            }`}
          >
            <Package className="h-3 w-3" />

            {isOutOfStock ? "Out of Stock" : `In Stock · ${stock}`}
          </span>
        </div>

        {/* Variant warning */}
        {variantSelectionRequired && (
          <p className="mb-3 text-center text-[11px] font-medium text-amber-600">
            Please select a variant first
          </p>
        )}

        {/* ───────────────── Move to cart ───────────────── */}
        <button
          type="button"
          onClick={handleMoveToCart}
          disabled={
            isOutOfStock ||
            movingToCart ||
            isLoadingCart ||
            variantSelectionRequired
          }
          className="flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white transition-all hover:opacity-90 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-40"
        >
          {movingToCart ? (
            <span className="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent" />
          ) : (
            <ShoppingCart className="h-4 w-4" />
          )}

          <span>{movingToCart ? "Moving..." : "Move to Cart"}</span>
        </button>
      </div>
    </article>
  );
};

export default WishlistCard;
