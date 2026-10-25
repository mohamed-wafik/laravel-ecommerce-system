import { ChevronRight, Zap } from "lucide-react";

import { productAPI } from "../../api/modules/Guest/product.api";
import { ProductCardSkeleton } from "../../components/Skeleton/ProductCardSkeleton";
import SliderProducts from "../../components/SliderProduct";
import { Link } from "react-router-dom";
import LayoutSection from "./LayoutSection";

const TopDealsToday = () => {
  const { isLoading, data } = productAPI.getTopDeals();
  const renderProductSkeleton = Array.from({ length: 4 }).map((_, idx) => (
    <div key={idx} className="shrink-0 w-full md:w-1/3 lg:w-1/4 px-3">
      <ProductCardSkeleton />
    </div>
  ));

  return (
    <LayoutSection
      id="top-deals-section"
      title="Today's"
      titleHighlight="Top Deals"
      subTitle="Don't miss out on these exclusive offers. Limited time only!"
      label="Hot Deals"
      Icon={Zap}
    >
      {/* Layout: Slider + Banner */}
      <div className="flex justify-between items-center mb-6 md:mb-8">
        {/* Title */}
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 md:w-12 md:h-12 bg-linear-to-r from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
            <Zap className="w-5 h-5 md:w-6 md:h-6 text-white" />
          </div>

          <h3 className="text-2xl md:text-3xl font-bold text-gray-900">
            Flash Sales
          </h3>

          {/* Animated dots */}
          <div className="hidden sm:flex gap-1">
            {[1, 2, 3].map((dot) => (
              <div
                key={dot}
                className="w-2 h-2 bg-orange-500 rounded-full animate-pulse"
                style={{ animationDelay: `${dot * 0.2}s` }}
              />
            ))}
          </div>
        </div>

        {/* View All */}
        <Link
          to="/shop"
          className="group flex items-center gap-1 md:gap-2 text-sm md:text-lg font-semibold text-gray-700 hover:text-blue-600 transition-colors"
        >
          View All
          <ChevronRight className="w-4 h-4 md:w-5 md:h-5 transition-transform group-hover:translate-x-1" />
        </Link>
      </div>

      {/* Slider responsive count */}
      {isLoading ? (
        <div className="flex overflow-hidden">{renderProductSkeleton}</div>
      ) : data && data.data.length ? (
        <SliderProducts
          products={data.data}
          Count={4} // Desktop
        />
      ) : (
        <div className="flex flex-col items-center justify-center py-10">
          <svg
            className="w-12 h-12 text-gray-300 mb-3"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth={1.5}
              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <p className="text-gray-400 font-medium text-center">
            No top deals found today
          </p>
        </div>
      )}
    </LayoutSection>
  );
};

export default TopDealsToday;
