const ProductCartSkeleton = () => {
  return (
    <div className="cart-item p-6 border-b border-gray-200 flex flex-col md:flex-row animate-pulse">
      {/* Image Skeleton */}
      <div className="md:w-1/4 mb-4 md:mb-0">
        <div className="w-full h-40 bg-gray-300 rounded-lg" />
      </div>

      <div className="md:w-3/4 md:pl-6 w-full">
        {/* Title + Price */}
        <div className="flex justify-between">
          <div className="flex-1">
            <div className="h-4 bg-gray-300 rounded w-3/4 mb-3"></div>

            {/* Rating Skeleton */}
            <div className="flex space-x-2 mt-2">
              <div className="h-3 w-20 bg-gray-300 rounded"></div>
            </div>
          </div>

          <div className="text-right">
            <div className="h-4 bg-gray-300 rounded w-16 mb-2"></div>
            <div className="h-3 bg-gray-300 rounded w-20"></div>
          </div>
        </div>

        {/* Quantity + Actions */}
        <div className="flex justify-between items-center mt-6">
          <div className="flex items-center">
            <div className="h-6 w-28 bg-gray-300 rounded"></div>
          </div>

          <div className="flex space-x-4">
            <div className="h-4 w-24 bg-gray-300 rounded"></div>
            <div className="h-4 w-20 bg-gray-300 rounded"></div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductCartSkeleton;
