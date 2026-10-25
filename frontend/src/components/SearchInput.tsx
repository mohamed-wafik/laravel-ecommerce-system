import { Search, Loader2 } from "lucide-react";
import { useEffect, useState } from "react";
import toast from "react-hot-toast";
import { Link, useNavigate } from "react-router-dom";
import useFetch from "../hook/useAuthentienticatedQuery";

interface ISearchProduct {
  id: string;
  name: string;
  slug: string;
  price: number;
  image_url?: string | null;
}

interface ISearchCategory {
  id: string;
  name: string;
  slug: string;
}

interface ISearchResponse {
  products: ISearchProduct[];
  categories: ISearchCategory[];
}

const SearchInput = () => {
  const [input, setInput] = useState("");
  const [search, setSearch] = useState("");
  const [isFocused, setIsFocused] = useState(false);

  const navigate = useNavigate();

  // Debounce search
  useEffect(() => {
    const timer = setTimeout(() => {
      setSearch(input.trim());
    }, 300);

    return () => clearTimeout(timer);
  }, [input]);

  const shouldSearch = search.length >= 3;

  const { data, isLoading } = useFetch({
    queryKey: ["search", search],
    url: shouldSearch ? `/guest/search?q=${encodeURIComponent(search)}` : "",
  });

  const results: ISearchResponse | null = data?.data ?? null;

  const hasResults =
    results && (results.products.length > 0 || results.categories.length > 0);

  const handleSubmit = () => {
    const value = input.trim();

    if (value.length < 3) {
      toast.error("Search term must be at least 3 characters.");
      return;
    }

    setInput("");
    setSearch("");
    setIsFocused(false);

    navigate(`/shop?search=${encodeURIComponent(value)}`);
  };

  const handleKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
    if (e.key === "Enter") {
      handleSubmit();
    }

    if (e.key === "Escape") {
      setIsFocused(false);
    }
  };

  return (
    <div className="relative w-full">
      {/* Search input */}
      <div className="relative">
        <input
          type="text"
          placeholder="Search for products..."
          value={input}
          onChange={(e) => setInput(e.target.value)}
          onFocus={() => setIsFocused(true)}
          onKeyDown={handleKeyDown}
          className="w-full rounded-full border border-gray-300 py-2 pl-4 pr-12 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"
        />

        <button
          type="button"
          onClick={handleSubmit}
          className="absolute right-0 top-0 flex h-full items-center justify-center rounded-r-full bg-primary px-4 text-white transition-colors hover:bg-secondary"
          aria-label="Search"
        >
          {isLoading && shouldSearch ? (
            <Loader2 size={18} className="animate-spin" />
          ) : (
            <Search size={18} />
          )}
        </button>
      </div>

      {/* Search results */}
      {isFocused && shouldSearch && (
        <div className="absolute left-0 right-0 top-[calc(100%+8px)] z-50 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl">
          {isLoading ? (
            <div className="flex items-center justify-center gap-2 p-6 text-sm text-gray-500">
              <Loader2 size={18} className="animate-spin" />
              Searching...
            </div>
          ) : !hasResults ? (
            <div className="p-6 text-center text-sm text-gray-500">
              No results found for "{search}"
            </div>
          ) : (
            <div className="max-h-[420px] overflow-y-auto">
              {/* Categories */}
              {results.categories.length > 0 && (
                <div className="border-b border-gray-100 p-3">
                  <p className="mb-2 px-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Categories
                  </p>

                  <div className="space-y-1">
                    {results.categories.map((category) => (
                      <button
                        key={category.id}
                        type="button"
                        onClick={() => {
                          setInput("");
                          setSearch("");
                          setIsFocused(false);

                          navigate(
                            `/shop?category_id=${encodeURIComponent(
                              category.id,
                            )}`,
                          );
                        }}
                        className="flex w-full items-center rounded-xl px-3 py-2 text-left text-sm text-gray-700 transition hover:bg-gray-50 hover:text-primary"
                      >
                        <Search size={15} className="mr-3 text-gray-400" />
                        <span>{category.name}</span>
                      </button>
                    ))}
                  </div>
                </div>
              )}

              {/* Products */}
              {results.products.length > 0 && (
                <div className="p-3">
                  <p className="mb-2 px-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Products
                  </p>

                  <div className="space-y-1">
                    {results.products.map((product) => (
                      <Link
                        key={product.id}
                        to={`/products/${product.slug}`}
                        onClick={() => {
                          setInput("");
                          setSearch("");
                          setIsFocused(false);
                        }}
                        className="flex items-center gap-3 rounded-xl p-2 transition hover:bg-gray-50"
                      >
                        <div className="h-12 w-12 shrink-0 overflow-hidden rounded-lg bg-gray-100">
                          {product.image_url ? (
                            <img
                              src={product.image_url}
                              alt={product.name}
                              className="h-full w-full object-cover"
                            />
                          ) : (
                            <div className="flex h-full w-full items-center justify-center">
                              <Search size={16} className="text-gray-400" />
                            </div>
                          )}
                        </div>

                        <div className="min-w-0 flex-1">
                          <p className="truncate text-sm font-medium text-gray-800">
                            {product.name}
                          </p>

                          <p className="text-xs text-gray-500">
                            ${product.price}
                          </p>
                        </div>
                      </Link>
                    ))}
                  </div>
                </div>
              )}

              {/* View all */}
              <div className="border-t border-gray-100 p-3">
                <button
                  type="button"
                  onClick={handleSubmit}
                  className="w-full rounded-xl bg-primary px-4 py-2.5 text-sm font-medium text-white transition hover:bg-secondary"
                >
                  View all results
                </button>
              </div>
            </div>
          )}
        </div>
      )}
    </div>
  );
};

export default SearchInput;
