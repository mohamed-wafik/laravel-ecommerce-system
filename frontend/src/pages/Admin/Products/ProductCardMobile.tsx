import {
  formatPrice,
  getProductImage,
  getStockStatus,
} from "../../../lib/utils";
import type { IProduct } from "../../../interface";
import ProductImage from "./ProductImage";

import ActionButton from "../../../components/ui/ActionButton";
import { Edit3, Eye, Power, Trash2 } from "lucide-react";
import { useNavigate } from "react-router-dom";
import StatusBadge from "../../../components/ui/StatusBadge";

export interface IProps {
  product: IProduct;
  openEditModal: (product: IProduct) => void;
  setDeletingProduct: (product: IProduct) => void;
  handleToggleActive: (product: IProduct) => Promise<void>;
}

const ProductCardMobile = ({
  product,
  openEditModal,
  setDeletingProduct,
  handleToggleActive,
}: IProps) => {
  const navigate = useNavigate();
  const image = getProductImage(product);
  const stock = getStockStatus(product.stock_quantity);

  return (
    <article
      key={product.id}
      className="rounded-2xl border border-border bg-bg-primary p-4 shadow-sm"
    >
      <div className="flex gap-3">
        <ProductImage src={image} alt={product.name} size="large" />{" "}
        <div className="min-w-0 flex-1">
          <div className="flex items-start justify-between gap-2">
            <div className="min-w-0">
              <h3 className="truncate font-semibold text-text-primary">
                {product.name}
              </h3>
              <p className="mt-1 text-xs text-text-tertiary">
                {product.brand?.name ?? "No brand"}
              </p>
            </div>

            <StatusBadge active={product.is_active} />
          </div>
          <div className="mt-3 flex flex-wrap gap-2">
            <span className="rounded-lg bg-bg-secondary px-2.5 py-1 text-xs text-text-secondary">
              SKU: {product.sku}
            </span>
            <span
              className={`rounded-lg px-2.5 py-1 text-xs font-medium ${stock.className}`}
            >
              {product.stock_quantity} stock
            </span>
            {product.variants?.length > 0 && (
              <span className="rounded-lg bg-primary-50 px-2.5 py-1 text-xs font-medium text-primary">
                {product.variants.length} variants
              </span>
            )}
          </div>
        </div>
      </div>
      <div className="mt-4 flex items-center justify-between border-t border-border pt-4">
        <div>
          <p className="font-bold text-price">${formatPrice(product.price)} </p>
          {product.compare_price !== null && (
            <p className="text-xs text-text-tertiary line-through">
              $ {formatPrice(product.compare_price)}
            </p>
          )}
        </div>
        <div className="flex gap-2">
          <ActionButton
            label="show"
            onClick={() =>
              navigate(`/admin/products/${product.id}`, { replace: true })
            }
          >
            <Eye size={16} />
          </ActionButton>
          <ActionButton label="Edit" onClick={() => openEditModal(product)}>
            <Edit3 size={16} />
          </ActionButton>
          <ActionButton
            label="toggle-active"
            onClick={() => handleToggleActive(product)}
          >
            <Power size={17} />
          </ActionButton>
          <ActionButton
            label="Delete"
            danger
            onClick={() => setDeletingProduct(product)}
          >
            <Trash2 size={16} />
          </ActionButton>
        </div>
      </div>
    </article>
  );
};

export default ProductCardMobile;
