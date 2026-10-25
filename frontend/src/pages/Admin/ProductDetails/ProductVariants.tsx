import { Layers } from "lucide-react";
import type { IVariant } from "../../../interface";
import { formatPrice } from "../../../lib/utils";
import SectionCard from "./SectionCard";
import SectionHeader from "./SectionHeader";

interface ProductVariantsProps {
  variants: IVariant[];
  basePrice: number | string;
}

const ProductVariants = ({ variants, basePrice }: ProductVariantsProps) => {
  const getVariantPrice = (variant: IVariant) =>
    variant.price_override
      ? formatPrice(variant.price_override)
      : formatPrice(basePrice);

  return (
    <SectionCard>
      <SectionHeader
        title="Variants"
        icon={<Layers size={18} className="text-primary" />}
        action={
          <span className="rounded-full bg-bg-secondary px-2.5 py-1 text-xs font-medium text-text-secondary">
            {variants.length} variants
          </span>
        }
      />

      <div className="p-4 sm:p-5">
        {variants.length === 0 ? (
          <div className="py-8 text-center">
            <p className="text-sm text-text-secondary">
              No variants available.
            </p>
          </div>
        ) : (
          <>
            {/* Desktop */}
            <div className="hidden overflow-x-auto md:block">
              <table className="w-full min-w-[650px]">
                <thead>
                  <tr className="border-b border-border">
                    {["Color", "Size", "SKU", "Price", "Stock"].map(
                      (header, index) => (
                        <th
                          key={header}
                          className={`px-4 py-3 text-xs font-semibold uppercase tracking-wide text-text-secondary ${
                            index === 4 ? "text-right" : "text-left"
                          }`}
                        >
                          {header}
                        </th>
                      ),
                    )}
                  </tr>
                </thead>

                <tbody className="divide-y divide-border">
                  {variants.map((variant) => (
                    <tr key={variant.id}>
                      <td className="px-4 py-4 text-sm text-text-primary">
                        {variant.color || "-"}
                      </td>

                      <td className="px-4 py-4 text-sm text-text-primary">
                        {variant.size || "-"}
                      </td>

                      <td className="px-4 py-4 text-sm text-text-secondary">
                        {variant.sku}
                      </td>

                      <td className="px-4 py-4 text-sm font-medium text-text-primary">
                        {getVariantPrice(variant)}
                      </td>

                      <td className="px-4 py-4 text-right text-sm font-medium text-text-primary">
                        {variant.stock_quantity}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            {/* Mobile */}
            <div className="space-y-3 md:hidden">
              {variants.map((variant) => (
                <div
                  key={variant.id}
                  className="rounded-xl border border-border bg-bg-secondary p-4"
                >
                  <div className="flex items-start justify-between gap-3">
                    <div>
                      <p className="font-semibold text-text-primary">
                        {variant.color || "Default"}
                        {variant.size ? ` / ${variant.size}` : ""}
                      </p>

                      <p className="mt-1 text-xs text-text-tertiary">
                        {variant.sku}
                      </p>
                    </div>

                    <span className="rounded-lg bg-bg-primary px-2.5 py-1 text-xs font-medium text-text-secondary">
                      Stock: {variant.stock_quantity}
                    </span>
                  </div>

                  <div className="mt-4 flex items-center justify-between border-t border-border pt-3">
                    <span className="text-xs text-text-tertiary">Price</span>

                    <span className="text-sm font-semibold text-text-primary">
                      {getVariantPrice(variant)}
                    </span>
                  </div>
                </div>
              ))}
            </div>
          </>
        )}
      </div>
    </SectionCard>
  );
};
export default ProductVariants;
