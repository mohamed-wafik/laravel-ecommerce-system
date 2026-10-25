import { ChevronRight } from "lucide-react";
import { NavLink } from "react-router-dom";

interface IProps {
  title: string;
  items: {
    label: string;
    path: string;
    icon: React.ElementType;
  }[];
  onClose?: () => void;
}

const NavSection = ({ title, items, onClose }: IProps) => {
  return (
    <section className="mb-7">
      <h2 className="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.14em] text-text-tertiary">
        {title}
      </h2>

      <div className="space-y-1">
        {items.map((item) => {
          const Icon = item.icon;

          return (
            <NavLink
              key={item.path}
              to={item.path}
              onClick={onClose}
              end={item.path === "/admin"}
              className={({ isActive }) =>
                `
                group relative flex items-center gap-3
                rounded-xl px-3 py-2.5
                text-sm font-medium
                transition-all duration-200
                ${
                  isActive
                    ? "bg-primary-50 text-primary shadow-sm"
                    : "text-text-secondary hover:bg-bg-secondary hover:text-text-primary"
                }
              `
              }
            >
              {({ isActive }) => (
                <>
                  {/* Active indicator */}
                  {isActive && (
                    <span
                      className="
                        absolute start-0 top-1/2
                        h-6 w-1
                        -translate-y-1/2
                        rounded-e-full
                        bg-primary
                      "
                    />
                  )}

                  {/* Icon */}
                  <Icon
                    size={19}
                    strokeWidth={isActive ? 2.3 : 2}
                    className={`
                      shrink-0
                      transition-all duration-200
                      group-hover:scale-105
                      ${isActive ? "text-primary" : ""}
                    `}
                  />

                  {/* Label */}
                  <span className="flex-1">{item.label}</span>

                  {/* Arrow */}
                  <ChevronRight
                    size={15}
                    className={`
                      opacity-0
                      -translate-x-1
                      transition-all duration-200
                      group-hover:translate-x-0
                      group-hover:opacity-50
                      ${isActive ? "text-primary opacity-100" : ""}
                    `}
                  />
                </>
              )}
            </NavLink>
          );
        })}
      </div>
    </section>
  );
};

export default NavSection;
