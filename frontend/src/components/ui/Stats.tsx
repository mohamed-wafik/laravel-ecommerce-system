const Stats = () => {
  return (
    <div className="grid grid-cols-3 gap-3 mt-10">
      {[
        { value: "50K+", label: "Products" },
        { value: "200K+", label: "Customers" },
        { value: "4.9★", label: "Rating" },
      ].map(({ value, label }) => (
        <div
          key={label}
          className="backdrop-blur-sm border border-white/10 rounded-2xl p-4 text-center bg-white/10"
        >
          <div className="text-white font-bold text-xl">{value}</div>
          <div className="text-white/50 text-xs mt-0.5">{label}</div>
        </div>
      ))}
    </div>
  );
};

export default Stats;
