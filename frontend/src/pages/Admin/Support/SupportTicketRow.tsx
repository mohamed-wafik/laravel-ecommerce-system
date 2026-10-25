import { Eye } from "lucide-react";

import ActionButton from "../../../components/ui/ActionButton";
import type { ISupportTicket } from "../../../api/modules/Admin/contactSupport.api";
import SupportStatusBadge from "./StatusBadge";

interface IProps {
  ticket: ISupportTicket;
  openTicket: (ticket: ISupportTicket) => void;
}

const SupportTicketRow = ({ ticket, openTicket }: IProps) => {
  return (
    <tr className="transition hover:bg-bg-secondary/50">
      {/* Customer */}

      <td className="px-5 py-4">
        <div className="flex items-center gap-3">
          <div className="h-10 w-10 shrink-0 overflow-hidden rounded-full bg-bg-tertiary">
            {ticket.user?.avatar_url ? (
              <img
                src={ticket.user.avatar_url}
                alt={ticket.name}
                className="h-full w-full object-cover"
              />
            ) : (
              <div className="flex h-full w-full items-center justify-center text-sm font-semibold text-text-secondary">
                {ticket.name.charAt(0).toUpperCase()}
              </div>
            )}
          </div>

          <div className="min-w-0">
            <p className="truncate font-semibold text-text-primary">
              {ticket.name}
            </p>

            <p className="truncate text-xs text-text-secondary">
              {ticket.email}
            </p>
          </div>
        </div>
      </td>

      {/* Subject */}

      <td className="max-w-xs px-5 py-4">
        <p className="truncate text-sm font-semibold text-text-primary">
          {ticket.subject}
        </p>

        <p className="mt-1 truncate text-xs text-text-secondary">
          {ticket.message}
        </p>
      </td>

      {/* Status */}

      <td className="px-5 py-4">
        <SupportStatusBadge status={ticket.status} />
      </td>

      {/* Last reply */}

      <td className="px-5 py-4">
        <span className="text-sm text-text-secondary">
          {ticket.last_reply_at
            ? new Date(ticket.last_reply_at).toLocaleDateString()
            : "No reply"}
        </span>
      </td>

      {/* Created */}

      <td className="px-5 py-4">
        <span className="text-sm text-text-secondary">
          {new Date(ticket.created_at).toLocaleDateString()}
        </span>
      </td>

      {/* Actions */}

      <td className="px-5 py-4">
        <div className="flex justify-end">
          <ActionButton label="View ticket" onClick={() => openTicket(ticket)}>
            <Eye size={17} />
          </ActionButton>
        </div>
      </td>
    </tr>
  );
};

export default SupportTicketRow;
