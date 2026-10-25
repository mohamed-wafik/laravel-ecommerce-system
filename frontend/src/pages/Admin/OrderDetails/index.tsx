import { Package, Calendar, ArrowLeft, Clock } from "lucide-react";
import { useNavigate, useParams } from "react-router-dom";
import { useEffect } from "react";
import toast from "react-hot-toast";

import type { IOrderItem, OrderStatus } from "../../../interface/order";

import { adminOrderAPI } from "../../../api/modules/Admin/order.api";
import NotFound from "../../../components/Order/NotFound";
import Skeleton from "./Skeleton";
import { formatStatus, statusClass } from "../Orders/OrderRow";
import { formatDate } from "../../../lib/utils";
import OrderItems from "../../../components/Order/OrderItems";
import ShippingAddress from "../../../components/Order/ShippingAddress";
import PaymentDetails from "../../../components/Order/PaymentDetails";
import OrderSummary from "../../../components/Order/OrderSummary";
import OrderInformatin from "./OrderInformatin";
import BillingAddress from "../../../components/Order/BillingAddress";
import CustomerInformation from "../../../components/Order/CustomerInformation";
import SectionCard from "../ProductDetails/SectionCard";

const ORDER_STATUSES: OrderStatus[] = [
  "pending",
  "processing",
  "paid",
  "shipped",
  "delivered",
  "cancelled",
];

const OrderDetails = () => {
  const navigate = useNavigate();

  const { orderId } = useParams<{ orderId: string }>();

  /* ------------------------------------------------------------------------ */
  /* Redirect                                                                 */
  /* ------------------------------------------------------------------------ */

  useEffect(() => {
    if (!orderId) {
      navigate("/admin/orders", { replace: true });
    }
  }, [orderId, navigate]);

  /* ------------------------------------------------------------------------ */
  /* Fetch Order                                                              */
  /* ------------------------------------------------------------------------ */

  const { data, isLoading, isFetching, refetch } = adminOrderAPI.getById(
    orderId ?? "",
  );

  const order = data?.data;

  /* ------------------------------------------------------------------------ */
  /* Loading                                                                  */
  /* ------------------------------------------------------------------------ */

  if (isLoading) {
    return <Skeleton />;
  }

  /* ------------------------------------------------------------------------ */
  /* Not Found                                                                */
  /* ------------------------------------------------------------------------ */

  if (!order) {
    return <NotFound link="/admin/orders" />;
  }

  const totalItems =
    order.items?.reduce(
      (total: number, item: IOrderItem) => total + item.quantity,
      0,
    ) ?? 0;

  /* ------------------------------------------------------------------------ */
  /* Update Status                                                            */
  /* ------------------------------------------------------------------------ */

  const handleStatusChange = async (
    event: React.ChangeEvent<HTMLSelectElement>,
  ) => {
    if (!orderId) return;

    const status = event.target.value as OrderStatus;

    if (status === order.status) return;

    try {
      await adminOrderAPI.updateStatus(orderId, { status });

      await refetch();

      toast.success("Order status updated successfully");
    } catch (error) {
      console.error("Failed to update order status:", error);

      toast.error("Failed to update order status");
    }
  };

  return (
    <section className="space-y-6">
      {/* ================================================================== */}
      {/* HEADER                                                             */}
      {/* ================================================================== */}
      <SectionCard>
        <header className=" p-4 sm:p-5">
          <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            {/* Order Info */}

            <div className="flex min-w-0 items-start gap-3">
              <button
                type="button"
                onClick={() => navigate("/admin/orders")}
                className="mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-border bg-bg-primary text-text-secondary transition hover:bg-bg-secondary hover:text-text-primary"
                aria-label="Back to orders"
              >
                <ArrowLeft size={18} />
              </button>

              <div className="min-w-0">
                <div className="flex min-w-0 items-center gap-2">
                  <Package size={20} className="shrink-0 text-primary" />

                  <h1 className="truncate text-xl font-bold text-text-primary sm:text-2xl">
                    #{order.order_number}
                  </h1>
                </div>

                <div className="mt-1 flex flex-wrap items-center gap-2 text-xs text-text-secondary sm:text-sm">
                  <Calendar size={14} />

                  <span>{formatDate(order.created_at)}</span>

                  <span className="hidden sm:inline">•</span>

                  <span>
                    {totalItems} item
                    {totalItems !== 1 ? "s" : ""}
                  </span>
                </div>
              </div>
            </div>

            {/* Status */}

            <select
              value={order.status}
              onChange={handleStatusChange}
              disabled={isFetching}
              className={`w-full rounded-full border-0 px-4 py-2.5 text-xs font-semibold outline-none transition focus:ring-2 focus:ring-primary/10 disabled:cursor-not-allowed disabled:opacity-60 lg:w-auto ${statusClass(
                order.status,
              )}`}
            >
              {ORDER_STATUSES.map((status) => (
                <option key={status} value={status}>
                  {formatStatus(status)}
                </option>
              ))}
            </select>
          </div>

          {isFetching && !isLoading && (
            <div className="mt-3 flex items-center gap-2 text-xs text-text-secondary">
              <Clock size={14} />
              Updating order...
            </div>
          )}
        </header>
      </SectionCard>

      {/* ================================================================== */}
      {/* CONTENT                                                            */}
      {/* ================================================================== */}

      <div className="grid gap-6 lg:grid-cols-3">
        {/* ================================================================= */}
        {/* MAIN                                                              */}
        {/* ================================================================= */}

        <main className="space-y-6 lg:col-span-2">
          {/* =============================================================== */}
          {/* CUSTOMER                                                        */}
          {/* =============================================================== */}
          <CustomerInformation user={order.user} />

          {/* =============================================================== */}
          {/* ORDER ITEMS                                                     */}
          {/* =============================================================== */}
          <OrderItems order={order} />

          {/* =============================================================== */}
          {/* SHIPPING ADDRESS                                                */}
          {/* =============================================================== */}
          <ShippingAddress order={order} />

          {/* =============================================================== */}
          {/* BILLING ADDRESS                                                 */}
          {/* =============================================================== */}
          <BillingAddress order={order} />
        </main>

        {/* ================================================================= */}
        {/* SIDEBAR                                                           */}
        {/* ================================================================= */}

        <aside className="space-y-6">
          {/* =============================================================== */}
          {/* PAYMENT                                                         */}
          {/* =============================================================== */}
          <PaymentDetails order={order} />

          {/* =============================================================== */}
          {/* ORDER SUMMARY                                                   */}
          {/* =============================================================== */}
          <OrderSummary order={order} />

          {/* =============================================================== */}
          {/* ORDER INFORMATION                                               */}
          {/* =============================================================== */}
          <OrderInformatin order={order} />
        </aside>
      </div>
    </section>
  );
};

export default OrderDetails;
