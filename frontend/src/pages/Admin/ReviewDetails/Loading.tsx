const Loading = () => {
  return (
    <section className="space-y-6">
      <div className="h-8 w-48 animate-pulse rounded-lg bg-bg-tertiary" />

      <div className="grid gap-6 lg:grid-cols-3">
        <div className="space-y-6 lg:col-span-2">
          <div className="h-64 animate-pulse rounded-2xl bg-bg-tertiary" />
          <div className="h-52 animate-pulse rounded-2xl bg-bg-tertiary" />
        </div>

        <div className="h-80 animate-pulse rounded-2xl bg-bg-tertiary" />
      </div>
    </section>
  );
};

export default Loading;
