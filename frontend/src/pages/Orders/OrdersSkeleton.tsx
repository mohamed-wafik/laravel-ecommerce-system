const Skeleton = ({
  width,
  height = "h-4",
}: {
  width: string;
  height?: string;
}) => {
  return (
    <div className={`${width} ${height} animate-pulse rounded bg-gray-200`} />
  );
};

const OrdersSkeleton = () => {
  return (
    <>
      {/* Desktop skeleton */}
      <div className="hidden overflow-hidden rounded-2xl border border-gray-200 bg-white md:block">
        <div className="border-b border-gray-200 bg-gray-50 px-6 py-4">
          <div className="h-4 w-24 animate-pulse rounded bg-gray-200" />
        </div>

        <div className="divide-y divide-gray-100">
          {Array.from({ length: 5 }).map((_, index) => (
            <div
              key={index}
              className="grid grid-cols-7 items-center gap-4 px-6 py-5"
            >
              <Skeleton width="w-32" />
              <Skeleton width="w-24" />
              <Skeleton width="w-16" />
              <Skeleton width="w-20" />
              <Skeleton width="w-20" />
              <Skeleton width="w-20" />
              <Skeleton width="w-9" />
            </div>
          ))}
        </div>
      </div>

      {/* Mobile skeleton */}
      <div className="space-y-4 md:hidden">
        {Array.from({ length: 3 }).map((_, index) => (
          <div
            key={index}
            className="rounded-2xl border border-gray-200 bg-white p-4"
          >
            <div className="flex items-center justify-between">
              <div className="flex gap-3">
                <Skeleton width="w-10" height="h-10" />
                <div className="space-y-2">
                  <Skeleton width="w-32" />
                  <Skeleton width="w-24" />
                </div>
              </div>

              <Skeleton width="w-20" />
            </div>

            <div className="mt-5 space-y-3">
              <Skeleton width="w-full" height="h-12" />
              <Skeleton width="w-full" />
              <Skeleton width="w-3/4" />
              <Skeleton width="w-full" height="h-10" />
            </div>
          </div>
        ))}
      </div>
    </>
  );
};

export default OrdersSkeleton;
