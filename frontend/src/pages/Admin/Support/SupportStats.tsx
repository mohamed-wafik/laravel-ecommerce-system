import { CheckCircle2, CircleDot, Clock3, Lock } from "lucide-react";

import StatCard from "../Dashboard/StatCard";
import { adminSupportAPI } from "../../../api/modules/Admin/contactSupport.api";

const SupportStats = () => {
  const { data, isLoading } = adminSupportAPI.stats();

  if (isLoading) {
    return (
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {Array.from({ length: 4 }).map((_, index) => (
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
      label: "Open",
      value: stats.open,
      description: "Tickets waiting for support",
      icon: CircleDot,
      iconClass: "bg-blue-500/10 text-blue-500",
    },
    {
      label: "In Progress",
      value: stats.in_progress,
      description: "Tickets currently being handled",
      icon: Clock3,
      iconClass: "bg-amber-500/10 text-amber-500",
    },
    {
      label: "Resolved",
      value: stats.resolved,
      description: "Successfully resolved tickets",
      icon: CheckCircle2,
      iconClass: "bg-emerald-500/10 text-emerald-500",
    },
    {
      label: "Closed",
      value: stats.closed,
      description: "Closed support tickets",
      icon: Lock,
      iconClass: "bg-gray-500/10 text-gray-500",
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

export default SupportStats;
