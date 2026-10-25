import {
  CircleDollarSign,
  Clock3,
  CreditCard,
  PackageCheck,
  RotateCcw,
  Truck,
  XCircle,
} from "lucide-react";

import { adminOrderAPI } from "../../../api/modules/Admin/order.api";
import StatCard from "../Dashboard/StatCard";

const StatsSection = () => {
  const { data, isLoading } = adminOrderAPI.stats();

  if (isLoading) {
    return (
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {Array.from({ length: 8 }).map((_, index) => (
          <div
            key={index}
            className="h-36 animate-pulse rounded-2xl border border-border bg-bg-primary"
          />
        ))}
      </div>
    );
  }

  const stats = data?.data;

  if (!stats) return null;

  const statCards = [
    {
      label: "Revenue",
      value: `$${Number(stats.revenue).toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      })}`,
      description: "Total order revenue",
      icon: CircleDollarSign,
      iconClass: "bg-emerald-500/10 text-emerald-500",
    },
    {
      label: "Pending",
      value: stats.pending,
      description: "Orders awaiting processing",
      icon: Clock3,
      iconClass: "bg-amber-500/10 text-amber-500",
    },
    {
      label: "Processing",
      value: stats.processing,
      description: "Orders being processed",
      icon: PackageCheck,
      iconClass: "bg-blue-500/10 text-blue-500",
    },
    {
      label: "Paid",
      value: stats.paid,
      description: "Successfully paid orders",
      icon: CreditCard,
      iconClass: "bg-indigo-500/10 text-indigo-500",
    },
    {
      label: "Shipped",
      value: stats.shipped,
      description: "Orders currently shipped",
      icon: Truck,
      iconClass: "bg-cyan-500/10 text-cyan-500",
    },
    {
      label: "Delivered",
      value: stats.delivered,
      description: "Successfully delivered orders",
      icon: PackageCheck,
      iconClass: "bg-green-500/10 text-green-500",
    },
    {
      label: "Cancelled",
      value: stats.cancelled,
      description: "Cancelled orders",
      icon: XCircle,
      iconClass: "bg-red-500/10 text-red-500",
    },
    {
      label: "Refunded",
      value: stats.refunded,
      description: "Refunded orders",
      icon: RotateCcw,
      iconClass: "bg-purple-500/10 text-purple-500",
    },
  ];

  return (
    <section className="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
      {statCards.map((stat) => (
        <StatCard key={stat.label} {...stat} />
      ))}
    </section>
  );
};

export default StatsSection;
