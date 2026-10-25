export const ProductCardSkeleton = () => {
  return (
    <div className="relative bg-white rounded-2xl p-4 border border-gray-400 shadow-sm animate-pulse">
      {/* Discount badge */}
      <div className="absolute top-4 left-4">
        <div className="w-16 h-6 bg-gray-200 rounded-full"></div>
      </div>

      {/* Wishlist button */}
      <div className="absolute top-4 right-4">
        <div className="w-10 h-10 bg-gray-200 rounded-full"></div>
      </div>

      {/* Image */}
      <div className="w-full h-48 bg-gray-200 rounded-xl mb-4"></div>

      {/* Rating */}
      <div className="flex items-center gap-2 mb-2">
        <div className="flex gap-1">
          {[...Array(5)].map((_, i) => (
            <div key={i} className="w-4 h-4 bg-gray-200 rounded"></div>
          ))}
        </div>
        <div className="w-8 h-4 bg-gray-200 rounded"></div>
      </div>

      {/* Title */}
      <div className="w-3/4 h-5 bg-gray-200 rounded mb-2"></div>

      {/* Description lines */}
      <div className="w-full h-4 bg-gray-200 rounded mb-1"></div>
      <div className="w-5/6 h-4 bg-gray-200 rounded mb-3"></div>

      {/* Price + Button */}
      <div className="flex items-center justify-between">
        <div className="flex gap-2">
          <div className="w-16 h-6 bg-gray-200 rounded"></div>
          <div className="w-12 h-5 bg-gray-200 rounded"></div>
        </div>

        <div className="w-10 h-10 bg-gray-200 rounded-lg"></div>
      </div>
    </div>
  );
};
