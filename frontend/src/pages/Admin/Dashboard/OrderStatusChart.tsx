import { Cell, Pie, PieChart, ResponsiveContainer, Tooltip } from "recharts";
import type { IOrdersByStatus } from "../../../interface/dashboard";
import { ShoppingCart } from "lucide-react";

const STATUS_COLORS: Record<string, string> = {
  pending: "var(--color-warning)",
  paid: "var(--color-primary)",
  shipped: "var(--color-info)",
  delivered: "var(--color-success)",
  cancelled: "var(--color-error)",
};
const OrderStatusChart = ({ data }: { data: IOrdersByStatus[] }) => {
  return (
    <div className="rounded-2xl border border-border bg-bg-primary p-5 shadow-sm">
      <div className="mb-5 flex items-center justify-between">
        <div>
          <h2 className="font-semibold text-text-primary">Orders by Status</h2>

          <p className="mt-1 text-xs text-text-tertiary">
            Current order distribution
          </p>
        </div>

        <ShoppingCart className="h-5 w-5 text-primary" />
      </div>

      <div className="h-[220px]">
        <ResponsiveContainer width="100%" height="100%">
          <PieChart>
            <Pie
              data={data}
              dataKey="count"
              nameKey="status"
              innerRadius={55}
              outerRadius={80}
              paddingAngle={3}
            >
              {data.map((item) => (
                <Cell
                  key={item.status}
                  fill={STATUS_COLORS[item.status] ?? "var(--color-primary)"}
                />
              ))}
            </Pie>

            <Tooltip />
          </PieChart>
        </ResponsiveContainer>
      </div>

      <div className="space-y-3">
        {data.map((item) => (
          <div key={item.status} className="flex items-center justify-between">
            <div className="flex items-center gap-2">
              <span
                className="h-2.5 w-2.5 rounded-full"
                style={{
                  backgroundColor:
                    STATUS_COLORS[item.status] ?? "var(--color-primary)",
                }}
              />

              <span className="text-sm capitalize text-text-secondary">
                {item.status}
              </span>
            </div>

            <span className="text-sm font-semibold text-text-primary">
              {item.count}
            </span>
          </div>
        ))}
      </div>
    </div>
  );
};

export default OrderStatusChart;
