import { ArrowLeft } from "lucide-react";
import { useNavigate, useParams } from "react-router-dom";
import { useEffect } from "react";

import { orderAPI } from "../../api/modules/Customer/order.api";
import Breadcrumb from "../../components/ui/Breadcrumb";
import type { IOrder } from "../../interface/order";
import { formatCurrency, formatDate } from "../../lib/utils";
import OrderDetailsSkeleton from "./OrderDetailsSkeleton";
import StatusBadge from "../Orders/StatusBadge";
import PaymentDetails from "../../components/Order/PaymentDetails";
import OrderSummary from "../../components/Order/OrderSummary";
import ShippingAddress from "../../components/Order/ShippingAddress";
import OrderItems from "../../components/Order/OrderItems";
import NotFound from "../../components/Order/NotFound";

const OrderDetails = () => {
  const { orderId } = useParams<{ orderId: string }>();
  const navigate = useNavigate();

  useEffect(() => {
    if (!orderId) {
      navigate("/orders", { replace: true });
    }
  }, [orderId, navigate]);

  const { data, isLoading } = orderAPI.getById(orderId ?? "");

  const order: IOrder | undefined = data?.data;

  if (!orderId) {
    return null;
  }

  if (isLoading) {
    return <OrderDetailsSkeleton />;
  }

  if (!order) {
    return <NotFound link="/orders" />;
  }

  return (
    <section className="min-h-screen bg-gray-50 py-8 sm:py-10">
      <div className="container mx-auto max-w-6xl px-4">
        <Breadcrumb
          items={[
            { label: "Home", href: "/" },
            { label: "Orders", href: "/orders" },
            { label: order.order_number },
          ]}
        />

        {/* Header */}
        <div className="mb-6 flex flex-col gap-4 sm:mb-8 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <button
              type="button"
              onClick={() => navigate("/orders")}
              className="mb-3 inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-primary"
            >
              <ArrowLeft size={16} />
              Back to Orders
            </button>

            <div className="flex flex-wrap items-center gap-3">
              <h1 className="text-xl font-extrabold text-dark sm:text-2xl">
                {order.order_number}
              </h1>

              <StatusBadge status={order.status} />
            </div>

            <p className="mt-1 text-sm text-gray-500">
              Placed on {formatDate(order.created_at)}
            </p>
          </div>

          <div className="rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm">
            <p className="text-xs font-medium uppercase tracking-wide text-gray-400">
              Order Total
            </p>

            <p className="mt-1 text-lg font-extrabold text-dark">
              {formatCurrency(Number(order.total), order.currency)}
            </p>
          </div>
        </div>

        {/* Order Summary */}
        <div className="grid gap-6 lg:grid-cols-3">
          {/* Main */}
          <div className="space-y-6 lg:col-span-2">
            <OrderItems order={order} />

            <ShippingAddress order={order} />
          </div>

          {/* Sidebar */}
          <div className="space-y-6">
            <OrderSummary order={order} />

            <PaymentDetails order={order} />
          </div>
        </div>
      </div>
    </section>
  );
};

export default OrderDetails;
