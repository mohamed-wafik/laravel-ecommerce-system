import { Eye } from "lucide-react";

import ActionButton from "../../../components/ui/ActionButton";
import type { ISupportTicket } from "../../../api/modules/Admin/contactSupport.api";
import SupportStatusBadge from "./StatusBadge";

interface IProps {
  ticket: ISupportTicket;
  openTicket: (ticket: ISupportTicket) => void;
}

const SupportTicketCardMobile = ({ ticket, openTicket }: IProps) => {
  return (
    <div className="rounded-2xl border border-border bg-bg-primary p-4 shadow-sm">
      {/* Header */}

      <div className="flex items-start gap-3">
        <div className="h-11 w-11 shrink-0 overflow-hidden rounded-full bg-bg-tertiary">
          {ticket.user?.avatar_url ? (
            <img
              src={ticket.user.avatar_url}
              alt={ticket.name}
              className="h-full w-full object-cover"
            />
          ) : (
            <div className="flex h-full w-full items-center justify-center font-semibold text-text-secondary">
              {ticket.name.charAt(0).toUpperCase()}
            </div>
          )}
        </div>

        <div className="min-w-0 flex-1">
          <div className="flex items-start justify-between gap-2">
            <div className="min-w-0">
              <h3 className="truncate font-semibold text-text-primary">
                {ticket.name}
              </h3>

              <p className="truncate text-xs text-text-secondary">
                {ticket.email}
              </p>
            </div>

            <SupportStatusBadge status={ticket.status} />
          </div>
        </div>
      </div>

      {/* Ticket */}

      <div className="mt-4">
        <p className="text-xs font-medium text-text-tertiary">Subject</p>

        <p className="mt-1 font-semibold text-text-primary">{ticket.subject}</p>

        <p className="mt-2 line-clamp-3 text-sm leading-6 text-text-secondary">
          {ticket.message}
        </p>
      </div>

      {/* Meta */}

      <div className="mt-4 grid grid-cols-2 gap-3 border-t border-border pt-4">
        <div>
          <p className="text-xs text-text-tertiary">Created</p>

          <p className="mt-1 text-sm font-medium text-text-primary">
            {new Date(ticket.created_at).toLocaleDateString()}
          </p>
        </div>

        <div>
          <p className="text-xs text-text-tertiary">Last reply</p>

          <p className="mt-1 text-sm font-medium text-text-primary">
            {ticket.last_reply_at
              ? new Date(ticket.last_reply_at).toLocaleDateString()
              : "No reply"}
          </p>
        </div>
      </div>

      {/* Action */}

      <div className="mt-4 flex justify-end border-t border-border pt-3">
        <ActionButton label="View ticket" onClick={() => openTicket(ticket)}>
          <Eye size={17} />
        </ActionButton>
      </div>
    </div>
  );
};

export default SupportTicketCardMobile;
