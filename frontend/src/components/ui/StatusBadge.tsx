import { CheckCircle2, XCircle } from "lucide-react";

interface StatusBadgeProps {
  active: boolean;
}

const StatusBadge = ({ active }: StatusBadgeProps) =>
  active ? (
    <span className="inline-flex w-fit items-center gap-2 rounded-full bg-success-50 px-3 py-1.5 text-sm font-medium text-success">
      <CheckCircle2 size={16} />
      Active
    </span>
  ) : (
    <span className="inline-flex w-fit items-center gap-2 rounded-full bg-error-50 px-3 py-1.5 text-sm font-medium text-error">
      <XCircle size={16} />
      Inactive
    </span>
  );

export default StatusBadge;
