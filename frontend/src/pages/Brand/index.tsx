"use client";

import { useMemo, useState } from "react";
import { Search } from "lucide-react";
import BrandCard from "./BrandCard";
import EmptyState from "./EmptyState";
import BrandSkeleton from "../../components/Skeleton/BrandSkeleton";
import { brandApi } from "../../api/modules/Guest/brand.api";

const BrandsPage = () => {
  const { data, isLoading } = brandApi.getAll();
  const [query, setQuery] = useState("");

  const brands = useMemo(() => {
    const list = data?.data ?? [];
    const sorted = [...list].sort((a, b) => a.name.localeCompare(b.name));
    if (!query.trim()) return sorted;
    const q = query.trim().toLowerCase();
    return sorted.filter((b) => b.name.toLowerCase().includes(q));
  }, [data, query]);

  return (
    <section className="mx-auto w-full max-w-6xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
      <header className="mb-8 flex flex-col gap-4 sm:mb-10 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <h1 className="text-2xl font-semibold text-text-primary sm:text-3xl">
            Shop by brand
          </h1>
          <p className="mt-1.5 text-text-secondary">
            Find everything from the brands you already trust.
          </p>
        </div>

        <div className="relative w-full sm:w-64">
          <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-text-tertiary" />
          <input
            type="text"
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder="Search brands"
            className="w-full rounded-full border border-border bg-bg-primary py-2 pl-9 pr-4 text-sm text-text-primary placeholder:text-text-tertiary focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100"
          />
        </div>
      </header>

      {isLoading ? (
        <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
          {Array.from({ length: 6 }).map((_, i) => (
            <BrandSkeleton key={i} />
          ))}
        </div>
      ) : brands.length === 0 ? (
        <EmptyState query={query} />
      ) : (
        <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
          {brands.map((brand) => (
            <BrandCard key={brand.id} brand={brand} />
          ))}
        </div>
      )}
    </section>
  );
};
export default BrandsPage;
