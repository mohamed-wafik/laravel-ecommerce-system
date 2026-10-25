import { ChevronDown, ChevronRight } from "lucide-react";
import { useState } from "react";
import useAuthentienticatedQuery from "../../hook/useAuthentienticatedQuery";
import type { ICategory } from "../../interface";

interface IProps {
  selectedCategory: string | null;
  setSelectedCategory: (val: string | null) => void;
}

const CategoriesFilter = ({
  selectedCategory,
  setSelectedCategory,
}: IProps) => {
  const { data, isLoading } = useAuthentienticatedQuery({
    queryKey: ["categories"],
    url: "/guest/categories",
  });

  const [expandedCategories, setExpandedCategories] = useState<string[]>([]);

  const toggleCategory = (id: string) => {
    setExpandedCategories((prev) =>
      prev.includes(id)
        ? prev.filter((categoryId) => categoryId !== id)
        : [...prev, id],
    );
  };

  const handleSelect = (id: string) => {
    setSelectedCategory(selectedCategory === id ? null : id);
  };

  const renderSkeleton = Array.from({ length: 5 }).map((_, index) => (
    <div
      key={index}
      className="h-9 w-full animate-pulse rounded-lg bg-gray-200"
    />
  ));

  if (isLoading) {
    return <div className="space-y-2">{renderSkeleton}</div>;
  }

  console.log(data);

  const categories: ICategory[] = data?.data ?? [];

  if (!categories.length) {
    return (
      <p className="py-4 text-center text-sm font-medium text-gray-500">
        No categories found
      </p>
    );
  }

  return (
    <div className="space-y-1">
      {categories.map((category) => {
        const hasChildren = category.children?.length > 0;
        const isExpanded = expandedCategories.includes(category.id);
        const isSelected = selectedCategory === category.id;

        return (
          <div key={category.id}>
            {/* Parent */}
            <button
              onClick={() => toggleCategory(category.id)}
              className="w-full flex items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 gap-1 px-2"
            >
              {hasChildren ? (
                <span
                  // type="button"
                  // onClick={() => toggleCategory(category.id)}
                  // className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-900"
                  aria-label={
                    isExpanded
                      ? `Collapse ${category.name}`
                      : `Expand ${category.name}`
                  }
                >
                  {isExpanded ? (
                    <ChevronDown size={17} />
                  ) : (
                    <ChevronRight size={17} />
                  )}
                </span>
              ) : (
                <div className="w-9 shrink-0" />
              )}
              <p
                className={`flex min-w-0 flex-1 items-center justify-between rounded-lg px-3 py-2 text-left text-sm transition`}
              >
                <span className="truncate">{category.name}</span>

                {hasChildren && (
                  <span className="ml-2 shrink-0 text-xs text-gray-400">
                    {category.children.length}
                  </span>
                )}
              </p>
            </button>

            {/* Children */}
            {hasChildren && isExpanded && (
              <div className="ml-5 space-y-1 border-l border-gray-200 pl-3">
                {category.children.map((child) => {
                  const isChildSelected = selectedCategory === child.id;

                  return (
                    <button
                      key={child.id}
                      type="button"
                      onClick={() => handleSelect(child.id)}
                      className={`flex w-full items-center rounded-lg px-3 py-2 text-left text-sm transition ${
                        isChildSelected
                          ? "bg-blue-50 font-medium text-blue-600"
                          : "text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                      }`}
                    >
                      <span className="truncate">{child.name}</span>
                    </button>
                  );
                })}
              </div>
            )}
          </div>
        );
      })}
    </div>
  );
};

export default CategoriesFilter;
