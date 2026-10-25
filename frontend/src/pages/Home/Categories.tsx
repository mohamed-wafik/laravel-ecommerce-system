import {
  ArrowRight,
  Home,
  Lamp,
  Monitor,
  Plane,
  Shirt,
  ShoppingBag,
  Smartphone,
  Star,
  Wrench,
  Gift,
  Cookie,
} from "lucide-react";
import { Link } from "react-router-dom";
import type { LucideIcon } from "lucide-react";

import useFetch from "../../hook/useAuthentienticatedQuery";
import type { ICategory } from "../../interface";

const CATEGORY_ICONS: Record<string, LucideIcon> = {
  deals: Star,
  grocery: ShoppingBag,
  fashion: Shirt,
  mobile: Smartphone,
  electronics: Monitor,
  home: Home,
  "home-kitchen": Home,
  dining: Lamp,
  gifts: Gift,
  tools: Wrench,
  travel: Plane,
  others: Cookie,
};

const DEFAULT_ICON = ShoppingBag;

const CategoriesSkeleton = () => {
  return (
    <div className="flex gap-8 overflow-x-auto py-7 px-3 md:gap-12 md:justify-center">
      {Array.from({ length: 10 }).map((_, index) => (
        <div
          key={index}
          className="flex min-w-[72px] shrink-0 flex-col items-center gap-2"
        >
          <div className="h-[68px] w-[68px] animate-pulse rounded-xl bg-gray-100" />

          <div className="h-4 w-16 animate-pulse rounded bg-gray-100" />
        </div>
      ))}
    </div>
  );
};

const Categories = () => {
  const { data, isLoading } = useFetch({
    queryKey: ["categories", "parent"],
    url: "/guest/categories/parent",
  });

  if (isLoading) {
    return (
      <section className="w-full">
        <div className="container mx-auto">
          <CategoriesSkeleton />
        </div>
      </section>
    );
  }

  const visibleCategories = data?.data ?? [];

  if (!visibleCategories.length) {
    return null;
  }

  return (
    <section className="w-full">
      <div className="container mx-auto">
        <div className="flex gap-8 overflow-x-auto px-3 py-7 scrollbar-hide md:justify-center md:gap-12">
          {visibleCategories.map((category: ICategory) => {
            const Icon = CATEGORY_ICONS[category.slug] ?? DEFAULT_ICON;

            return (
              <div
                key={category.id}
                className="group flex min-w-[72px] shrink-0 flex-col items-center gap-2"
              >
                <div
                  className="
                    flex h-[68px] w-[68px] items-center justify-center
                    rounded-xl bg-gray-100 p-5
                    transition-all duration-300
                    group-hover:bg-yellow-100
                    group-hover:-translate-y-1
                  "
                >
                  {category.image_url ? (
                    <img
                      src={category.image_url}
                      alt={category.name}
                      className="h-full w-full object-contain"
                      loading="lazy"
                    />
                  ) : (
                    <Icon
                      size={26}
                      strokeWidth={2}
                      className="transition-transform duration-300 group-hover:scale-110"
                    />
                  )}
                </div>

                <span className="whitespace-nowrap text-sm transition duration-300 group-hover:underline">
                  {category.name}
                </span>
              </div>
            );
          })}

          {/* View all */}
          <Link
            to="/categories"
            className="group flex min-w-[72px] shrink-0 flex-col items-center gap-2"
          >
            <div
              className="
                flex h-[68px] w-[68px] items-center justify-center
                rounded-xl bg-gray-100
                transition-all duration-300
                group-hover:bg-yellow-100
                group-hover:-translate-y-1
              "
            >
              <ArrowRight
                size={26}
                strokeWidth={2}
                className="transition-transform duration-300 group-hover:translate-x-1"
              />
            </div>

            <span className="whitespace-nowrap text-sm transition duration-300 group-hover:underline">
              View all
            </span>
          </Link>
        </div>
      </div>
    </section>
  );
};

export default Categories;
