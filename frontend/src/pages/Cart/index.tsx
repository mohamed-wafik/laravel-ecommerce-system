import { ArrowLeft, Trash2 } from "lucide-react";

import ProductCardCart from "./ProductCardCart";

import { Link } from "react-router-dom";
import { useState } from "react";
import ProductCartSkeleton from "../../components/Skeleton/ProductCartSkeleton";
import Button from "../../components/ui/Button";
import { useCart } from "../../store/useCart";
import OrderSummary from "./OrderSummary";
import Alert from "../../components/ui/Alert";
const CartPage = () => {
  const { cartItems, countItems, isLoadingCart, subtotal, discount, clear } =
    useCart();

  const [isShow, setIsShow] = useState<boolean>(false);
  const [isLoading, setIsLoading] = useState<boolean>(false);

  const renderProductSkeleton = Array.from({ length: 4 }).map((_, idx) => (
    <ProductCartSkeleton key={idx} />
  ));

  const handleClear = async () => {
    setIsLoading(true);
    await clear();
    setIsLoading(false);
    setIsShow(false);
  };

  return (
    <>
      <main className="py-8">
        <div className="container mx-auto px-4">
          <h1 className="text-3xl font-bold text-dark mb-2">
            Your Shopping Cart
          </h1>
          <p className="text-gray-600 mb-8">
            Review your items and proceed to checkout
          </p>

          <div className="flex flex-col lg:flex-row gap-8">
            {/* Cart Items */}
            <div className="lg:w-2/3 space-y-6">
              <div className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="p-6 border-b border-gray-200">
                  <h2 className="text-xl font-semibold text-dark">
                    Cart Items ({countItems})
                  </h2>
                </div>

                {isLoadingCart ? (
                  renderProductSkeleton
                ) : countItems === 0 ? (
                  <div className="flex flex-col items-center justify-center min-h-[400px] p-6">
                    {/* Large shopping cart illustration */}
                    <div className="relative mb-6">
                      <div className="w-24 h-24 rounded-full bg-linear-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        <svg
                          className="w-12 h-12 text-gray-400"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth={1.2}
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                          />
                        </svg>
                      </div>
                      {/* Empty indicator */}
                      <div className="absolute -top-1 -right-1 w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                        <span className="text-xs font-semibold text-gray-700">
                          0
                        </span>
                      </div>
                    </div>

                    {/* Text content */}
                    <h3 className="text-xl font-bold text-gray-800 mb-2">
                      Your shopping cart is empty
                    </h3>
                    <p className="text-gray-500 text-center mb-8 max-w-md">
                      Add items to your cart and they will appear here. Ready to
                      start shopping?
                    </p>
                  </div>
                ) : (
                  cartItems.map((item) => (
                    <ProductCardCart
                      key={item.id}
                      product={item.product}
                      variant={item.variant}
                      id={item.id}
                      priceSnapshot={item.price_snapshot}
                      quantity={item.quantity}
                    />
                  ))
                )}
              </div>

              {/* Continue Shopping / Update Cart */}
              <div className="mt-6 flex justify-between items-center">
                <Link
                  to={"/shop"}
                  className="text-primary hover:text-secondary font-semibold transition-colors flex items-center"
                >
                  <ArrowLeft className="w-4 h-4 mr-2" /> Continue Shopping
                </Link>
                {countItems ? (
                  <Button
                    variant="destructive"
                    size="sm"
                    onClick={() => setIsShow(true)}
                    className="flex items-center gap-1.5"
                  >
                    <Trash2 className="w-3.5 h-3.5" />
                    Clear Cart
                  </Button>
                ) : null}
              </div>
            </div>

            {/* Order Summary */}
            <OrderSummary subtotal={subtotal} productDiscount={+discount} />
          </div>
        </div>
      </main>
      {isShow && (
        <Alert
          title="Clear entire cart?"
          subTitle="All items will be removed. This action cannot be undone."
          onClose={() => setIsShow(false)}
        >
          <Button
            onClick={() => setIsShow(false)}
            disabled={isLoading}
            variant={"light"}
            width={"full"}
          >
            Cancel
          </Button>

          <Button
            variant="destructive"
            loading={isLoading}
            width={"full"}
            onClick={handleClear}
          >
            {!isLoading && <Trash2 className="w-3.5 h-3.5 mr-1.5" />}
            Clear
          </Button>
        </Alert>
      )}
    </>
  );
};

export default CartPage;
