import { Check } from "lucide-react";
import type { IVariant } from "../../../interface";

/*
 * Best-effort color swatch mapping. Falls back to a neutral chip
 * with the color name printed on it if it isn't recognized.
 */
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

interface VariantSelectorProps {
  variants: IVariant[];
  uniqueColors: string[];
  uniqueSizes: string[];
  selectedColor: string | null;
  selectedSize: string | null;
  onSelectColor: (color: string) => void;
  onSelectSize: (size: string) => void;
}

const VariantSelector = ({
  variants,
  uniqueColors,
  uniqueSizes,
  selectedColor,
  selectedSize,
  onSelectColor,
  onSelectSize,
}: VariantSelectorProps) => {
  const sizesForSelectedColor = selectedColor
    ? Array.from(
        new Set(
          variants
            .filter((v) => v.color === selectedColor)
            .map((v) => v.size)
            .filter(Boolean),
        ),
      )
    : uniqueSizes;

  const colorsForSelectedSize = selectedSize
    ? Array.from(
        new Set(
          variants
            .filter((v) => v.size === selectedSize)
            .map((v) => v.color)
            .filter(Boolean),
        ),
      )
    : uniqueColors;

  const isColorInStock = (color: string) => {
    const pool = selectedSize
      ? variants.filter((v) => v.size === selectedSize && v.color === color)
      : variants.filter((v) => v.color === color);
    return pool.some((v) => (Number(v.stock_quantity) || 0) > 0);
  };

  const isSizeInStock = (size: string) => {
    const pool = selectedColor
      ? variants.filter((v) => v.color === selectedColor && v.size === size)
      : variants.filter((v) => v.size === size);
    return pool.some((v) => (Number(v.stock_quantity) || 0) > 0);
  };

  return (
    <div className="mb-8 space-y-5">
      {/* Colors — only ones that still pair with the chosen size are selectable */}
      {uniqueColors.length > 0 && (
        <div>
          <div className="mb-2 flex items-center justify-between">
            <span className="text-xs font-semibold uppercase tracking-wide text-gray-500">
              Color
            </span>
            {selectedColor && (
              <span className="text-xs font-medium capitalize text-dark">
                {selectedColor}
              </span>
            )}
          </div>

          <div className="flex flex-wrap items-center gap-2">
            {uniqueColors.map((color) => {
              const isActive = selectedColor === color;
              const isAvailable = colorsForSelectedSize.includes(color);
              const inStock = isColorInStock(color);
              const isDisabled = !isAvailable || !inStock;
              const swatch = COLOR_HEX[color?.toLowerCase?.() ?? ""];

              return (
                <button
                  key={color}
                  type="button"
                  disabled={isDisabled}
                  onClick={() => onSelectColor(color)}
                  aria-label={`Select color ${color}`}
                  aria-pressed={isActive}
                  title={isDisabled ? `${color} — not available` : color}
                  className={`relative flex h-9 w-9 items-center justify-center rounded-full border-2 transition-all duration-150 ${
                    isActive
                      ? "border-primary shadow-sm ring-2 ring-primary/20"
                      : "border-gray-200 hover:border-gray-300"
                  } ${
                    isDisabled
                      ? "cursor-not-allowed opacity-30 grayscale"
                      : "cursor-pointer"
                  }`}
                  style={
                    swatch
                      ? { backgroundColor: swatch }
                      : { backgroundColor: "#f3f4f6" }
                  }
                >
                  {!swatch && (
                    <span className="text-[9px] font-bold uppercase text-gray-500">
                      {color?.slice(0, 2)}
                    </span>
                  )}
                  {isActive && swatch && (
                    <Check
                      className={`h-4 w-4 ${
                        color?.toLowerCase() === "white"
                          ? "text-gray-800"
                          : "text-white"
                      }`}
                    />
                  )}
                </button>
              );
            })}
          </div>
        </div>
      )}

      {/* Sizes — dynamically scoped to the chosen color, not a static list */}
      {uniqueSizes.length > 0 && (
        <div>
          <div className="mb-2 flex items-center justify-between">
            <span className="text-xs font-semibold uppercase tracking-wide text-gray-500">
              Size
            </span>
            {selectedSize && (
              <span className="text-xs font-medium text-dark">
                {selectedSize}
              </span>
            )}
          </div>

          <div className="flex flex-wrap items-center gap-2">
            {sizesForSelectedColor.map((size) => {
              const isActive = selectedSize === size;
              const inStock = isSizeInStock(size);

              return (
                <button
                  key={size}
                  type="button"
                  disabled={!inStock}
                  onClick={() => onSelectSize(size)}
                  aria-pressed={isActive}
                  title={inStock ? size : `${size} — out of stock`}
                  className={`min-w-11 rounded-lg border px-3 py-2 text-sm font-semibold transition-all duration-150 ${
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
        </div>
      )}
    </div>
  );
};

export default VariantSelector;
