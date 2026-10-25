import { useEffect } from "react";
import { Link, useNavigate, useParams } from "react-router-dom";
import {
  ArrowLeft,
  Box,
  Calendar,
  CircleDollarSign,
  Layers,
  Package,
  Tag,
} from "lucide-react";

import { adminProductAPI } from "../../../api/modules/Admin/product.api";
import InfoItem from "../../../components/ui/InfoItem";

import { formatDate } from "../../../lib/utils";
import ProductDetailsSkeleton from "./ProductDetailsSkeleton";
import SectionCard from "./SectionCard";
import SectionHeader from "./SectionHeader";
import ProductImages from "./ProductImages";
import ProductVariants from "./ProductVariants";
import PriceRow from "./PriceRow";
import StatusBadge from "../../../components/ui/StatusBadge";

const formatPrice = (value: number | string | null | undefined) =>
  `$${Number(value ?? 0).toFixed(2)}`;

const getDiscount = (
  price: number | string,
  comparePrice: number | string | null | undefined,
) => {
  const current = Number(price);
  const compare = Number(comparePrice);

  if (!comparePrice || compare <= current) return null;

  return Math.round(((compare - current) / compare) * 100);
};

const ProductDetails = () => {
  const navigate = useNavigate();
  const { productId } = useParams<{ productId: string }>();

  useEffect(() => {
    if (!productId) {
      navigate("/admin/products", { replace: true });
    }
  }, [navigate, productId]);

  const { data, isLoading, isFetching } = adminProductAPI.getById(
    String(productId),
  );

  const product = data?.data;

  if (isLoading) {
    return <ProductDetailsSkeleton />;
  }

  if (!product) {
    return (
      <section className="space-y-6">
        <BackToProducts />

        <div className="rounded-2xl border border-border bg-bg-primary px-5 py-12 text-center shadow-sm">
          <p className="text-sm text-text-secondary">Product not found.</p>
        </div>
      </section>
    );
  }

  const {
    id,
    name,
    slug,
    description,
    price,
    compare_price,
    sku,
    stock_quantity,
    is_active,
    images = [],
    variants = [],
    brand,
    category,
    brand_id,
    category_id,
    created_at,
    updated_at,
  } = product;

  const discount = getDiscount(price, compare_price);

  return (
    <section className="space-y-6">
      {/* Header */}
      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <BackToProducts className="mb-3" />

          <h1 className="text-2xl font-bold text-text-primary">
            Product Details
          </h1>

          <p className="mt-1 text-sm text-text-secondary">
            View product information, pricing, inventory, and variants.
          </p>
        </div>

        <StatusBadge active={is_active} />
      </div>

      {isFetching && (
        <p className="text-sm text-text-secondary">Updating product...</p>
      )}

      <div className="grid gap-6 lg:grid-cols-3">
        {/* Left */}
        <div className="space-y-6 lg:col-span-2">
          {/* Overview */}
          <SectionCard>
            <SectionHeader
              title="Product Overview"
              icon={<Package size={18} className="text-primary" />}
            />

            <div className="p-4 sm:p-5">
              <div className="grid gap-6 md:grid-cols-2">
                <ProductImages images={images} name={name} />

                <div className="flex flex-col">
                  <div>
                    <h3 className="text-xl font-bold text-text-primary">
                      {name}
                    </h3>

                    <p className="mt-1 break-all text-sm text-text-tertiary">
                      {slug}
                    </p>
                  </div>

                  {/* Price */}
                  <div className="mt-6">
                    <p className="text-xs font-medium uppercase tracking-wide text-text-tertiary">
                      Price
                    </p>

                    <div className="mt-1 flex flex-wrap items-center gap-3">
                      <span className="text-2xl font-bold text-text-primary">
                        {formatPrice(price)}
                      </span>

                      {compare_price && (
                        <span className="text-sm text-text-tertiary line-through">
                          {formatPrice(compare_price)}
                        </span>
                      )}

                      {discount && (
                        <span className="rounded-full bg-success-50 px-2.5 py-1 text-xs font-semibold text-success">
                          {discount}% OFF
                        </span>
                      )}
                    </div>
                  </div>

                  {/* Meta */}
                  <div className="mt-6 grid grid-cols-2 gap-4">
                    <InfoItem label="SKU" value={sku} />
                    <InfoItem label="Stock" value={String(stock_quantity)} />
                    <InfoItem label="Brand" value={brand?.name} />
                    <InfoItem label="Category" value={category?.name} />
                  </div>

                  {/* Description */}
                  <div className="mt-6">
                    <p className="text-xs font-medium uppercase tracking-wide text-text-tertiary">
                      Description
                    </p>

                    <p className="mt-2 text-sm leading-7 text-text-secondary">
                      {description || "No description available."}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </SectionCard>

          {/* Variants */}
          <ProductVariants variants={variants} basePrice={price} />

          {/* Product Information */}
          <SectionCard>
            <SectionHeader title="Product Information" />

            <div className="grid gap-4 p-4 sm:grid-cols-2 sm:p-5">
              <InfoItem label="Product ID" value={id} />
              <InfoItem label="SKU" value={sku} />
              <InfoItem label="Brand ID" value={brand_id} />
              <InfoItem label="Category ID" value={category_id} />

              <InfoItem label="Created At" value={formatDate(created_at)} />

              <InfoItem label="Updated At" value={formatDate(updated_at)} />
            </div>
          </SectionCard>
        </div>

        {/* Right */}
        <div className="space-y-6">
          {/* Pricing */}
          <SectionCard>
            <SectionHeader
              title="Pricing"
              icon={<CircleDollarSign size={18} className="text-primary" />}
            />

            <div className="space-y-4 p-4 sm:p-5">
              <PriceRow label="Current price" value={formatPrice(price)} />

              <PriceRow
                label="Compare price"
                value={
                  compare_price
                    ? formatPrice(compare_price)
                    : "No compare price"
                }
              />

              {discount && (
                <div className="rounded-xl bg-success-50 p-3">
                  <p className="text-xs font-medium text-success">
                    Current discount
                  </p>

                  <p className="mt-1 text-lg font-bold text-success">
                    {discount}% OFF
                  </p>
                </div>
              )}
            </div>
          </SectionCard>

          {/* Inventory */}
          <SectionCard>
            <SectionHeader
              title="Inventory"
              icon={<Box size={18} className="text-primary" />}
            />

            <div className="p-4 sm:p-5">
              <div className="flex items-center justify-between">
                <span className="text-sm text-text-secondary">
                  Available stock
                </span>

                <span className="text-lg font-bold text-text-primary">
                  {stock_quantity}
                </span>
              </div>

              <div className="mt-4 h-2 overflow-hidden rounded-full bg-bg-secondary">
                <div
                  className="h-full rounded-full bg-primary transition-all"
                  style={{
                    width: `${Math.min(
                      Math.max((stock_quantity / 50) * 100, 4),
                      100,
                    )}%`,
                  }}
                />
              </div>

              <p className="mt-2 text-xs text-text-tertiary">
                {stock_quantity === 0
                  ? "Out of stock"
                  : stock_quantity <= 5
                    ? "Low stock"
                    : "In stock"}
              </p>
            </div>
          </SectionCard>

          {/* Brand */}
          <SectionCard>
            <SectionHeader
              title="Brand"
              icon={<Tag size={18} className="text-primary" />}
            />

            <div className="p-4 sm:p-5">
              <div className="flex items-center gap-3">
                {brand?.logo_url ? (
                  <img
                    src={brand.logo_url}
                    alt={brand.name}
                    className="h-12 w-12 rounded-xl object-contain"
                  />
                ) : (
                  <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-bg-secondary">
                    <Tag size={20} className="text-text-tertiary" />
                  </div>
                )}

                <div>
                  <p className="font-semibold text-text-primary">
                    {brand?.name || "No brand"}
                  </p>

                  <p className="text-xs text-text-tertiary">
                    {brand?.slug || "-"}
                  </p>
                </div>
              </div>
            </div>
          </SectionCard>

          {/* Category */}
          <SectionCard>
            <SectionHeader
              title="Category"
              icon={<Layers size={18} className="text-primary" />}
            />

            <div className="p-4 sm:p-5">
              <p className="font-semibold text-text-primary">
                {category?.name || "No category"}
              </p>

              <p className="mt-1 break-all text-xs text-text-tertiary">
                {category?.slug || "-"}
              </p>

              {category?.parent_id && (
                <p className="mt-3 text-xs text-text-secondary">
                  Has parent category
                </p>
              )}
            </div>
          </SectionCard>

          {/* Dates */}
          <SectionCard>
            <SectionHeader
              title="Dates"
              icon={<Calendar size={18} className="text-primary" />}
            />

            <div className="space-y-4 p-4 sm:p-5">
              <InfoItem label="Created" value={formatDate(created_at)} />

              <InfoItem label="Last updated" value={formatDate(updated_at)} />
            </div>
          </SectionCard>
        </div>
      </div>
    </section>
  );
};

/* -------------------------------------------------------------------------- */
/* Navigation                                                                 */
/* -------------------------------------------------------------------------- */

interface BackToProductsProps {
  className?: string;
}

const BackToProducts = ({ className = "" }: BackToProductsProps) => (
  <Link
    to="/admin/products"
    className={`inline-flex items-center gap-2 text-sm font-medium text-text-secondary transition hover:text-text-primary ${className}`}
  >
    <ArrowLeft size={17} />
    Back to products
  </Link>
);

export default ProductDetails;
