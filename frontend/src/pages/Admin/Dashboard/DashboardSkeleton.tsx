const DashboardSkeleton = () => {
  return (
    <main className="min-h-screen bg-bg-secondary p-4 sm:p-6 lg:p-8">
      <div className="mb-6">
        <div className="h-8 w-40 animate-pulse rounded-lg bg-light-tertiary" />
        <div className="mt-2 h-4 w-72 animate-pulse rounded bg-light-tertiary" />
      </div>

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {Array.from({ length: 4 }).map((_, index) => (
          <div
            key={index}
            className="h-32 animate-pulse rounded-2xl bg-bg-primary"
          />
        ))}
      </div>

      <div className="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div className="h-[390px] animate-pulse rounded-2xl bg-bg-primary xl:col-span-2" />
        <div className="h-[390px] animate-pulse rounded-2xl bg-bg-primary" />
      </div>

      <div className="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div className="h-[380px] animate-pulse rounded-2xl bg-bg-primary" />
        <div className="h-[380px] animate-pulse rounded-2xl bg-bg-primary" />
      </div>
    </main>
  );
};
export default DashboardSkeleton;
