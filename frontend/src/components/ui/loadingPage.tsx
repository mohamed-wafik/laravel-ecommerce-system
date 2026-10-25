import { ShoppingBag, Sparkles } from "lucide-react";

const LoadingPage = () => {
  return (
    <div className="relative flex min-h-screen items-center justify-center overflow-hidden bg-bg-primary px-6">
      {/* Ambient background */}
      <div
        aria-hidden="true"
        className="pointer-events-none absolute left-1/2 top-1/2 h-[520px] w-[520px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary-50 blur-3xl"
      />

      <div
        aria-hidden="true"
        className="pointer-events-none absolute -left-24 top-20 h-64 w-64 rounded-full bg-secondary-50 blur-3xl"
      />

      <div
        aria-hidden="true"
        className="pointer-events-none absolute -bottom-24 -right-20 h-72 w-72 rounded-full bg-accent-50 blur-3xl"
      />

      {/* Loader */}
      <div className="relative z-10 flex w-full max-w-md flex-col items-center">
        {/* Main visual */}
        <div className="relative flex h-64 w-64 items-center justify-center">
          {/* Outer orbit */}
          <div className="absolute inset-3 rounded-full border border-border-light" />

          <div className="absolute inset-8 rounded-full border border-dashed border-primary-100 animate-[loader-spin_12s_linear_infinite]" />

          {/* Orbiting accent */}
          <div className="absolute inset-8 animate-[loader-spin_5s_linear_infinite]">
            <span className="absolute left-1/2 top-0 h-3 w-3 -translate-x-1/2 rounded-full bg-primary shadow-lg shadow-primary/20" />
          </div>

          <div className="absolute inset-8 animate-[loader-spin-reverse_7s_linear_infinite]">
            <span className="absolute bottom-2 right-4 h-2.5 w-2.5 rounded-full bg-accent shadow-lg shadow-accent/20" />
          </div>

          {/* Decorative dots */}
          <span className="absolute left-5 top-1/2 h-2 w-2 rounded-full bg-secondary animate-[dot-float_2.5s_ease-in-out_infinite]" />

          <span className="absolute right-5 top-[35%] h-1.5 w-1.5 rounded-full bg-success animate-[dot-float_2.2s_ease-in-out_0.3s_infinite]" />

          <span className="absolute bottom-10 left-1/4 h-1.5 w-1.5 rounded-full bg-primary-light animate-[dot-float_2.8s_ease-in-out_0.6s_infinite]" />

          {/* Main card */}
          <div className="relative flex h-28 w-28 items-center justify-center rounded-[2rem] border border-primary-100 bg-bg-primary shadow-2xl shadow-primary/10">
            {/* Scanner */}
            <div className="absolute inset-x-5 top-5 h-px overflow-hidden">
              <div className="h-full w-1/2 bg-gradient-to-r from-transparent via-primary to-transparent animate-[scanner_1.8s_ease-in-out_infinite]" />
            </div>

            {/* Glow */}
            <div className="absolute inset-4 rounded-3xl bg-primary-50 animate-pulse" />

            {/* Icon */}
            <div className="relative flex h-16 w-16 items-center justify-center rounded-2xl bg-bg-secondary">
              <ShoppingBag className="h-8 w-8 text-primary" strokeWidth={1.8} />

              {/* Badge */}
              <span className="absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-accent text-white shadow-lg shadow-accent/20">
                <Sparkles className="h-3 w-3" />
              </span>
            </div>

            {/* Bottom scanning line */}
            <div className="absolute bottom-5 left-6 right-6 h-1 overflow-hidden rounded-full bg-border-light">
              <div className="h-full w-1/2 rounded-full bg-primary animate-[progress-slide_1.5s_ease-in-out_infinite]" />
            </div>
          </div>
        </div>

        {/* Text */}
        <div className="mt-4 text-center">
          <div className="flex items-center justify-center gap-2">
            <h1 className="text-2xl font-bold tracking-tight text-text-primary">
              Preparing your experience
            </h1>

            <span className="flex items-end gap-1 pb-1">
              <span className="h-1.5 w-1.5 rounded-full bg-primary animate-[loading-dot_1.4s_ease-in-out_infinite]" />
              <span className="h-1.5 w-1.5 rounded-full bg-primary animate-[loading-dot_1.4s_ease-in-out_0.2s_infinite]" />
              <span className="h-1.5 w-1.5 rounded-full bg-primary animate-[loading-dot_1.4s_ease-in-out_0.4s_infinite]" />
            </span>
          </div>

          <p className="mt-3 max-w-sm text-sm leading-6 text-text-tertiary">
            We're getting everything ready for you.
          </p>
        </div>

        {/* Progress */}
        <div className="mt-9 w-full max-w-xs">
          <div className="mb-3 flex items-center justify-between">
            <span className="text-[11px] font-semibold uppercase tracking-[0.18em] text-text-tertiary">
              Loading
            </span>

            <span className="text-[11px] font-semibold text-primary">
              Please wait
            </span>
          </div>

          <div className="h-1 overflow-hidden rounded-full bg-border-light">
            <div className="h-full w-1/3 rounded-full bg-gradient-to-r from-primary via-secondary to-primary animate-[progress-loading_2s_ease-in-out_infinite]" />
          </div>
        </div>

        {/* Status */}
        <div className="mt-7 flex items-center gap-2 rounded-full border border-border-light bg-bg-secondary px-4 py-2.5">
          <span className="relative flex h-2 w-2">
            <span className="absolute inset-0 rounded-full bg-success opacity-50 animate-ping" />
            <span className="relative h-2 w-2 rounded-full bg-success" />
          </span>

          <span className="text-xs font-medium text-text-secondary">
            Everything is running smoothly
          </span>
        </div>
      </div>

      <style>{`
        @keyframes loader-spin {
          from {
            transform: rotate(0deg);
          }

          to {
            transform: rotate(360deg);
          }
        }

        @keyframes loader-spin-reverse {
          from {
            transform: rotate(360deg);
          }

          to {
            transform: rotate(0deg);
          }
        }

        @keyframes scanner {
          0% {
            transform: translateX(-180%);
          }

          50% {
            transform: translateX(180%);
          }

          100% {
            transform: translateX(180%);
          }
        }

        @keyframes progress-slide {
          0% {
            transform: translateX(-130%);
          }

          50% {
            transform: translateX(130%);
          }

          100% {
            transform: translateX(130%);
          }
        }

        @keyframes progress-loading {
          0% {
            transform: translateX(-130%);
          }

          50% {
            transform: translateX(180%);
          }

          100% {
            transform: translateX(320%);
          }
        }

        @keyframes loading-dot {
          0%,
          60%,
          100% {
            transform: translateY(0);
            opacity: 0.35;
          }

          30% {
            transform: translateY(-5px);
            opacity: 1;
          }
        }

        @keyframes dot-float {
          0%,
          100% {
            transform: translateY(0) scale(1);
            opacity: 0.5;
          }

          50% {
            transform: translateY(-8px) scale(1.15);
            opacity: 1;
          }
        }

        @media (prefers-reduced-motion: reduce) {
          *,
          *::before,
          *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            scroll-behavior: auto !important;
          }
        }
      `}</style>
    </div>
  );
};

export default LoadingPage;
