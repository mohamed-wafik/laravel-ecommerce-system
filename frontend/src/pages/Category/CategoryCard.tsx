import {
  ChevronDown,
  Shirt,
  Footprints,
  Watch,
  Smartphone,
  Laptop,
  Headphones,
  Sofa,
  CookingPot,
  Package,
  type LucideIcon,
} from "lucide-react";
import type { ICategory } from "../../interface";
import { Link } from "react-router-dom";

const ICON_MAP: Record<string, LucideIcon> = {
  men: Shirt,
  women: Watch,
  electronics: Smartphone,
  "home-kitchen": Sofa,
};

const CHILD_ICON_MAP: Record<string, LucideIcon> = {
  shirts: Shirt,
  shoes: Footprints,
  accessories: Watch,
  dresses: Shirt,
  bags: Watch,
  phones: Smartphone,
  laptops: Laptop,
  headphones: Headphones,
  furniture: Sofa,
  cookware: CookingPot,
};
function resolveIcon(
  slug: string,
  map: Record<string, LucideIcon>,
): LucideIcon {
  return map[slug] ?? Package;
}

interface IProps {
  category: ICategory;
  isOpen: boolean;
  onToggle: () => void;
  activeSlug: string | null;
  onSelectChild: (slug: string) => void;
}
const CategoryCard = ({ category, isOpen, onToggle, activeSlug }: IProps) => {
  const Icon = resolveIcon(category.slug, ICON_MAP);
  const hasChildren = category.children.length > 0;

  return (
    <div className="group rounded-2xl border border-border bg-bg-primary transition-colors hover:border-primary-light">
      <button
        type="button"
        onClick={onToggle}
        disabled={!hasChildren}
        className="flex w-full items-center gap-4 rounded-2xl p-5 text-left disabled:cursor-default"
        aria-expanded={hasChildren ? isOpen : undefined}
      >
        <span className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary transition-colors group-hover:bg-primary-100">
          <Icon className="h-6 w-6" strokeWidth={1.75} />
        </span>

        <span className="min-w-0 flex-1">
          <span className="block truncate font-medium text-text-primary">
            {category.name}
          </span>
          <span className="block text-sm text-text-tertiary">
            {hasChildren
              ? `${category.children.length} subcategor${
                  category.children.length === 1 ? "y" : "ies"
                }`
              : "No subcategories"}
          </span>
        </span>

        {hasChildren && (
          <ChevronDown
            className={`h-5 w-5 shrink-0 text-text-tertiary transition-transform duration-200 ${
              isOpen ? "rotate-180" : ""
            }`}
          />
        )}
      </button>

      {hasChildren && (
        <div
          className={`grid transition-[grid-template-rows] duration-200 ease-out ${
            isOpen ? "grid-rows-[1fr]" : "grid-rows-[0fr]"
          }`}
        >
          <div className="overflow-hidden">
            <div className="flex flex-wrap gap-2 px-5 pb-5">
              {category.children.map((child) => {
                const ChildIcon = resolveIcon(child.slug, CHILD_ICON_MAP);
                const selected = activeSlug === child.slug;
                return (
                  <Link
                    to={`/shop?category_id=${child.id}`}
                    key={child.id}
                    type="button"
                    className={`inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-sm transition-colors ${
                      selected
                        ? "active border-primary bg-primary-50"
                        : "border-border-dark text-text-secondary hover:border-primary-light hover:text-primary"
                    }`}
                  >
                    <ChildIcon className="h-3.5 w-3.5" strokeWidth={1.75} />
                    {child.name}
                  </Link>
                );
              })}
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default CategoryCard;
