const OrderSummarySkeleton = () => {
  return (
    <div className="w-full lg:w-1/3">
      <div className="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5 lg:p-6 animate-pulse">
        {/* Header */}
        <div className="flex items-center gap-3 mb-5">
          <div className="w-1 h-5 rounded-full bg-gray-200" />
          <div className="w-32 h-5 rounded bg-gray-200" />
        </div>

        {/* Rows */}
        <div className="space-y-4 mb-5">
          <SkeletonRow />
          <SkeletonRow />
          <SkeletonRow />
          <SkeletonRow />

          <div className="h-px bg-gray-100" />

          <div className="flex justify-between">
            <div className="w-14 h-5 rounded bg-gray-200" />
            <div className="w-24 h-5 rounded bg-gray-200" />
          </div>
        </div>

        {/* Free shipping message */}
        <div className="h-12 rounded-xl bg-gray-100 mb-4" />

        {/* Button */}
        <div className="w-full h-12 rounded-xl bg-gray-200 mb-4" />

        {/* Security */}
        <div className="flex justify-center gap-2 mb-5">
          <div className="w-4 h-4 rounded bg-gray-200" />
          <div className="w-40 h-3 rounded bg-gray-200" />
        </div>

        {/* Payment */}
        <div className="border-t border-gray-100 pt-5">
          <div className="w-16 h-3 rounded bg-gray-200 mb-3" />

          <div className="grid grid-cols-4 gap-2">
            <div className="h-8 rounded-lg bg-gray-100" />
            <div className="h-8 rounded-lg bg-gray-100" />
            <div className="h-8 rounded-lg bg-gray-100" />
            <div className="h-8 rounded-lg bg-gray-100" />
          </div>
        </div>
      </div>
    </div>
  );
};

const SkeletonRow = () => {
  return (
    <div className="flex items-center justify-between gap-4">
      <div className="w-20 h-3.5 rounded bg-gray-200" />
      <div className="w-24 h-3.5 rounded bg-gray-200" />
    </div>
  );
};

export default OrderSummarySkeleton;
