import { ArrowLeft, MapPin } from "lucide-react";
import { Link } from "react-router-dom";

const CartIsEmpty = () => {
  return (
    <div className="mx-auto p-6 sm:p-8">
      <div className="rounded-2xl border border-border bg-bg-primary p-8 text-center">
        <div className="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full bg-primary-50 text-primary">
          <MapPin className="h-6 w-6" />
        </div>

        <h1 className="mb-2 text-2xl font-semibold text-text-primary">
          Your cart is empty
        </h1>

        <p className="mb-6 text-sm text-text-secondary">
          Add products to your cart before continuing to checkout.
        </p>

        <Link
          to="/shop"
          className="inline-flex h-11 items-center gap-2 rounded-xl bg-primary px-5 text-sm font-semibold text-white transition-colors hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary-100"
        >
          <ArrowLeft className="h-4 w-4" />
          Continue Shopping
        </Link>
      </div>
    </div>
  );
};

export default CartIsEmpty;
