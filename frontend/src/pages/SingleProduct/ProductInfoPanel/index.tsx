import { useEffect, useMemo, useState } from "react";
import type { IProduct, IVariant } from "../../../interface";
import { useCart } from "../../../store/useCart";

import ProductBadges from "./ProductBadges";
import ProductRating from "./ProductRating";
import ProductPrice from "./ProductPrice";
import TrustBadges from "./TrustBadges";
import ProductActions from "./ProductActions";
import QuantitySelector from "./QuantitySelector";
import VariantSelector from "./VariantSelector";
import toast from "react-hot-toast";

interface ProductInfoPanelProps {
  product: IProduct;
}

const ProductInfoPanel = ({ product }: ProductInfoPanelProps) => {
  const { addItem, isLoadingCart } = useCart();
  const [quantity, setQuantity] = useState(1);

  /*
   * Variants
   */
  const variants = product.variants ?? [];
  const hasVariants = variants.length > 0;

  const uniqueColors = useMemo(
    () => Array.from(new Set(variants.map((v) => v.color).filter(Boolean))),
    [variants],
  );

  const uniqueSizes = useMemo(
    () => Array.from(new Set(variants.map((v) => v.size).filter(Boolean))),
    [variants],
  );

  const [selectedColor, setSelectedColor] = useState<string | null>(
    uniqueColors.length === 1 ? uniqueColors[0] : null,
  );
  const [selectedSize, setSelectedSize] = useState<string | null>(
    uniqueSizes.length === 1 ? uniqueSizes[0] : null,
  );

  const sizesForSelectedColor = useMemo(() => {
    const pool = selectedColor
      ? variants.filter((v) => v.color === selectedColor)
      : variants;
    return Array.from(new Set(pool.map((v) => v.size).filter(Boolean)));
  }, [variants, selectedColor]);

  const colorsForSelectedSize = useMemo(() => {
    const pool = selectedSize
      ? variants.filter((v) => v.size === selectedSize)
      : variants;
    return Array.from(new Set(pool.map((v) => v.color).filter(Boolean)));
  }, [variants, selectedSize]);

  // Drop the size if it no longer pairs with the newly chosen color.
  useEffect(() => {
    if (selectedSize && !sizesForSelectedColor.includes(selectedSize)) {
      setSelectedSize(null);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [selectedColor]);

  // Drop the color if it no longer pairs with the newly chosen size.
  useEffect(() => {
    if (selectedColor && !colorsForSelectedSize.includes(selectedColor)) {
      setSelectedColor(null);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [selectedSize]);

  const selectedVariant: IVariant | undefined = useMemo(() => {
    if (!hasVariants) return undefined;

    return variants.find((v) => {
      const colorMatches =
        uniqueColors.length === 0 || v.color === selectedColor;
      const sizeMatches = uniqueSizes.length === 0 || v.size === selectedSize;
      return colorMatches && sizeMatches;
    });
  }, [
    hasVariants,
    variants,
    uniqueColors,
    uniqueSizes,
    selectedColor,
    selectedSize,
  ]);

  const variantSelectionIncomplete =
    hasVariants &&
    ((uniqueColors.length > 0 && !selectedColor) ||
      (uniqueSizes.length > 0 && !selectedSize) ||
      !selectedVariant);

  // Reset quantity any time the matched variant changes, so it never
  // carries over a quantity that exceeds the new variant's stock.
  useEffect(() => {
    setQuantity(1);
  }, [selectedVariant?.id]);

  /*
   * Prices — a selected variant's price_override wins when present.
   */
  const basePrice = Number(product.price) || 0;

  const variantPrice =
    selectedVariant?.price_override !== null &&
    selectedVariant?.price_override !== undefined
      ? Number(selectedVariant.price_override)
      : null;

  const originalPrice = variantPrice ?? basePrice;

  const comparePrice = product.compare_price
    ? Number(product.compare_price)
    : null;

  const hasDiscount = comparePrice !== null && comparePrice > originalPrice;

  const discountPercentage = useMemo(() => {
    if (!hasDiscount || !comparePrice) return null;

    return Math.round(((comparePrice - originalPrice) / comparePrice) * 100);
  }, [hasDiscount, comparePrice, originalPrice]);

  const averageRating = Number(product.average_rating ?? 0);
  const reviewsCount = Number(product.approved_reviews_count ?? 0);

  /*
   * Stock — reflects the selected variant once one is chosen,
   * otherwise falls back to the product-level total.
   */
  const stock = selectedVariant
    ? Number(selectedVariant.stock_quantity) || 0
    : Number(product.stock_quantity) || 0;

  const outOfStock = hasVariants
    ? selectedVariant
      ? stock <= 0
      : variants.every((v) => (Number(v.stock_quantity) || 0) <= 0)
    : stock <= 0;

  const addToCartDisabled = outOfStock || variantSelectionIncomplete;

  const addCart = async () => {
    if (!selectedVariant && hasVariants) {
      toast.error("Please select a variant before adding to cart.");
      return;
    }
    await addItem({
      product_id: product.id,
      quantity,
      variant_id: selectedVariant?.id,
    });
  };

  return (
    <div className="p-6 sm:p-8 lg:p-12 flex flex-col justify-between border-t lg:border-t-0 lg:border-l border-gray-100">
      <div>
        {/* Badges */}
        <ProductBadges categoryName={product.category?.name} stock={stock} />

        {/* Product name */}
        <h1 className="text-3xl sm:text-4xl font-extrabold text-dark leading-tight mb-3 capitalize">
          {product.name}
        </h1>

        {/* Rating */}
        <ProductRating avgRating={averageRating} reviewsCount={reviewsCount} />

        <div className="h-px bg-gray-100 mb-6" />

        {/* Price */}
        <ProductPrice
          price={originalPrice}
          comparePrice={comparePrice}
          discount={discountPercentage}
        />

        {/* Description */}
        <p className="text-sm text-gray-500 leading-relaxed mb-8">
          {product.description}
        </p>

        <div className="h-px bg-gray-100 mb-6" />

        {/* Variants */}
        {hasVariants && (
          <VariantSelector
            variants={variants}
            uniqueColors={uniqueColors}
            uniqueSizes={uniqueSizes}
            selectedColor={selectedColor}
            selectedSize={selectedSize}
            onSelectColor={setSelectedColor}
            onSelectSize={setSelectedSize}
          />
        )}

        {hasVariants && variantSelectionIncomplete && !outOfStock && (
          <p className="mb-4 text-xs font-medium text-amber-600">
            Select{" "}
            {uniqueColors.length > 0 && !selectedColor ? "a color" : "a size"}{" "}
            to continue
          </p>
        )}

        {/* Quantity */}
        <QuantitySelector
          quantity={quantity}
          onDecrease={() => setQuantity((q) => Math.max(1, q - 1))}
          onIncrease={() => setQuantity((q) => Math.min(stock, q + 1))}
          disabled={addToCartDisabled}
        />

        {/* Actions */}
        <ProductActions
          product={product}
          quantity={quantity}
          isLoadingCart={isLoadingCart}
          onAddToCart={addCart}
          disabled={addToCartDisabled}
        />
      </div>

      {/* Trust */}
      <TrustBadges />
    </div>
  );
};

export default ProductInfoPanel;
