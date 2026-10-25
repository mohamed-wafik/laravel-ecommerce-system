import { useMemo } from "react";

import { productAPI } from "../../api/modules/Guest/product.api";
import Products from "./Products";
import SiderbarFilter from "./SiderbarFilter";
import HeaderShop from "./Header";
import type { IProduct } from "../../interface";
import useProductFilters from "../../hook/useProductFilters";

const PRODUCTS_DEFAULT_MAX_PRICE = 2500;

const ProductsPage = () => {
  // ─────────────────────────────────────────────
  // Product Filters
  // ─────────────────────────────────────────────

  const { filters, setFilter, setPage, setPriceRange, clearFilters } =
    useProductFilters();

  const {
    search,
    category_id,
    brand_id,
    min_price,
    max_price,
    only_discounted,
    min_rating,
    sort,
  } = filters;

  // ─────────────────────────────────────────────
  // Fetch Products
  // ─────────────────────────────────────────────

  const { data, isLoading } = productAPI.getAll(filters);

  // ─────────────────────────────────────────────
  // Maximum Price
  // ─────────────────────────────────────────────

  const maxPrice = useMemo(() => {
    if (!data?.data?.data?.length) {
      return PRODUCTS_DEFAULT_MAX_PRICE;
    }

    const prices = data.data.data.map((product: IProduct) =>
      Number(product.price),
    );

    return prices.length ? Math.max(...prices) : PRODUCTS_DEFAULT_MAX_PRICE;
  }, [data]);

  // ─────────────────────────────────────────────
  // Filters
  // ─────────────────────────────────────────────

  const hasActiveFilters =
    Boolean(search.trim()) ||
    Boolean(category_id) ||
    Boolean(brand_id) ||
    Boolean(min_price) ||
    Boolean(max_price) ||
    Boolean(only_discounted) ||
    Boolean(min_rating);

  const clearAllFilters = () => {
    clearFilters();
  };

  // ─────────────────────────────────────────────
  // Render
  // ─────────────────────────────────────────────

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <HeaderShop />

      <div className="container mx-auto px-4 py-8">
        <div className="flex gap-8">
          {/* Sidebar */}
          <SiderbarFilter
            filters={filters}
            maxPrice={maxPrice}
            hasActiveFilters={hasActiveFilters}
            clearAllFilters={clearAllFilters}
            setFilter={setFilter}
            setPriceRange={setPriceRange}
          />

          {/* Products */}
          <Products
            setFilter={setFilter}
            clearAllFilters={clearAllFilters}
            data={data ? data.data : null}
            setPage={setPage}
            sort={sort}
            hasActiveFilters={hasActiveFilters}
            isLoading={isLoading}
          />
        </div>
      </div>
    </div>
  );
};

export default ProductsPage;
