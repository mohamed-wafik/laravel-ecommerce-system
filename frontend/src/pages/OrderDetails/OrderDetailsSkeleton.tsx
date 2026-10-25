const OrderDetailsSkeleton = () => {
  return (
    <section className="min-h-screen bg-gray-50 py-8 sm:py-10">
      <div className="container mx-auto max-w-6xl px-4">
        {/* Breadcrumb */}
        <div className="mb-6 flex gap-2">
          <div className="h-4 w-14 animate-pulse rounded bg-gray-200" />
          <div className="h-4 w-4 animate-pulse rounded bg-gray-200" />
          <div className="h-4 w-24 animate-pulse rounded bg-gray-200" />
        </div>

        {/* Header */}
        <div className="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div className="space-y-3">
            <div className="h-4 w-28 animate-pulse rounded bg-gray-200" />

            <div className="flex items-center gap-3">
              <div className="h-7 w-48 animate-pulse rounded bg-gray-200" />
              <div className="h-6 w-20 animate-pulse rounded-full bg-gray-200" />
            </div>

            <div className="h-4 w-40 animate-pulse rounded bg-gray-200" />
          </div>

          <div className="h-20 w-full animate-pulse rounded-xl bg-gray-200 sm:w-40" />
        </div>

        <div className="grid gap-6 lg:grid-cols-3">
          {/* Main */}
          <div className="space-y-6 lg:col-span-2">
            {/* Items */}
            <SkeletonCard>
              <div className="mb-5 flex items-center gap-3">
                <SkeletonCircle />
                <div className="space-y-2">
                  <SkeletonLine width="w-24" />
                  <SkeletonLine width="w-32" />
                </div>
              </div>

              <div className="space-y-5">
                <SkeletonItem />
                <SkeletonItem />
              </div>
            </SkeletonCard>

            {/* Address */}
            <SkeletonCard>
              <div className="mb-5 flex items-center gap-3">
                <SkeletonCircle />
                <div className="space-y-2">
                  <SkeletonLine width="w-32" />
                  <SkeletonLine width="w-40" />
                </div>
              </div>

              <div className="space-y-3 rounded-xl bg-gray-50 p-4">
                <SkeletonLine width="w-36" />
                <SkeletonLine width="w-32" />
                <SkeletonLine width="w-52" />
                <SkeletonLine width="w-44" />
              </div>
            </SkeletonCard>
          </div>

          {/* Sidebar */}
          <div className="space-y-6">
            <SkeletonCard>
              <div className="mb-5 flex items-center gap-3">
                <SkeletonCircle />
                <SkeletonLine width="w-32" />
              </div>

              <div className="space-y-5">
                <SkeletonRow />
                <SkeletonRow />
                <SkeletonRow />
                <div className="border-t border-gray-200 pt-4">
                  <SkeletonRow />
                </div>
              </div>
            </SkeletonCard>

            <SkeletonCard>
              <div className="mb-5 flex items-center gap-3">
                <SkeletonCircle />
                <SkeletonLine width="w-20" />
              </div>

              <div className="space-y-5">
                <SkeletonRow />
                <SkeletonRow />
                <SkeletonRow />
              </div>
            </SkeletonCard>
          </div>
        </div>
      </div>
    </section>
  );
};

const SkeletonCard = ({ children }: { children: React.ReactNode }) => {
  return (
    <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
      {children}
    </div>
  );
};

const SkeletonCircle = () => {
  return (
    <div className="h-9 w-9 shrink-0 animate-pulse rounded-lg bg-gray-200" />
  );
};

const SkeletonLine = ({ width }: { width: string }) => {
  return <div className={`h-4 ${width} animate-pulse rounded bg-gray-200`} />;
};

const SkeletonRow = () => {
  return (
    <div className="flex items-center justify-between gap-4">
      <SkeletonLine width="w-20" />
      <SkeletonLine width="w-24" />
    </div>
  );
};

const SkeletonItem = () => {
  return (
    <div className="flex items-center justify-between gap-4">
      <div className="flex items-center gap-4">
        <div className="h-12 w-12 animate-pulse rounded-xl bg-gray-200" />

        <div className="space-y-2">
          <SkeletonLine width="w-40" />
          <SkeletonLine width="w-20" />
        </div>
      </div>

      <div className="space-y-2">
        <SkeletonLine width="w-20" />
        <SkeletonLine width="w-24" />
      </div>
    </div>
  );
};

export default OrderDetailsSkeleton;
