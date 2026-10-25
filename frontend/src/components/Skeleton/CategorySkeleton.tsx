const CategorySkeleton = () => {
  return (
    <div className="rounded-2xl border border-border bg-bg-primary p-5">
      <div className="flex items-center gap-4">
        <div className="h-12 w-12 shrink-0 animate-pulse rounded-xl bg-light-tertiary" />
        <div className="flex-1 space-y-2">
          <div className="h-4 w-2/3 animate-pulse rounded bg-light-tertiary" />
          <div className="h-3 w-1/3 animate-pulse rounded bg-light-tertiary" />
        </div>
      </div>
      <div className="mt-5 flex flex-wrap gap-2">
        {[0, 1, 2].map((i) => (
          <div
            key={i}
            className="h-7 w-20 animate-pulse rounded-full bg-light-tertiary"
          />
        ))}
      </div>
    </div>
  );
};

export default CategorySkeleton;
