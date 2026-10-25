import { useState } from "react";
import { Eye } from "lucide-react";
import { Link } from "react-router-dom";

import type { IProduct, IVariant } from "../interface";

import AddProductToCart from "./AddProductToCart";
import Image from "./ui/Image";
import ProductRating from "../pages/SingleProduct/ProductInfoPanel/ProductRating";
import ProductVariantSelector from "./ProductVariantSelector";
import HeartButton from "./HeartButton";

interface IProps {
  product: IProduct;
}

const ProductCard = ({ product }: IProps) => {
  /*
   * Variants
   */
  const variants = product.variants ?? [];
  const hasVariants = variants.length > 0;

  const [selectedVariant, setSelectedVariant] = useState<IVariant | undefined>(
    undefined,
  );

  /*
   * Product image
   */
  const primaryImage =
    product.images?.find((image) => image.is_primary)?.url ??
    product.images?.[0]?.url ??
    "";

  /*
   * Price
   *
   * A selected variant's price_override takes priority
   * over the product base price.
   */
  const basePrice = Number(product.price) || 0;

  const variantPrice =
    selectedVariant?.price_override != null
      ? Number(selectedVariant.price_override)
      : null;

  const price = variantPrice ?? basePrice;

  /*
   * Compare price / discount
   */
  const comparePrice =
    product.compare_price !== null ? Number(product.compare_price) : null;

  const hasDiscount = comparePrice !== null && comparePrice > price;

  const discountPercentage = hasDiscount
    ? Math.round(((comparePrice - price) / comparePrice) * 100)
    : 0;

  /*
   * Stock
   *
   * Variant stock is used when a variant is selected.
   * Otherwise fallback to product stock.
   */
  const stock = selectedVariant
    ? Number(selectedVariant.stock_quantity) || 0
    : Number(product.stock_quantity) || 0;

  /*
   * Out of stock
   *
   * If the product has variants but no variant has
   * been selected yet, check whether ALL variants
   * are out of stock.
   */
  const isOutOfStock = hasVariants
    ? selectedVariant
      ? stock <= 0
      : variants.every((variant) => Number(variant.stock_quantity) <= 0)
    : stock <= 0;

  /*
   * Variant selection
   *
   * A product with variants requires a selected variant
   * before adding it to the cart.
   */
  const variantSelectionIncomplete = hasVariants && !selectedVariant;

  /*
   * Rating
   */
  const rating = Number(product.average_rating ?? 0);

  /*
   * Variant handler
   */
  const handleVariantChange = (variant: IVariant | undefined) => {
    setSelectedVariant(variant);
  };

  return (
    <article className="group relative mx-1 flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white p-3 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl sm:mx-2 sm:p-4">
      {/* ───────────────── Discount ───────────────── */}
      {hasDiscount && (
        <span className="absolute left-5 top-5 z-20 rounded-full bg-red-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-md sm:text-xs">
          -{discountPercentage}%
        </span>
      )}

      {/* ───────────────── Wishlist ───────────────── */}
      <HeartButton product={product} />

      {/* ───────────────── Product Image ───────────────── */}
      <Link
        to={`/products/${product.slug}`}
        className="block"
        aria-label={`View ${product.name}`}
      >
        <div className="relative overflow-hidden rounded-xl bg-gray-50">
          <div className="aspect-square w-full">
            <Image
              src={primaryImage}
              alt={product.name}
              className="h-full w-full object-contain transition-transform duration-500 group-hover:scale-105"
            />
          </div>

          {/* Quick View */}
          <div className="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
            <span className="flex translate-y-2 items-center gap-2 rounded-lg bg-white px-3 py-2 text-xs font-semibold text-gray-900 shadow-lg transition-transform duration-300 group-hover:translate-y-0 sm:px-4 sm:text-sm">
              <Eye className="h-4 w-4" />
              Quick View
            </span>
          </div>

          {/* Out of stock */}
          {isOutOfStock && (
            <div className="absolute inset-0 flex items-center justify-center bg-white/70 backdrop-blur-[1px]">
              <span className="rounded-full bg-gray-900 px-3 py-1.5 text-xs font-bold text-white">
                Out of stock
              </span>
            </div>
          )}
        </div>
      </Link>

      {/* ───────────────── Product Info ───────────────── */}
      <div className="mt-auto space-y-2.5 pt-4">
        {/* Rating */}
        {rating > 0 && (
          <ProductRating
            avgRating={product.average_rating as number}
            reviewsCount={product.approved_reviews_count as number}
          />
        )}

        {/* Product name */}
        <Link to={`/products/${product.id}`}>
          <h3 className="line-clamp-2 min-h-10 text-sm font-semibold leading-5 text-gray-900 transition-colors hover:text-primary sm:text-base">
            {product.name}
          </h3>
        </Link>

        {/* Description */}
        <p className="line-clamp-2 text-xs leading-5 text-gray-500 sm:text-sm">
          {product.description}
        </p>

        {/* SKU / Stock */}
        <div className="flex items-center justify-between gap-2">
          <span className="truncate text-[10px] text-gray-400 sm:text-xs">
            SKU: {selectedVariant?.sku ?? product.sku}
          </span>

          {!isOutOfStock && (
            <span className="shrink-0 text-[10px] font-medium text-green-600 sm:text-xs">
              {hasVariants && !selectedVariant ? "In stock" : `${stock} left`}
            </span>
          )}
        </div>

        {/* ───────────────── Variants ───────────────── */}
        {hasVariants && (
          <ProductVariantSelector
            variants={variants}
            onChange={handleVariantChange}
          />
        )}

        {/* ───────────────── Price ───────────────── */}
        <div className="flex items-center justify-between gap-2 pt-1">
          <div className="min-w-0">
            <div className="flex flex-wrap items-baseline gap-1.5 sm:gap-2">
              <span className="text-lg font-black text-gray-900 sm:text-xl">
                {price.toFixed(2)}
              </span>

              <span className="text-xs font-medium text-gray-500">EGP</span>
            </div>

            {hasDiscount && (
              <div className="flex items-center gap-1.5">
                <span className="text-xs text-gray-400 line-through">
                  {comparePrice.toFixed(2)} EGP
                </span>

                <span className="text-[10px] font-bold text-green-600">
                  Save {(comparePrice - price).toFixed(2)}
                </span>
              </div>
            )}
          </div>

          {/* Add to cart */}
          <div className="shrink-0">
            <AddProductToCart
              id={product.id}
              variantId={selectedVariant?.id}
              disabled={isOutOfStock || variantSelectionIncomplete}
            />
          </div>
        </div>

        {/* Variant selection message */}
        {hasVariants && variantSelectionIncomplete && !isOutOfStock && (
          <p className="text-right text-[10px] font-medium text-amber-600 sm:text-xs">
            Select a variant to continue
          </p>
        )}
      </div>

      {/* ───────────────── Hover Border ───────────────── */}
      <div className="pointer-events-none absolute inset-0 rounded-2xl border-2 border-transparent transition-colors duration-300 group-hover:border-primary/20" />
    </article>
  );
};

export default ProductCard;
