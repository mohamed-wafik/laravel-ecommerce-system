import { Plus } from "lucide-react";
import { useState } from "react";
import toast from "react-hot-toast";

import type { IPagination } from "../../../interface";

import Button from "../../../components/ui/Button";
import Pagination from "../../../components/ui/Pagnation";
import Toolbar from "./Toolbar";
import BrandCardMobile from "./BrandCardMobile";
import DeleteBrand from "./DeleteBrand";
import BrandRow from "./BrandRow";
import CreateOrEdit from "./CreateOrEdit";
import PaginationSkeleton from "../../../components/Skeleton/PaginationSkeleton";
import { adminBrandAPI } from "../../../api/modules/Admin/brand.api";
import { Table } from "../../../components/ui/Table";

import useBrandFilters, {
  type BrandFilterField,
} from "../../../hook/useBrandFilters";

/* -------------------------------------------------------------------------- */
/* Types                                                                      */
/* -------------------------------------------------------------------------- */

export interface IBrand {
  id: string;
  name: string;
  slug: string;
  logo_url: string | null;
  logo_public_id: string | null;
  is_active: boolean;
}

interface IBrandResponse {
  items: IBrand[];
  pagination: IPagination;
}

/* -------------------------------------------------------------------------- */
/* Component                                                                  */
/* -------------------------------------------------------------------------- */

const AdminBrands = () => {
  const { filters, setFilter, setPage, setPerPage, clearFilters } =
    useBrandFilters();

  const [deleteBrand, setDeleteBrand] = useState<IBrand | null>(null);

  const [isModalOpen, setIsModalOpen] = useState(false);

  const [editingBrand, setEditingBrand] = useState<IBrand | null>(null);

  /* ------------------------------------------------------------------------ */
  /* Query params                                                             */
  /* ------------------------------------------------------------------------ */

  /* ------------------------------------------------------------------------ */
  /* Fetch brands                                                             */
  /* ------------------------------------------------------------------------ */

  const { data, isLoading, isFetching, refetch } =
    adminBrandAPI.getAll(filters);

  /* ------------------------------------------------------------------------ */
  /* API response                                                             */
  /* ------------------------------------------------------------------------ */

  const result: IBrandResponse = data?.data ?? {
    items: [],
    pagination: {} as IPagination,
  };

  /* ------------------------------------------------------------------------ */
  /* Create / Edit                                                            */
  /* ------------------------------------------------------------------------ */

  const openCreateModal = () => {
    setEditingBrand(null);
    setIsModalOpen(true);
  };

  const openEditModal = (brand: IBrand) => {
    setEditingBrand(brand);
    setIsModalOpen(true);
  };

  const closeModal = () => {
    setIsModalOpen(false);
    setEditingBrand(null);
  };

  /* ------------------------------------------------------------------------ */
  /* Toggle active                                                            */
  /* ------------------------------------------------------------------------ */

  const handleToggleActive = async (brand: IBrand) => {
    try {
      await adminBrandAPI.toggleActive(brand.id, !brand.is_active);

      toast.success(brand.is_active ? "Brand disabled" : "Brand activated");

      await refetch();
    } catch (error) {
      console.error(error);

      toast.error("Failed to update brand status");
    }
  };

  /* ------------------------------------------------------------------------ */
  /* Create / Update success                                                  */
  /* ------------------------------------------------------------------------ */

  const handleBrandMutationSuccess = async () => {
    await refetch();
    closeModal();
  };

  /* ------------------------------------------------------------------------ */
  /* Delete success                                                           */
  /* ------------------------------------------------------------------------ */

  const handleDeleteSuccess = async () => {
    /*
     * If the deleted brand was the last item
     * on the current page, move to the previous page.
     */

    if (result.items.length === 1 && filters.page > 1) {
      setPage(filters.page - 1);
      setDeleteBrand(null);

      return;
    }

    await refetch();
    setDeleteBrand(null);
  };

  /* ------------------------------------------------------------------------ */
  /* Render                                                                   */
  /* ------------------------------------------------------------------------ */

  return (
    <section className="space-y-6">
      {/* Header */}

      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-bold text-text-primary">Brands</h1>

          <p className="mt-1 text-sm text-text-secondary">
            Manage your store brands and their availability.
          </p>
        </div>

        <Button
          type="button"
          onClick={openCreateModal}
          className="inline-flex items-center justify-center gap-2"
        >
          <Plus size={18} />
          Add Brand
        </Button>
      </div>

      {/* Toolbar */}

      <Toolbar
        filters={filters}
        onFilterChange={(field: BrandFilterField, value: string) =>
          setFilter(field, value)
        }
        onPerPageChange={setPerPage}
        onClearFilters={clearFilters}
      />

      {/* Fetching indicator */}

      {isFetching && !isLoading && (
        <div className="text-sm text-text-secondary">Updating brands...</div>
      )}

      {/* Desktop table */}

      <Table
        isLoading={isLoading}
        length={result.items.length}
        emptyText="No brands found."
        width="750"
        itemsHeader={["Brand", "Slug", "Status"]}
      >
        {result.items.map((brand) => (
          <BrandRow
            key={brand.id}
            brand={brand}
            handleToggleActive={handleToggleActive}
            openEditModal={openEditModal}
            openDeleteAlert={setDeleteBrand}
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
            <p className="text-sm text-text-secondary">No brands found.</p>
          </div>
        ) : (
          result.items.map((brand) => (
            <BrandCardMobile
              key={brand.id}
              brand={brand}
              handleToggleActive={handleToggleActive}
              openEditModal={openEditModal}
              openDeleteAlert={setDeleteBrand}
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

      {/* Create / Edit */}

      {isModalOpen && (
        <CreateOrEdit
          closeModal={closeModal}
          editingBrand={editingBrand}
          onSuccess={handleBrandMutationSuccess}
        />
      )}

      {/* Delete */}

      {deleteBrand && (
        <DeleteBrand
          closeAlert={() => setDeleteBrand(null)}
          id={deleteBrand.id}
          name={deleteBrand.name}
          onSuccess={handleDeleteSuccess}
        />
      )}
    </section>
  );
};

export default AdminBrands;
