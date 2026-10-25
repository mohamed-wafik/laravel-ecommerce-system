import type { SupportTicketStatus } from "../../../api/modules/Admin/contactSupport.api";

interface IProps {
  status: SupportTicketStatus;
}

const STATUS_CONFIG: Record<
  SupportTicketStatus,
  {
    label: string;
    className: string;
  }
> = {
  open: {
    label: "Open",
    className: "bg-blue-500/10 text-blue-600",
  },

  in_progress: {
    label: "In Progress",
    className: "bg-amber-500/10 text-amber-600",
  },

  resolved: {
    label: "Resolved",
    className: "bg-emerald-500/10 text-emerald-600",
  },

  closed: {
    label: "Closed",
    className: "bg-gray-500/10 text-gray-600",
  },
};

const SupportStatusBadge = ({ status }: IProps) => {
  const config = STATUS_CONFIG[status];

  return (
    <span
      className={`inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ${config.className}`}
    >
      {config.label}
    </span>
  );
};

export default SupportStatusBadge;
