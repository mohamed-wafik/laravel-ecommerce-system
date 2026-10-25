import { Pencil, Power, Trash2 } from "lucide-react";

import type { IBrand } from ".";
import ActionButton from "../../../components/ui/ActionButton";
import StatusBadge from "../../../components/ui/StatusBadge";

export interface IProps {
  brand: IBrand;
  openEditModal: (brand: IBrand) => void;
  openDeleteAlert: (brand: IBrand) => void;
  handleToggleActive: (brand: IBrand) => Promise<void>;
}

const BrandRow = ({
  brand,
  handleToggleActive,
  openEditModal,
  openDeleteAlert,
}: IProps) => {
  return (
    <tr className="transition hover:bg-bg-secondary/50">
      {/* Brand */}

      <td className="px-5 py-4">
        <div className="flex items-center gap-3">
          <div className="h-11 w-11 shrink-0 overflow-hidden rounded-xl bg-bg-tertiary">
            {brand.logo_url ? (
              <img
                src={brand.logo_url}
                alt={brand.name}
                className="h-full w-full object-contain"
              />
            ) : (
              <div className="flex h-full w-full items-center justify-center text-xs font-semibold text-text-secondary">
                {brand.name.charAt(0).toUpperCase()}
              </div>
            )}
          </div>

          <div>
            <p className="font-semibold text-text-primary">{brand.name}</p>

            <p className="text-xs text-text-secondary">Brand</p>
          </div>
        </div>
      </td>

      {/* Slug */}

      <td className="px-5 py-4">
        <span className="text-sm text-text-secondary">{brand.slug}</span>
      </td>

      {/* Status */}

      <td className="px-5 py-4">
        <StatusBadge active={brand.is_active} />
      </td>

      {/* Actions */}

      <td className="px-5 py-4">
        <div className="flex justify-end gap-2">
          <ActionButton label="edit" onClick={() => openEditModal(brand)}>
            <Pencil size={17} />
          </ActionButton>

          <ActionButton
            label="toggle-active"
            onClick={() => handleToggleActive(brand)}
          >
            <Power size={17} />
          </ActionButton>

          <ActionButton
            label="Delete"
            danger
            onClick={() => openDeleteAlert(brand)}
          >
            <Trash2 size={17} />
          </ActionButton>
        </div>
      </td>
    </tr>
  );
};

export default BrandRow;
