import { Pencil, Power, Trash2 } from "lucide-react";
import type { ICategory } from ".";
import ActionButton from "../../../components/ui/ActionButton";
import StatusBadge from "../../../components/ui/StatusBadge";

export interface IProps {
  category: ICategory;
  openEditModal: (category: ICategory) => void;
  openDeleteAlert: (category: ICategory) => void;
  handleToggleActive: (category: ICategory) => Promise<void>;
}
const CategoryRow = ({
  category,
  handleToggleActive,
  openEditModal,
  openDeleteAlert,
}: IProps) => {
  return (
    <tr className="transition hover:bg-bg-secondary/50">
      {/* Category */}
      <td className="px-5 py-4">
        <div className="flex items-center gap-3">
          <div className="h-11 w-11 shrink-0 overflow-hidden rounded-xl bg-bg-tertiary">
            {category.image_url ? (
              <img
                src={category.image_url}
                alt={category.name}
                className="h-full w-full object-cover"
              />
            ) : (
              <div className="flex h-full w-full items-center justify-center text-xs font-semibold text-text-secondary">
                {category.name.charAt(0).toUpperCase()}
              </div>
            )}
          </div>

          <div>
            <p className="font-semibold text-text-primary">{category.name}</p>

            <p className="text-xs text-text-secondary">
              {category.parent_id ? "Child category" : "Root category"}
            </p>
          </div>
        </div>
      </td>

      {/* Slug */}
      <td className="px-5 py-4">
        <span className="text-sm text-text-secondary">{category.slug}</span>
      </td>

      {/* Parent */}
      <td className="px-5 py-4">
        {category.parent ? (
          <span className="inline-flex rounded-lg bg-bg-tertiary px-2.5 py-1 text-sm font-medium text-text-primary">
            {category.parent.name}
          </span>
        ) : (
          <span className="text-sm text-text-secondary">—</span>
        )}
      </td>

      {/* Sort order */}
      <td className="px-5 py-4">
        <span className="text-sm text-text-primary">{category.sort_order}</span>
      </td>

      {/* Status */}
      <td className="px-5 py-4">
        <StatusBadge active={category.is_active} />
      </td>

      {/* Actions */}
      <td className="px-5 py-4">
        <div className="flex justify-end gap-2">
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
      </td>
    </tr>
  );
};

export default CategoryRow;
