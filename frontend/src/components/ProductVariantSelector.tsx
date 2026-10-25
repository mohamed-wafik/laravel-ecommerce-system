import { useEffect, useMemo, useState } from "react";
import { Check } from "lucide-react";

import type { IVariant } from "../interface";

interface ProductVariantSelectorProps {
  variants: IVariant[];
  onChange: (variant: IVariant | undefined) => void;
}

const COLOR_HEX: Record<string, string> = {
  black: "#111111",
  white: "#ffffff",
  red: "#ef4444",
  blue: "#3b82f6",
  green: "#22c55e",
  yellow: "#eab308",
  orange: "#f97316",
  purple: "#a855f7",
  pink: "#ec4899",
  gray: "#9ca3af",
  grey: "#9ca3af",
  brown: "#92400e",
  navy: "#1e3a8a",
  beige: "#e8dcc8",
};

const ProductVariantSelector = ({
  variants,
  onChange,
}: ProductVariantSelectorProps) => {
  const [selectedColor, setSelectedColor] = useState<string | null>(null);
  const [selectedSize, setSelectedSize] = useState<string | null>(null);

  const uniqueColors = useMemo(
    () =>
      Array.from(
        new Set(variants.map((variant) => variant.color).filter(Boolean)),
      ),
    [variants],
  );

  const uniqueSizes = useMemo(
    () =>
      Array.from(
        new Set(variants.map((variant) => variant.size).filter(Boolean)),
      ),
    [variants],
  );

  /*
   * Automatically select the only available option.
   */
  useEffect(() => {
    setSelectedColor(uniqueColors.length === 1 ? uniqueColors[0] : null);

    setSelectedSize(uniqueSizes.length === 1 ? uniqueSizes[0] : null);
  }, [uniqueColors, uniqueSizes]);

  /*
   * Sizes compatible with selected color.
   */
  const availableSizes = useMemo(() => {
    const pool = selectedColor
      ? variants.filter((variant) => variant.color === selectedColor)
      : variants;

    return Array.from(
      new Set(pool.map((variant) => variant.size).filter(Boolean)),
    );
  }, [variants, selectedColor]);

  /*
   * Colors compatible with selected size.
   */
  const availableColors = useMemo(() => {
    const pool = selectedSize
      ? variants.filter((variant) => variant.size === selectedSize)
      : variants;

    return Array.from(
      new Set(pool.map((variant) => variant.color).filter(Boolean)),
    );
  }, [variants, selectedSize]);

  /*
   * Keep the selected size valid after changing color.
   */
  useEffect(() => {
    if (selectedSize && !availableSizes.includes(selectedSize)) {
      setSelectedSize(null);
    }
  }, [selectedColor, availableSizes, selectedSize]);

  /*
   * Keep the selected color valid after changing size.
   */
  useEffect(() => {
    if (selectedColor && !availableColors.includes(selectedColor)) {
      setSelectedColor(null);
    }
  }, [selectedSize, availableColors, selectedColor]);

  /*
   * Find the exact variant.
   */
  const selectedVariant = useMemo(() => {
    if (!variants.length) {
      return undefined;
    }

    return variants.find((variant) => {
      const colorMatches =
        uniqueColors.length === 0 || variant.color === selectedColor;

      const sizeMatches =
        uniqueSizes.length === 0 || variant.size === selectedSize;

      return colorMatches && sizeMatches;
    });
  }, [variants, uniqueColors, uniqueSizes, selectedColor, selectedSize]);

  /*
   * Return selected variant to ProductCard.
   */
  useEffect(() => {
    onChange(selectedVariant);
  }, [selectedVariant, onChange]);

  /*
   * Check color stock.
   */
  const isColorInStock = (color: string) => {
    const pool = selectedSize
      ? variants.filter(
          (variant) => variant.color === color && variant.size === selectedSize,
        )
      : variants.filter((variant) => variant.color === color);

    return pool.some((variant) => Number(variant.stock_quantity) > 0);
  };

  /*
   * Check size stock.
   */
  const isSizeInStock = (size: string) => {
    const pool = selectedColor
      ? variants.filter(
          (variant) => variant.size === size && variant.color === selectedColor,
        )
      : variants.filter((variant) => variant.size === size);

    return pool.some((variant) => Number(variant.stock_quantity) > 0);
  };

  if (!variants.length) {
    return null;
  }

  return (
    <div className="space-y-2 border-t border-gray-100 pt-2">
      {/* Color selector */}
      {uniqueColors.length > 0 && (
        <div className="flex flex-wrap items-center gap-1.5">
          {uniqueColors.map((color) => {
            const isActive = selectedColor === color;

            const isAvailable = availableColors.includes(color);

            const inStock = isColorInStock(color);

            const disabled = !isAvailable || !inStock;

            const swatch = COLOR_HEX[color.toLowerCase()];

            return (
              <button
                key={color}
                type="button"
                disabled={disabled}
                onClick={() => setSelectedColor(color)}
                aria-label={`Select color ${color}`}
                aria-pressed={isActive}
                title={disabled ? `${color} — not available` : color}
                className={`relative flex h-6 w-6 items-center justify-center rounded-full border-2 transition-all duration-150 sm:h-7 sm:w-7 ${
                  isActive
                    ? "border-primary shadow-sm ring-2 ring-primary/20"
                    : "border-gray-200 hover:border-gray-300"
                } ${
                  disabled
                    ? "cursor-not-allowed opacity-30 grayscale"
                    : "cursor-pointer"
                }`}
                style={{
                  backgroundColor: swatch ?? "#f3f4f6",
                }}
              >
                {!swatch && (
                  <span className="text-[8px] font-bold uppercase text-gray-500">
                    {color.slice(0, 2)}
                  </span>
                )}

                {isActive && swatch && (
                  <Check
                    className={`h-3 w-3 ${
                      color.toLowerCase() === "white"
                        ? "text-gray-800"
                        : "text-white"
                    }`}
                  />
                )}
              </button>
            );
          })}
        </div>
      )}

      {/* Size selector */}
      {uniqueSizes.length > 0 && (
        <div className="flex flex-wrap items-center gap-1.5">
          {availableSizes.map((size) => {
            const isActive = selectedSize === size;

            const inStock = isSizeInStock(size);

            return (
              <button
                key={size}
                type="button"
                disabled={!inStock}
                onClick={() => setSelectedSize(size)}
                aria-label={`Select size ${size}`}
                aria-pressed={isActive}
                title={inStock ? size : `${size} — out of stock`}
                className={`min-w-7 rounded-md border px-2 py-1 text-[11px] font-semibold transition-all duration-150 sm:text-xs ${
                  isActive
                    ? "border-primary bg-primary text-white shadow-sm"
                    : "border-gray-200 bg-white text-gray-700 hover:border-primary/40 hover:text-primary"
                } ${
                  !inStock
                    ? "cursor-not-allowed opacity-30 line-through"
                    : "cursor-pointer"
                }`}
              >
                {size}
              </button>
            );
          })}
        </div>
      )}
    </div>
  );
};

export default ProductVariantSelector;
