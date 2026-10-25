import { Heart, ChevronRight } from "lucide-react";
import { Link } from "react-router-dom";

const EmptyWishlist = () => (
  <div className="flex flex-col items-center justify-center py-24 text-center">
    <div className="w-20 h-20 rounded-full bg-primary/8 flex items-center justify-center mb-6">
      <Heart className="w-9 h-9 text-primary/40" />
    </div>
    <h2 className="text-2xl font-bold text-dark mb-2">
      Your wishlist is empty
    </h2>
    <p className="text-sm text-gray-400 mb-8 max-w-xs">
      Save products you love and come back to them anytime.
    </p>
    <Link
      to="/shop"
      className="inline-flex items-center gap-2 bg-primary text-white text-sm font-semibold px-6 py-3 rounded-xl hover:opacity-90 transition-opacity"
    >
      Browse products
      <ChevronRight className="w-4 h-4" />
    </Link>
  </div>
);

export default EmptyWishlist;
