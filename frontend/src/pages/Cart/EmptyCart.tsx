import { ShoppingBag, ArrowRight } from "lucide-react";
import { Link } from "react-router-dom";

const EmptyCart = () => (
  <div className="flex flex-col items-center justify-center py-20 px-6 text-center">
    <div className="relative mb-6">
      <div className="w-24 h-24 bg-primary/8 rounded-full flex items-center justify-center">
        <ShoppingBag className="w-11 h-11 text-primary/40" />
      </div>
      <div className="absolute -top-1 -right-1 w-7 h-7 bg-gray-200 rounded-full flex items-center justify-center">
        <span className="text-xs font-bold text-gray-500">0</span>
      </div>
    </div>

    <h3 className="text-xl font-extrabold text-dark mb-2">
      Your cart is empty
    </h3>
    <p className="text-sm text-gray-400 max-w-xs mb-8">
      Looks like you haven't added anything yet. Start browsing and find
      something you love.
    </p>

    <Link
      to="/"
      className="inline-flex items-center gap-2 bg-primary text-white text-sm font-bold px-6 py-3 rounded-xl hover:opacity-90 transition-opacity"
    >
      Browse products
      <ArrowRight className="w-4 h-4" />
    </Link>
  </div>
);

export default EmptyCart;
