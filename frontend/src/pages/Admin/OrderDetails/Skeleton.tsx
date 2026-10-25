const Skeleton = () => {
  return (
    <section className="space-y-6">
      <div className="flex items-center gap-3">
        <div className="h-10 w-10 animate-pulse rounded-xl bg-bg-tertiary" />

        <div className="space-y-2">
          <div className="h-6 w-48 animate-pulse rounded-lg bg-bg-tertiary" />
          <div className="h-4 w-72 max-w-full animate-pulse rounded-lg bg-bg-tertiary" />
        </div>
      </div>

      <div className="grid gap-6 lg:grid-cols-3">
        <div className="space-y-6 lg:col-span-2">
          {Array.from({ length: 4 }).map((_, index) => (
            <div
              key={index}
              className="h-40 animate-pulse rounded-2xl bg-bg-tertiary"
            />
          ))}
        </div>

        <div className="space-y-6">
          <div className="h-48 animate-pulse rounded-2xl bg-bg-tertiary" />
          <div className="h-48 animate-pulse rounded-2xl bg-bg-tertiary" />
        </div>
      </div>
    </section>
  );
};

export default Skeleton;
