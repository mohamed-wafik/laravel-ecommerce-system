import type { OrderStatus } from "../../interface/order";
import { statusClass } from "../../lib/utils";

const StatusBadge = ({ status }: { status: OrderStatus }) => {
  return (
    <span
      className={`inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold ${statusClass(status)}`}
    >
      <span className="mr-1.5 h-1.5 w-1.5 rounded-full bg-current" />
      {status}
    </span>
  );
};

export default StatusBadge;
