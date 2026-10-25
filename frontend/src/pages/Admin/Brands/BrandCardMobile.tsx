import { Pencil, Power, Trash2 } from "lucide-react";

import type { IProps } from "./BrandRow";
import ActionButton from "../../../components/ui/ActionButton";

const BrandCardMobile = ({
  brand,
  handleToggleActive,
  openEditModal,
  openDeleteAlert,
}: IProps) => {
  return (
    <div className="rounded-2xl border border-border bg-bg-primary p-4 shadow-sm">
      <div className="flex items-start gap-3">
        {/* Logo */}

        <div className="h-12 w-12 shrink-0 overflow-hidden rounded-xl bg-bg-tertiary">
          {brand.logo_url ? (
            <img
              src={brand.logo_url}
              alt={brand.name}
              className="h-full w-full object-contain"
            />
          ) : (
            <div className="flex h-full w-full items-center justify-center font-semibold text-text-secondary">
              {brand.name.charAt(0).toUpperCase()}
            </div>
          )}
        </div>

        <div className="min-w-0 flex-1">
          {/* Header */}

          <div className="flex items-start justify-between gap-2">
            <div className="min-w-0">
              <h3 className="truncate font-semibold text-text-primary">
                {brand.name}
              </h3>

              <p className="mt-0.5 truncate text-xs text-text-secondary">
                {brand.slug}
              </p>
            </div>

            <span
              className={`shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold ${
                brand.is_active
                  ? "bg-green-500/10 text-green-600"
                  : "bg-red-500/10 text-red-600"
              }`}
            >
              {brand.is_active ? "Active" : "Inactive"}
            </span>
          </div>

          {/* Details */}

          <div className="mt-3">
            <p className="text-xs text-text-secondary">Brand</p>

            <p className="mt-1 text-sm font-medium text-text-primary">
              {brand.name}
            </p>
          </div>

          {/* Actions */}

          <div className="mt-4 flex justify-end gap-2 border-t border-border pt-3">
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
        </div>
      </div>
    </div>
  );
};

export default BrandCardMobile;
