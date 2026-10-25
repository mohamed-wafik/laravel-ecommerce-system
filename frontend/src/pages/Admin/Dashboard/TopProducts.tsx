import { Package } from "lucide-react";
import { formatCurrency } from "../../../lib/utils";

const TopProducts = ({
  products,
}: {
  products: {
    id: string;
    name: string;
    sold: number;
    revenue: number;
  }[];
}) => {
  return (
    <div className="rounded-2xl border border-border bg-bg-primary p-5 shadow-sm">
      <div className="mb-5 flex items-center justify-between">
        <div>
          <h2 className="font-semibold text-text-primary">
            Best Selling Products
          </h2>

          <p className="mt-1 text-xs text-text-tertiary">
            Top products by units sold
          </p>
        </div>

        <Package className="h-5 w-5 text-secondary" />
      </div>

      <div className="space-y-4">
        {products.length === 0 ? (
          <p className="py-8 text-center text-sm text-text-tertiary">
            No sales data available.
          </p>
        ) : (
          products.map((product, index) => (
            <div
              key={product.id}
              className="flex items-center gap-3 rounded-xl border border-border-light p-3 transition-colors hover:bg-bg-secondary"
            >
              <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-sm font-bold text-primary">
                #{index + 1}
              </div>

              <div className="min-w-0 flex-1">
                <p className="truncate text-sm font-medium text-text-primary">
                  {product.name}
                </p>

                <p className="mt-1 text-xs text-text-tertiary">
                  {product.sold} units sold
                </p>
              </div>

              <p className="shrink-0 text-sm font-semibold text-price">
                {formatCurrency(product.revenue)}
              </p>
            </div>
          ))
        )}
      </div>
    </div>
  );
};

export default TopProducts;
