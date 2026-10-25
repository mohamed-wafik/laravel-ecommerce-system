const AddressSkeleton = () => {
  return (
    <div className="grid gap-5 md:grid-cols-2">
      {[1, 2].map((item) => (
        <div
          key={item}
          className="animate-pulse rounded-2xl border border-border bg-bg-primary p-5"
        >
          <div className="flex items-center gap-3">
            <div className="h-11 w-11 rounded-xl bg-light-secondary" />

            <div className="space-y-2">
              <div className="h-4 w-20 rounded bg-light-secondary" />
              <div className="h-3 w-32 rounded bg-light-secondary" />
            </div>
          </div>

          <div className="mt-6 space-y-4">
            <div className="h-4 w-40 rounded bg-light-secondary" />
            <div className="h-4 w-32 rounded bg-light-secondary" />
            <div className="h-12 w-full rounded bg-light-secondary" />
          </div>

          <div className="mt-6 flex gap-3 border-t border-border-light pt-4">
            <div className="h-10 flex-1 rounded-lg bg-light-secondary" />
            <div className="h-10 w-24 rounded-lg bg-light-secondary" />
          </div>
        </div>
      ))}
    </div>
  );
};

export default AddressSkeleton;
