import { ChevronRight } from "lucide-react";
import { Link } from "react-router-dom";

interface BreadcrumbItem {
  label: string;
  href?: string;
}

interface BreadcrumbProps {
  items: BreadcrumbItem[];
}

const Breadcrumb = ({ items }: BreadcrumbProps) => {
  return (
    <nav aria-label="Breadcrumb" className="mb-6 overflow-hidden sm:mb-8">
      <ol className="flex items-center gap-1.5 overflow-x-auto whitespace-nowrap text-xs text-gray-400">
        {items.map((item, index) => {
          const isLast = index === items.length - 1;

          return (
            <li
              key={`${item.label}-${index}`}
              className="flex shrink-0 items-center gap-1.5"
            >
              {index > 0 && <ChevronRight className="h-3 w-3 shrink-0" />}

              {isLast ? (
                <span className="max-w-[180px] truncate font-medium text-gray-900 sm:max-w-xs">
                  {item.label}
                </span>
              ) : (
                <Link
                  to={item.href ?? "/"}
                  className="transition-colors hover:text-primary"
                >
                  {item.label}
                </Link>
              )}
            </li>
          );
        })}
      </ol>
    </nav>
  );
};

export default Breadcrumb;
