import { Pencil, Power, Trash2, ShoppingBag } from "lucide-react";

import ActionButton from "../../../components/ui/ActionButton";

import { useNavigate } from "react-router-dom";
import type { IUser } from "../../../interface";
import StatusBadge from "../../../components/ui/StatusBadge";

export interface IProps {
  user: IUser;
  openEditModal: (user: IUser) => void;
  openDeleteAlert: (user: IUser) => void;
  handleToggleActive: (user: IUser) => Promise<void>;
}

const UserRow = ({
  user,
  openEditModal,
  openDeleteAlert,
  handleToggleActive,
}: IProps) => {
  const navigate = useNavigate();

  return (
    <tr className="transition hover:bg-bg-secondary/50">
      {/* User */}

      <td className="px-5 py-4">
        <div className="flex items-center gap-3">
          <div className="h-11 w-11 shrink-0 overflow-hidden rounded-full bg-bg-tertiary">
            {user.avatar_url ? (
              <img
                src={user.avatar_url}
                alt={user.name}
                className="h-full w-full object-cover"
              />
            ) : (
              <div className="flex h-full w-full items-center justify-center font-semibold text-text-secondary">
                {user.name.charAt(0).toUpperCase()}
              </div>
            )}
          </div>

          <div className="min-w-0">
            <p className="truncate font-semibold text-text-primary">
              {user.name}
            </p>

            <p className="truncate text-xs text-text-secondary">{user.email}</p>
          </div>
        </div>
      </td>

      {/* Phone */}

      <td className="px-5 py-4">
        <span className="text-sm text-text-secondary">{user.phone || "-"}</span>
      </td>

      {/* Role */}

      <td className="px-5 py-4">
        <span className="inline-flex rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold capitalize text-primary">
          {user.role}
        </span>
      </td>

      {/* Status */}

      <td className="px-5 py-4">
        <StatusBadge active={user.is_active} />
      </td>

      {/* Actions */}

      <td className="px-5 py-4">
        <div className="flex justify-end gap-2">
          <ActionButton
            label="orders"
            onClick={() => navigate(`/admin/users/${user.id}/orders`)}
          >
            <ShoppingBag size={17} />
          </ActionButton>

          <ActionButton label="edit" onClick={() => openEditModal(user)}>
            <Pencil size={17} />
          </ActionButton>

          <ActionButton
            label="toggle-active"
            onClick={() => handleToggleActive(user)}
          >
            <Power size={17} />
          </ActionButton>

          <ActionButton
            label="Delete"
            danger
            onClick={() => openDeleteAlert(user)}
          >
            <Trash2 size={17} />
          </ActionButton>
        </div>
      </td>
    </tr>
  );
};

export default UserRow;
