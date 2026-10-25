import { Edit3, Eye, Power, Trash2 } from "lucide-react";
import ActionButton from "../../../components/ui/ActionButton";
import ProductImage from "./ProductImage";
import {
  formatPrice,
  getProductImage,
  getStockStatus,
} from "../../../lib/utils";
import type { IProps } from "./ProductCardMobile";
import { useNavigate } from "react-router-dom";
import StatusBadge from "../../../components/ui/StatusBadge";

const ProductRow = ({
  product,

  openEditModal,
  setDeletingProduct,
  handleToggleActive,
}: IProps) => {
  const navigate = useNavigate();
  const image = getProductImage(product);
  const stock = getStockStatus(product.stock_quantity);
  return (
    <tr key={product.id} className="transition hover:bg-bg-secondary">
      {/* Product */}

      <td className="px-5 py-4">
        <div className="flex items-center gap-3">
          <ProductImage src={image} alt={product.name} />

          <div className="min-w-0">
            <p className="truncate font-semibold text-text-primary">
              {product.name}
            </p>

            <p className="mt-1 text-xs text-text-tertiary">
              SKU: {product.sku}
            </p>

            {product.variants?.length > 0 && (
              <p className="mt-1 text-xs text-primary">
                {product.variants.length} variants
              </p>
            )}
          </div>
        </div>
      </td>

      {/* Category */}

      <td className="px-5 py-4">
        <p className="text-sm text-text-primary">
          {product.category?.name ?? "—"}
        </p>

        <p className="mt-1 text-xs text-text-tertiary">
          {product.brand?.name ?? "No brand"}
        </p>
      </td>

      {/* Price */}

      <td className="px-5 py-4">
        <p className="font-semibold text-price">
          ${formatPrice(product.price)}
        </p>

        {product.compare_price !== null && (
          <p className="mt-1 text-xs text-text-tertiary line-through">
            ${formatPrice(product.compare_price)}
          </p>
        )}
      </td>

      {/* Stock */}

      <td className="px-5 py-4">
        <p className="font-medium text-text-primary">
          {product.stock_quantity}
        </p>

        <span
          className={`mt-1 inline-flex rounded-full px-2 py-1 text-xs font-medium ${stock.className}`}
        >
          {stock.label}
        </span>
      </td>

      {/* Status */}

      <td className="px-5 py-4">
        <StatusBadge active={product.is_active} />
      </td>

      {/* Actions */}

      <td className="px-5 py-4">
        <div className="flex justify-end gap-2">
          <ActionButton
            label="Edit"
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
      </td>
    </tr>
  );
};

export default ProductRow;
