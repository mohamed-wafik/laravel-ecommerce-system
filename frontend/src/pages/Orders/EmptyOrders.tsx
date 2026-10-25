import { Package, Truck } from "lucide-react";
import { Link } from "react-router-dom";

const EmptyOrders = () => {
  return (
    <div className="rounded-2xl border border-gray-200 bg-white px-5 py-16 text-center shadow-sm">
      <div className="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary-50 text-primary">
        <Package size={28} />
      </div>

      <h2 className="mt-5 text-lg font-bold text-dark">No orders yet</h2>

      <p className="mx-auto mt-2 max-w-sm text-sm text-gray-500">
        You haven't placed any orders yet. Start shopping and your orders will
        appear here.
      </p>

      <Link
        to="/products"
        className="mt-6 inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-bold text-white transition hover:bg-primary-dark"
      >
        Start Shopping
        <Truck size={16} />
      </Link>
    </div>
  );
};
export default EmptyOrders;
