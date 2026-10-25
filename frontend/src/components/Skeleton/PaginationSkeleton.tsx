const PaginationSkeleton = () => {
  return (
    <div className="flex flex-col gap-3 rounded-2xl border border-border bg-bg-primary p-4 sm:flex-row sm:items-center sm:justify-between">
      {/* Page Info */}
      <div className="h-5 w-36 animate-pulse rounded-md bg-bg-tertiary" />

      {/* Pagination Controls */}
      <div className="flex items-center gap-2">
        <div className="h-9 w-9 animate-pulse rounded-lg bg-bg-tertiary" />

        <div className="h-5 w-24 animate-pulse rounded-md bg-bg-tertiary" />

        <div className="h-9 w-9 animate-pulse rounded-lg bg-bg-tertiary" />
      </div>
    </div>
  );
};

export default PaginationSkeleton;
