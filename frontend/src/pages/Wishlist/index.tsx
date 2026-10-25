import { useState } from "react";
import { Link } from "react-router-dom";
import { Heart, Trash2, ChevronRight } from "lucide-react";
import useWishlist from "../../store/useWishlist";
import Breadcrumb from "../../components/ui/Breadcrumb";
import EmptyWishlist from "./EmptyWishlist";
import WishlistCard from "./WishlistCard";

// ─── Page ──────────────────────────────────────────────────────────────────────
const WishlistPage = () => {
  const { items, clearWishlist } = useWishlist();
  const [showClearConfirm, setShowClearConfirm] = useState(false);

  return (
    <section className="py-10 bg-gray-50 min-h-screen">
      <div className="container mx-auto px-4 max-w-7xl">
        {/* ── Header ── */}
        <div className="flex items-center justify-between mb-8">
          <div className="flex items-center gap-4">
            <div className="w-1 h-7 bg-primary rounded-full" />
            <div>
              <h1 className="text-2xl font-extrabold text-dark leading-none">
                My Wishlist
              </h1>
              {items.length > 0 && (
                <p className="text-sm text-gray-400 mt-1">
                  {items.length} saved {items.length === 1 ? "item" : "items"}
                </p>
              )}
            </div>
          </div>

          {/* Breadcrumb */}
          <Breadcrumb
            items={[{ label: "Home", href: "/" }, { label: "Wishlist" }]}
          />
        </div>

        {items.length === 0 ? (
          <EmptyWishlist />
        ) : (
          <>
            {/* ── Toolbar ── */}
            <div className="flex items-center justify-between bg-white rounded-xl border border-gray-100 px-5 py-3 mb-6">
              <p className="text-sm text-gray-500">
                <span className="font-semibold text-dark">{items.length}</span>{" "}
                {items.length === 1 ? "product" : "products"} saved
              </p>

              <div className="flex items-center gap-3">
                {/* Clear all — with inline confirm */}
                {showClearConfirm ? (
                  <div className="flex items-center gap-2">
                    <span className="text-xs text-gray-500">Remove all?</span>
                    <button
                      onClick={() => {
                        clearWishlist();
                        setShowClearConfirm(false);
                      }}
                      className="text-xs font-semibold text-red-500 hover:text-red-600 transition-colors"
                    >
                      Yes, clear
                    </button>
                    <button
                      onClick={() => setShowClearConfirm(false)}
                      className="text-xs text-gray-400 hover:text-gray-600 transition-colors"
                    >
                      Cancel
                    </button>
                  </div>
                ) : (
                  <button
                    onClick={() => setShowClearConfirm(true)}
                    className="flex items-center gap-1.5 text-xs text-gray-400 hover:text-red-500 transition-colors"
                  >
                    <Trash2 className="w-3.5 h-3.5" />
                    Clear all
                  </button>
                )}
              </div>
            </div>

            {/* ── Grid ── */}
            <div className="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4">
              {items.map((product) => (
                <WishlistCard key={product.id} product={product} />
              ))}
            </div>

            {/* ── Footer CTA ── */}
            <div className="mt-10 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-2xl border border-gray-100 p-6">
              <div className="flex items-center gap-3">
                <Heart className="w-5 h-5 text-primary fill-primary/20" />
                <p className="text-sm text-gray-600">
                  Items are saved locally and won't expire.
                </p>
              </div>
              <Link
                to="/"
                className="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:opacity-80 transition-opacity"
              >
                Continue browsing
                <ChevronRight className="w-4 h-4" />
              </Link>
            </div>
          </>
        )}
      </div>
    </section>
  );
};

export default WishlistPage;
