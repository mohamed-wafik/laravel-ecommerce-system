import { Pencil, Power, Trash2, ShoppingBag } from "lucide-react";

import { useNavigate } from "react-router-dom";

import ActionButton from "../../../components/ui/ActionButton";
import type { IProps } from "./UserRow";
import StatusBadge from "../../../components/ui/StatusBadge";

const UserCardMobile = ({
  user,
  handleToggleActive,
  openEditModal,
  openDeleteAlert,
}: IProps) => {
  const navigate = useNavigate();

  return (
    <div className="rounded-2xl border border-border bg-bg-primary p-4 shadow-sm">
      <div className="flex items-start gap-3">
        {/* Avatar */}

        <div className="h-12 w-12 shrink-0 overflow-hidden rounded-full bg-bg-tertiary">
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

        <div className="min-w-0 flex-1">
          {/* Header */}

          <div className="flex items-start justify-between gap-2">
            <div className="min-w-0">
              <h3 className="truncate font-semibold text-text-primary">
                {user.name}
              </h3>

              <p className="truncate text-xs text-text-secondary">
                {user.email}
              </p>
            </div>
            <StatusBadge active={user.is_active} />
          </div>

          {/* Details */}

          <div className="mt-4 grid grid-cols-2 gap-3">
            <div>
              <p className="text-xs text-text-secondary">Role</p>

              <p className="mt-1 text-sm font-medium capitalize text-text-primary">
                {user.role}
              </p>
            </div>

            <div>
              <p className="text-xs text-text-secondary">Phone</p>

              <p className="mt-1 truncate text-sm font-medium text-text-primary">
                {user.phone || "-"}
              </p>
            </div>
          </div>

          {/* Actions */}

          <div className="mt-4 flex justify-end gap-2 border-t border-border pt-3">
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
        </div>
      </div>
    </div>
  );
};

export default UserCardMobile;
