import { Plus } from "lucide-react";
import { useState } from "react";
import toast from "react-hot-toast";

import type { IPagination } from "../../../interface";

import Button from "../../../components/ui/Button";
import Pagination from "../../../components/ui/Pagnation";
import Toolbar from "./Toolbar";
import CreateOrEdit from "./CreateOrEdit";

import CategoryRow from "./CategoryRow";
import CategoryCardMobile from "./CategoryCardMobile";
import DeleteCategory from "./DeleteCategory";
import PaginationSkeleton from "../../../components/Skeleton/PaginationSkeleton";
import { adminCategoryAPI } from "../../../api/modules/Admin/category.api";
import { Table } from "../../../components/ui/Table";

import useCategoryFilters, {
  type CategoryFilterField,
} from "../../../hook/useCategoryFilters";

export interface ICategory {
  id: string;
  parent_id: string | null;
  name: string;
  slug: string;
  image_url: string | null;
  image_public_id: string | null;
  is_active: boolean;
  sort_order: number;
  parent: ICategory | null;
}

interface ICategoryResponse {
  items: ICategory[];
  pagination: IPagination;
}

const AdminCategories = () => {
  const { filters, setFilter, setPage, setPerPage, clearFilters } =
    useCategoryFilters();

  const [deleteCategory, setDeleteCategory] = useState<ICategory | null>(null);

  const [isModalOpen, setIsModalOpen] = useState(false);

  const [editingCategory, setEditingCategory] = useState<ICategory | null>(
    null,
  );

  const { data, isLoading, isFetching, refetch } =
    adminCategoryAPI.getAll(filters);

  const result: ICategoryResponse = data?.data ?? {
    items: [],
    pagination: {} as IPagination,
  };

  /*
   * Only root categories can be selected as parents.
   */
  const parentCategories = result.items.filter(
    (category) => category.parent_id === null,
  );

  const openCreateModal = () => {
    setEditingCategory(null);
    setIsModalOpen(true);
  };

  const openEditModal = (category: ICategory) => {
    setEditingCategory(category);
    setIsModalOpen(true);
  };

  const closeModal = () => {
    setIsModalOpen(false);
    setEditingCategory(null);
  };

  const handleToggleActive = async (category: ICategory) => {
    try {
      await adminCategoryAPI.toggleActive(category.id);

      toast.success(
        category.is_active ? "Category disabled" : "Category activated",
      );

      await refetch();
    } catch (error) {
      console.error(error);
      toast.error("Failed to update category status");
    }
  };

  const handleMutationSuccess = async () => {
    await refetch();
  };

  const handleDeleteSuccess = async () => {
    /*
     * If the deleted category was the last item
     * on the current page, move to the previous page.
     */
    if (result.items.length === 1 && filters.page > 1) {
      setPage(filters.page - 1);
      setDeleteCategory(null);
      return;
    }

    await refetch();
    setDeleteCategory(null);
  };

  return (
    <section className="space-y-6">
      {/* Header */}
      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-bold text-text-primary">Categories</h1>

          <p className="mt-1 text-sm text-text-secondary">
            Manage your store categories and category hierarchy.
          </p>
        </div>

        <Button
          type="button"
          onClick={openCreateModal}
          className="inline-flex items-center justify-center gap-2"
        >
          <Plus size={18} />
          Add Category
        </Button>
      </div>

      {/* Toolbar */}
      <Toolbar
        parentCategories={parentCategories.length ? parentCategories : null}
        filters={filters}
        onFilterChange={(field: CategoryFilterField, value: string) =>
          setFilter(field, value)
        }
        onPerPageChange={setPerPage}
        onClearFilters={clearFilters}
      />

      {/* Fetching indicator */}
      {isFetching && !isLoading && (
        <div className="text-sm text-text-secondary">
          Updating categories...
        </div>
      )}

      {/* Desktop table */}
      <Table
        isLoading={isLoading}
        length={result.items.length}
        emptyText="No categories found."
        width="850"
        itemsHeader={["Category", "Slug", "Parent", "Order", "Status"]}
      >
        {result.items.map((category) => (
          <CategoryRow
            key={category.id}
            category={category}
            handleToggleActive={handleToggleActive}
            openEditModal={openEditModal}
            openDeleteAlert={setDeleteCategory}
          />
        ))}
      </Table>

      {/* Mobile cards */}
      <div className="space-y-3 md:hidden">
        {isLoading ? (
          Array.from({ length: 5 }).map((_, index) => (
            <div
              key={index}
              className="h-40 animate-pulse rounded-2xl bg-bg-tertiary"
            />
          ))
        ) : result.items.length === 0 ? (
          <div className="rounded-2xl border border-border bg-bg-primary px-5 py-12 text-center">
            <p className="text-sm text-text-secondary">No categories found.</p>
          </div>
        ) : (
          result.items.map((category) => (
            <CategoryCardMobile
              key={category.id}
              category={category}
              handleToggleActive={handleToggleActive}
              openEditModal={openEditModal}
              openDeleteAlert={setDeleteCategory}
            />
          ))
        )}
      </div>

      {/* Pagination */}
      {isLoading ? (
        <PaginationSkeleton />
      ) : (
        result.pagination &&
        result.pagination.last_page > 1 && (
          <Pagination pagination={result.pagination} setPage={setPage} />
        )
      )}

      {/* Create / Edit Modal */}
      {isModalOpen && (
        <CreateOrEdit
          categories={result.items}
          closeModal={closeModal}
          editingCategory={editingCategory}
          onSuccess={handleMutationSuccess}
        />
      )}

      {/* Delete Modal */}
      {deleteCategory && (
        <DeleteCategory
          closeAlert={() => setDeleteCategory(null)}
          id={deleteCategory.id}
          name={deleteCategory.name}
          onSuccess={handleDeleteSuccess}
        />
      )}
    </section>
  );
};

export default AdminCategories;
