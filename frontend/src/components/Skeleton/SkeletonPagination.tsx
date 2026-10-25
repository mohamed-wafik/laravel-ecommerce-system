const SkeletonPagination = () => {
  return (
    <div className="flex items-center justify-between gap-3 py-4 animate-pulse">
      {/* Left info skeleton */}
      <div className="h-4 w-32 bg-gray-200 rounded"></div>

      {/* Button skeletons */}
      <div className="flex items-center gap-3">
        <div className="h-10 w-20 bg-gray-200 rounded-md"></div>
        <div className="h-10 w-20 bg-gray-200 rounded-md"></div>
      </div>
    </div>
  );
};

export default SkeletonPagination;
