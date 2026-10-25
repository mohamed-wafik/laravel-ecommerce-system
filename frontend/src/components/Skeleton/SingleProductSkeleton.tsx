const Shimmer = ({ className = "" }: { className?: string }) => (
  <div className={`bg-gray-100 rounded-xl animate-pulse ${className}`} />
);

// ── Breadcrumb skeleton ───────────────────────────────────────────────────────
const BreadcrumbSkeleton = () => (
  <div className="flex items-center gap-2 mb-8">
    <Shimmer className="h-3 w-10 rounded-full" />
    <Shimmer className="h-3 w-3 rounded-full" />
    <Shimmer className="h-3 w-20 rounded-full" />
    <Shimmer className="h-3 w-3 rounded-full" />
    <Shimmer className="h-3 w-36 rounded-full" />
  </div>
);

// ── Image panel skeleton ──────────────────────────────────────────────────────
const ImagePanelSkeleton = () => (
  <div className="relative bg-gray-50 flex items-center justify-center p-8 lg:p-12 min-h-[480px]">
    {/* Primary accent strip placeholder */}
    <div className="absolute top-0 left-0 w-1 h-full bg-gray-200 rounded-l-2xl" />

    {/* Discount badge placeholder */}
    <div className="absolute top-6 right-6">
      <Shimmer className="h-7 w-20 rounded-full" />
    </div>

    {/* Main image */}
    <Shimmer className="w-full max-w-sm h-80 rounded-xl" />
  </div>
);

// ── Info panel skeleton ───────────────────────────────────────────────────────
const InfoPanelSkeleton = () => (
  <div className="p-8 lg:p-12 flex flex-col justify-between border-l border-gray-100">
    <div>
      {/* Badges */}
      <div className="flex items-center gap-2 mb-4">
        <Shimmer className="h-6 w-20 rounded-full" />
        <Shimmer className="h-6 w-24 rounded-full" />
      </div>

      {/* Title */}
      <Shimmer className="h-9 w-4/5 mb-2" />
      <Shimmer className="h-9 w-3/5 mb-5" />

      {/* Stars */}
      <div className="flex items-center gap-3 mb-6">
        <div className="flex gap-1">
          {[...Array(5)].map((_, i) => (
            <Shimmer key={i} className="w-4 h-4 rounded" />
          ))}
        </div>
        <Shimmer className="h-3.5 w-28 rounded-full" />
      </div>

      {/* Divider */}
      <div className="h-px bg-gray-100 mb-6" />

      {/* Price */}
      <div className="flex items-end gap-3 mb-6">
        <Shimmer className="h-10 w-36 rounded-lg" />
        <div className="flex flex-col gap-1 pb-1">
          <Shimmer className="h-3.5 w-20 rounded-full" />
          <Shimmer className="h-3 w-16 rounded-full" />
        </div>
      </div>

      {/* Description */}
      <div className="space-y-2 mb-8">
        <Shimmer className="h-3.5 w-full rounded-full" />
        <Shimmer className="h-3.5 w-full rounded-full" />
        <Shimmer className="h-3.5 w-3/4 rounded-full" />
      </div>

      {/* Divider */}
      <div className="h-px bg-gray-100 mb-6" />

      {/* Quantity row */}
      <div className="flex items-center justify-between mb-8">
        <Shimmer className="h-4 w-20 rounded-full" />
        <Shimmer className="h-10 w-32 rounded-xl" />
      </div>

      {/* Action buttons */}
      <div className="flex gap-3 mb-8">
        <Shimmer className="h-12 flex-1 rounded-xl" />
        <Shimmer className="h-12 flex-1 rounded-xl" />
        <Shimmer className="h-12 w-12 rounded-xl" />
      </div>
    </div>

    {/* Trust badges */}
    <div className="grid grid-cols-2 gap-3 pt-6 border-t border-gray-100">
      {[...Array(4)].map((_, i) => (
        <div
          key={i}
          className="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-100"
        >
          <Shimmer className="w-4 h-4 rounded shrink-0" />
          <Shimmer className="h-3 w-24 rounded-full" />
        </div>
      ))}
    </div>
  </div>
);

// ── Related products skeleton ─────────────────────────────────────────────────
const RelatedSkeleton = () => (
  <div className="mt-14">
    <div className="flex items-center gap-4 mb-6">
      <div className="w-1 h-6 bg-gray-200 rounded-full animate-pulse" />
      <Shimmer className="h-5 w-40 rounded-full" />
    </div>
    <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
      {[...Array(4)].map((_, i) => (
        <div
          key={i}
          className="bg-white rounded-2xl border border-gray-100 overflow-hidden"
        >
          <Shimmer className="h-44 w-full rounded-none" />
          <div className="p-4 space-y-2">
            <Shimmer className="h-3.5 w-full rounded-full" />
            <Shimmer className="h-3.5 w-2/3 rounded-full" />
            <Shimmer className="h-4 w-1/2 rounded-full mt-3" />
          </div>
        </div>
      ))}
    </div>
  </div>
);

// ── Tabs skeleton ─────────────────────────────────────────────────────────────
const TabsSkeleton = () => (
  <div className="mt-14 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    {/* Tab bar */}
    <div className="border-b border-gray-100 px-6 flex gap-2 py-1">
      {[...Array(3)].map((_, i) => (
        <div key={i} className="px-6 py-5">
          <Shimmer
            className={`h-4 rounded-full ${i === 0 ? "w-24" : i === 1 ? "w-16" : "w-20"}`}
          />
        </div>
      ))}
    </div>

    {/* Tab content */}
    <div className="p-8 space-y-3">
      <Shimmer className="h-3.5 w-full rounded-full" />
      <Shimmer className="h-3.5 w-full rounded-full" />
      <Shimmer className="h-3.5 w-5/6 rounded-full" />
      <Shimmer className="h-3.5 w-4/5 rounded-full" />
    </div>
  </div>
);

// ── Main export ───────────────────────────────────────────────────────────────
const SingleProductSkeleton = () => (
  <section className="py-10 bg-gray-50 min-h-screen">
    <div className="container mx-auto px-4 max-w-7xl">
      <BreadcrumbSkeleton />

      {/* Main split — mirrors the 2-col grid */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-0 bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
        <ImagePanelSkeleton />
        <InfoPanelSkeleton />
      </div>

      <RelatedSkeleton />
      <TabsSkeleton />
    </div>
  </section>
);

export default SingleProductSkeleton;
