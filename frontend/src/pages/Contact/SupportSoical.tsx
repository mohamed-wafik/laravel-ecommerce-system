import { Clock } from "lucide-react";
import { CHANNELS } from "../../data/SuppotContact";

const SupportSoical = () => {
  return (
    <aside className="space-y-4">
      {CHANNELS.map(({ icon: Icon, title, detail, action, href }) => (
        <a
          key={title}
          href={href}
          className="group flex items-start gap-4 rounded-3xl border border-border bg-bg-primary p-5 transition-all hover:-translate-y-0.5 hover:border-primary-light hover:shadow-sm"
        >
          <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary">
            <Icon className="h-5 w-5" strokeWidth={1.75} />
          </span>

          <span className="min-w-0">
            <span className="block font-medium text-text-primary">{title}</span>

            <span className="mt-0.5 block truncate text-sm text-text-tertiary">
              {detail}
            </span>

            <span className="mt-1.5 block text-sm font-medium text-primary transition-colors group-hover:text-primary-dark">
              {action}
            </span>
          </span>
        </a>
      ))}

      {/* Hours */}
      <div className="flex items-start gap-4 rounded-3xl border border-border-light bg-bg-secondary p-5">
        <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-light-tertiary text-text-secondary">
          <Clock className="h-5 w-5" strokeWidth={1.75} />
        </span>

        <div>
          <p className="font-medium text-text-primary">Support hours</p>

          <p className="mt-0.5 text-sm text-text-tertiary">
            Mon–Fri, 9am–6pm ET
          </p>
        </div>
      </div>
    </aside>
  );
};

export default SupportSoical;
