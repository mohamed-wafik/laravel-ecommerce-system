const BrandSkeleton = () => {
  return (
    <div className="flex flex-col items-center gap-3 rounded-2xl border border-border bg-bg-primary p-6">
      <div className="h-14 w-14 animate-pulse rounded-full bg-light-tertiary" />
      <div className="h-4 w-16 animate-pulse rounded bg-light-tertiary" />
    </div>
  );
};

export default BrandSkeleton;
