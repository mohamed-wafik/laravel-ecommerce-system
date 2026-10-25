interface IProps {
  icon: React.ReactNode;
  badge: string;
  title: string;
  highlight: string;
  description: string;
  steps: { label: string; active: boolean }[];
}
const ProgressSteps = ({
  icon,
  badge,
  title,
  highlight,
  description,
  steps,
}: IProps) => {
  return (
    <div className="relative z-10">
      <div className="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 rounded-full px-3.5 py-1.5 mb-6">
        <div className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" />
        <span className="text-white/80 text-xs font-medium">{badge}</span>
      </div>

      {/* Big icon */}
      <div className="w-16 h-16 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center mb-6">
        {icon}
      </div>

      <h2 className="text-white text-4xl font-bold leading-tight mb-4">
        {title}
        <br />
        <span className="text-violet-200 italic">{highlight}</span>
      </h2>
      <p className="text-white/55 text-sm leading-relaxed max-w-xs mb-10">
        {description}
      </p>

      {/* Progress steps */}
      <div className="flex flex-col gap-3">
        {steps.map((step, i) => (
          <div key={i} className="flex items-center gap-3">
            <div
              className={`w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shrink-0 border ${
                step.active
                  ? "bg-white text-secondary border-white"
                  : "bg-white/10 text-white/40 border-white/15"
              }`}
            >
              {i + 1}
            </div>
            <span
              className={`text-sm ${step.active ? "text-white font-medium" : "text-white/40"}`}
            >
              {step.label}
            </span>
          </div>
        ))}
      </div>
    </div>
  );
};

export default ProgressSteps;
