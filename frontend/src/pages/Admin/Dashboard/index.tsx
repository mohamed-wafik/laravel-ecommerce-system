import {
  BarChart3,
  DollarSign,
  Package,
  ShoppingCart,
  TrendingUp,
  AlertTriangle,
} from "lucide-react";

import {
  Area,
  AreaChart,
  CartesianGrid,
  ResponsiveContainer,
  Tooltip,
  XAxis,
  YAxis,
} from "recharts";

import { useState } from "react";

import StatCard from "./StatCard";

import { adminDashboardAPI } from "../../../api/modules/Admin/adminDashboard.api";
import DashboardSkeleton from "./DashboardSkeleton";
import OrderStatusChart from "./OrderStatusChart";
import TopProducts from "./TopProducts";
import SalesByCategory from "./SalesByCategory";
import CustomerCard from "./CustomerCard";
import InventoryCard from "./InventoryCard";
import { formatCurrency, formatDate } from "../../../lib/utils";

const AdminDashboard = () => {
  const [days, setDays] = useState(30);

  const { data, isLoading, error } = adminDashboardAPI.dashboard(days);

  const dashboard = data?.data;

  if (isLoading) {
    return <DashboardSkeleton />;
  }

  if (error || !dashboard) {
    return (
      <div className="flex min-h-[400px] items-center justify-center">
        <div className="rounded-2xl border border-error-100 bg-error-50 px-6 py-5 text-center">
          <AlertTriangle className="mx-auto h-8 w-8 text-error" />

          <p className="mt-3 font-semibold text-text-primary">
            Failed to load dashboard
          </p>

          <p className="mt-1 text-sm text-text-secondary">
            Please try again later.
          </p>
        </div>
      </div>
    );
  }

  const {
    summary,
    sales,
    orders_by_status,
    top_products,
    sales_by_category,
    customers,
    inventory,
  } = dashboard;

  return (
    <main className="min-h-screen bg-bg-secondary p-4 sm:p-6 lg:p-8">
      {/* Header */}
      <div className="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-bold text-text-primary sm:text-3xl">
            Dashboard
          </h1>

          <p className="mt-1 text-sm text-text-secondary">
            Monitor your store performance and sales activity.
          </p>
        </div>

        <div className="flex rounded-xl border border-border bg-bg-primary p-1 shadow-sm">
          {[7, 30, 90, 365].map((value) => (
            <button
              key={value}
              type="button"
              onClick={() => setDays(value)}
              className={`rounded-lg px-3 py-2 text-xs font-medium transition-colors sm:px-4 ${
                days === value
                  ? "bg-primary text-white"
                  : "text-text-secondary hover:bg-bg-tertiary"
              }`}
            >
              {value === 365 ? "1Y" : `${value}D`}
            </button>
          ))}
        </div>
      </div>

      {/* Stats */}
      <section className="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <StatCard
          label="Total Revenue"
          value={formatCurrency(summary.revenue)}
          description={`Revenue in the last ${days} days`}
          icon={DollarSign}
          iconClass="bg-success-50 text-success"
        />

        <StatCard
          label="Total Orders"
          value={summary.orders.toLocaleString()}
          description={`Orders in the last ${days} days`}
          icon={ShoppingCart}
          iconClass="bg-primary-50 text-primary"
        />

        <StatCard
          label="Products Sold"
          value={summary.products_sold.toLocaleString()}
          description="Items sold successfully"
          icon={Package}
          iconClass="bg-secondary-50 text-secondary"
        />

        <StatCard
          label="Average Order"
          value={formatCurrency(summary.average_order_value)}
          description="Average value per order"
          icon={TrendingUp}
          iconClass="bg-accent-50 text-accent-dark"
        />
      </section>

      {/* Sales + Order Status */}
      <section className="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div className="rounded-2xl border border-border bg-bg-primary p-5 shadow-sm xl:col-span-2">
          <div className="mb-6 flex items-center justify-between">
            <div>
              <h2 className="font-semibold text-text-primary">
                Sales Overview
              </h2>

              <p className="mt-1 text-xs text-text-tertiary">
                Revenue and orders by day
              </p>
            </div>

            <BarChart3 className="h-5 w-5 text-primary" />
          </div>

          <div className="h-[300px] w-full">
            <ResponsiveContainer width="100%" height="100%">
              <AreaChart data={sales}>
                <defs>
                  <linearGradient
                    id="salesGradient"
                    x1="0"
                    y1="0"
                    x2="0"
                    y2="1"
                  >
                    <stop
                      offset="0%"
                      stopColor="var(--color-primary)"
                      stopOpacity={0.25}
                    />

                    <stop
                      offset="100%"
                      stopColor="var(--color-primary)"
                      stopOpacity={0}
                    />
                  </linearGradient>
                </defs>

                <CartesianGrid
                  stroke="var(--color-border-light)"
                  vertical={false}
                />

                <XAxis
                  dataKey="date"
                  tickFormatter={formatDate}
                  tick={{
                    fill: "var(--color-text-tertiary)",
                    fontSize: 11,
                  }}
                  axisLine={false}
                  tickLine={false}
                />

                <YAxis
                  tick={{
                    fill: "var(--color-text-tertiary)",
                    fontSize: 11,
                  }}
                  axisLine={false}
                  tickLine={false}
                  tickFormatter={(value) => `$${value}`}
                />

                <Tooltip
                  formatter={(value, name) => [
                    name === "revenue" ? formatCurrency(Number(value)) : value,
                    name === "revenue" ? "Revenue" : "Orders",
                  ]}
                  labelFormatter={(label) => formatDate(String(label))}
                />

                <Area
                  type="monotone"
                  dataKey="revenue"
                  stroke="var(--color-primary)"
                  strokeWidth={2.5}
                  fill="url(#salesGradient)"
                />
              </AreaChart>
            </ResponsiveContainer>
          </div>
        </div>

        <OrderStatusChart data={orders_by_status} />
      </section>

      {/* Products + Categories */}
      <section className="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2">
        <TopProducts products={top_products} />

        <SalesByCategory categories={sales_by_category} />
      </section>

      {/* Customers + Inventory */}
      <section className="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <CustomerCard customers={customers} />

        <InventoryCard inventory={inventory} />
      </section>
    </main>
  );
};

export default AdminDashboard;
