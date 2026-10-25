import type { IProps } from "./CategoryRow";
import { Pencil, Power, Trash2 } from "lucide-react";
import ActionButton from "../../../components/ui/ActionButton";
import StatusBadge from "../../../components/ui/StatusBadge";

const CategoryCardMobile = ({
  category,
  handleToggleActive,
  openEditModal,
  openDeleteAlert,
}: IProps) => {
  return (
    <div
      key={category.id}
      className="rounded-2xl border border-border bg-bg-primary p-4 shadow-sm"
    >
      <div className="flex items-start gap-3">
        <div className="h-12 w-12 shrink-0 overflow-hidden rounded-xl bg-bg-tertiary">
          {category.image_url ? (
            <img
              src={category.image_url}
              alt={category.name}
              className="h-full w-full object-cover"
            />
          ) : (
            <div className="flex h-full w-full items-center justify-center font-semibold text-text-secondary">
              {category.name.charAt(0).toUpperCase()}
            </div>
          )}
        </div>

        <div className="min-w-0 flex-1">
          <div className="flex items-start justify-between gap-2">
            <div>
              <h3 className="font-semibold text-text-primary">
                {category.name}
              </h3>

              <p className="mt-0.5 text-xs text-text-secondary">
                {category.slug}
              </p>
            </div>
            <StatusBadge active={category.is_active} />
          </div>

          <div className="mt-3 grid grid-cols-2 gap-3 text-sm">
            <div>
              <p className="text-xs text-text-secondary">Parent</p>

              <p className="mt-1 font-medium text-text-primary">
                {category.parent?.name ?? "Root"}
              </p>
            </div>

            <div>
              <p className="text-xs text-text-secondary">Order</p>

              <p className="mt-1 font-medium text-text-primary">
                {category.sort_order}
              </p>
            </div>
          </div>

          <div className="mt-4 flex justify-end gap-2 border-t border-border pt-3">
            <ActionButton label="edit" onClick={() => openEditModal(category)}>
              <Pencil size={17} />
            </ActionButton>

            <ActionButton
              label="toggle-active"
              onClick={() => handleToggleActive(category)}
            >
              <Power size={17} />
            </ActionButton>

            <ActionButton
              label="Delete"
              danger
              onClick={() => openDeleteAlert(category)}
            >
              <Trash2 size={17} />
            </ActionButton>
          </div>
        </div>
      </div>
    </div>
  );
};

export default CategoryCardMobile;
