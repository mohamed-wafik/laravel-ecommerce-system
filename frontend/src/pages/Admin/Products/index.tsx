import { Plus } from "lucide-react";
import { useState } from "react";

import type { IPagination, IProduct } from "../../../interface";

import Button from "../../../components/ui/Button";
import Pagination from "../../../components/ui/Pagnation";

import EmptyState from "./EmptyState";
import ProductRow from "./ProductRow";
import CreateOrEdit from "./CreateOrEdit";
import DeleteProduct from "./DeleteProduct";
import Toolbar from "./Toolbar";
import ProductCardMobile from "./ProductCardMobile";

import { adminProductAPI } from "../../../api/modules/Admin/product.api";
import { adminCategoryAPI } from "../../../api/modules/Admin/category.api";
import { adminBrandAPI } from "../../../api/modules/Admin/brand.api";

import toast from "react-hot-toast";

import PaginationSkeleton from "../../../components/Skeleton/PaginationSkeleton";
import { Table } from "../../../components/ui/Table";
import useProductFilters, {
  type ProductFilterField,
} from "../../../hook/useProductFilters";

/* -------------------------------------------------------------------------- */
/* Types                                                                      */
/* -------------------------------------------------------------------------- */

interface IProductResponse {
  items: IProduct[];
  pagination: IPagination;
}

/* -------------------------------------------------------------------------- */
/* Component                                                                  */
/* -------------------------------------------------------------------------- */

const AdminProducts = () => {
  /* ------------------------------------------------------------------------ */
  /* Filters                                                                  */
  /* ------------------------------------------------------------------------ */

  const { filters, setFilter, setPage, setPerPage, clearFilters } =
    useProductFilters();

  /* ------------------------------------------------------------------------ */
  /* Modal state                                                              */
  /* ------------------------------------------------------------------------ */

  const [isModalOpen, setIsModalOpen] = useState(false);

  const [editingProduct, setEditingProduct] = useState<IProduct | null>(null);

  const [deletingProduct, setDeletingProduct] = useState<IProduct | null>(null);

  /* ------------------------------------------------------------------------ */
  /* API params                                                               */
  /* ------------------------------------------------------------------------ */

  /* ------------------------------------------------------------------------ */
  /* Products Query                                                           */
  /* ------------------------------------------------------------------------ */

  const { isLoading, isFetching, data, refetch } =
    adminProductAPI.list(filters);

  /* ------------------------------------------------------------------------ */
  /* Categories Query                                                         */
  /* ------------------------------------------------------------------------ */

  const { data: resCategories } = adminCategoryAPI.getAll();

  const categories = resCategories?.data?.items ?? [];

  /* ------------------------------------------------------------------------ */
  /* Brands Query                                                             */
  /* ------------------------------------------------------------------------ */

  const { data: resBrands } = adminBrandAPI.getAll();

  const brands = resBrands?.data?.items ?? [];

  /* ------------------------------------------------------------------------ */
  /* Product response                                                         */
  /* ------------------------------------------------------------------------ */

  const result: IProductResponse = data?.data ?? {
    items: [],
    pagination: {} as IPagination,
  };

  /* ------------------------------------------------------------------------ */
  /* Product mutation success                                                 */
  /* ------------------------------------------------------------------------ */

  const handleProductMutationSuccess = async () => {
    await refetch();
  };

  /* ------------------------------------------------------------------------ */
  /* Create / Edit                                                             */
  /* ------------------------------------------------------------------------ */

  const openCreateModal = () => {
    setEditingProduct(null);
    setIsModalOpen(true);
  };

  const openEditModal = (product: IProduct) => {
    setEditingProduct(product);
    setIsModalOpen(true);
  };

  const closeModal = () => {
    setIsModalOpen(false);
    setEditingProduct(null);
  };

  /* ------------------------------------------------------------------------ */
  /* Delete                                                                    */
  /* ------------------------------------------------------------------------ */

  const handleDeleteSuccess = async () => {
    /*
     * If the deleted product was the last item on the current page,
     * move to the previous page.
     */
    if (result.items.length === 1 && filters.page > 1) {
      setPage(filters.page - 1);
      setDeletingProduct(null);

      return;
    }

    await refetch();

    setDeletingProduct(null);
  };

  /* ------------------------------------------------------------------------ */
  /* Toggle active                                                             */
  /* ------------------------------------------------------------------------ */

  const handleToggleActive = async (product: IProduct) => {
    try {
      await adminProductAPI.toggleActive(product.id);

      toast.success(
        product.is_active ? "Product disabled" : "Product activated",
      );

      await refetch();
    } catch (error) {
      console.error(error);

      toast.error("Failed to update product status");
    }
  };

  /* ------------------------------------------------------------------------ */
  /* Render                                                                    */
  /* ------------------------------------------------------------------------ */

  return (
    <section className="space-y-6">
      {/* Header */}

      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-bold text-text-primary">Products</h1>

          <p className="mt-1 text-sm text-text-secondary">
            Manage your store products, inventory and variants.
          </p>
        </div>

        <Button
          type="button"
          onClick={openCreateModal}
          className="inline-flex items-center justify-center gap-2"
        >
          <Plus size={18} />
          Add Product
        </Button>
      </div>

      {/* Toolbar */}

      <Toolbar
        categories={categories}
        brands={brands}
        filters={filters}
        onFilterChange={(field: ProductFilterField, value: string) =>
          setFilter(field, value)
        }
        onPerPageChange={setPerPage}
        onClearFilters={clearFilters}
      />

      {/* Loading indicator */}

      {isFetching && !isLoading && (
        <div className="text-sm text-text-secondary">Updating products...</div>
      )}

      {/* Desktop table */}

      <Table
        isLoading={isLoading}
        length={result.items.length}
        emptyText="No products found."
        width="900"
        itemsHeader={["Product", "Category", "Price", "Stock", "Status"]}
      >
        {result.items.map((product) => (
          <ProductRow
            key={product.id}
            product={product}
            handleToggleActive={handleToggleActive}
            openEditModal={openEditModal}
            setDeletingProduct={setDeletingProduct}
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
          <EmptyState />
        ) : (
          result.items.map((product) => (
            <ProductCardMobile
              key={product.id}
              product={product}
              handleToggleActive={handleToggleActive}
              openEditModal={openEditModal}
              setDeletingProduct={setDeletingProduct}
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
          categories={categories}
          brands={brands}
          editingProduct={editingProduct}
          closeModal={closeModal}
          onSuccess={handleProductMutationSuccess}
        />
      )}

      {/* Delete Alert */}

      {deletingProduct && (
        <DeleteProduct
          id={deletingProduct.id}
          name={deletingProduct.name}
          closeAlert={() => setDeletingProduct(null)}
          onSuccess={handleDeleteSuccess}
        />
      )}
    </section>
  );
};

export default AdminProducts;
