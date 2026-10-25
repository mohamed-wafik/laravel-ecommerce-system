"use client";

import { useState } from "react";
import CategorySkeleton from "../../components/Skeleton/CategorySkeleton";
import EmptyState from "./EmptyState";
import CategoryCard from "./CategoryCard";
import type { ICategory } from "../../interface";
import { categoryAPI } from "../../api/modules/Guest/category.api";

export default function CategoriesPage() {
  const { data, isLoading } = categoryAPI.getAll();
  const [openIds, setOpenIds] = useState<Set<string>>(new Set());
  const [activeSlug, setActiveSlug] = useState<string | null>(null);

  const categories = data?.data ?? [];

  function toggle(id: string) {
    setOpenIds((prev) => {
      const next = new Set(prev);
      next.has(id) ? next.delete(id) : next.add(id);
      return next;
    });
  }

  return (
    <section className="mx-auto w-full max-w-6xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
      <header className="mb-8 sm:mb-10">
        <h1 className="text-2xl font-semibold text-text-primary sm:text-3xl">
          Shop by category
        </h1>
        <p className="mt-1.5 text-text-secondary">
          Browse everything by department, then narrow down to what you need.
        </p>
      </header>

      {isLoading ? (
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {Array.from({ length: 6 }).map((_, i) => (
            <CategorySkeleton key={i} />
          ))}
        </div>
      ) : categories.length === 0 ? (
        <EmptyState />
      ) : (
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {categories.map((category: ICategory) => (
            <CategoryCard
              key={category.id}
              category={category}
              isOpen={openIds.has(category.id)}
              onToggle={() => toggle(category.id)}
              activeSlug={activeSlug}
              onSelectChild={(slug) => setActiveSlug(slug)}
            />
          ))}
        </div>
      )}
    </section>
  );
}
