import { Plus } from "lucide-react";
import { useState } from "react";
import toast from "react-hot-toast";

import type { IPagination, IUser } from "../../../interface";

import Button from "../../../components/ui/Button";
import Pagination from "../../../components/ui/Pagnation";

import { adminUserAPI } from "../../../api/modules/Admin/user.api";

import Toolbar from "./Toolbar";
import UserRow from "./UserRow";
import UserCardMobile from "./UserCardMobile";
import DeleteUser from "./DeleteUser";
import CreateOrEdit from "./CreateOrEdit";
import PaginationSkeleton from "../../../components/Skeleton/PaginationSkeleton";
import { Table } from "../../../components/ui/Table";
import useUserFilters from "../../../hook/useUserFilters";

interface IUserResponse {
  items: IUser[];
  pagination: IPagination;
}

const AdminUsers = () => {
  const { filters, setFilter, setPage, setPerPage, clearFilters } =
    useUserFilters();

  const [deleteUser, setDeleteUser] = useState<IUser | null>(null);

  const [isModalOpen, setIsModalOpen] = useState(false);

  const [editingUser, setEditingUser] = useState<IUser | null>(null);

  /* -------------------------------------------------------------------------- */
  /* Fetch                                                                      */
  /* -------------------------------------------------------------------------- */

  const { data, isLoading, isFetching, refetch } = adminUserAPI.getAll(filters);

  const result: IUserResponse = data?.data ?? {
    items: [],
    pagination: {} as IPagination,
  };

  /* -------------------------------------------------------------------------- */
  /* Filters                                                                    */
  /* -------------------------------------------------------------------------- */

  const handleFilterChange = (
    field: "search" | "role" | "is_active",
    value: string,
  ) => {
    setFilter(field, value);
  };

  /* -------------------------------------------------------------------------- */
  /* Create / Edit                                                              */
  /* -------------------------------------------------------------------------- */

  const openCreateModal = () => {
    setEditingUser(null);
    setIsModalOpen(true);
  };

  const openEditModal = (user: IUser) => {
    setEditingUser(user);
    setIsModalOpen(true);
  };

  const closeModal = () => {
    setIsModalOpen(false);
    setEditingUser(null);
  };

  /* -------------------------------------------------------------------------- */
  /* Toggle Active                                                              */
  /* -------------------------------------------------------------------------- */

  const handleToggleActive = async (user: IUser) => {
    try {
      await adminUserAPI.toggleActive(user.id);

      toast.success(user.is_active ? "User disabled" : "User activated");

      await refetch();
    } catch (error) {
      console.error(error);

      toast.error("Failed to update user status");
    }
  };

  /* -------------------------------------------------------------------------- */
  /* Mutation success                                                           */
  /* -------------------------------------------------------------------------- */

  const handleUserMutationSuccess = async () => {
    await refetch();

    closeModal();
  };

  /* -------------------------------------------------------------------------- */
  /* Delete success                                                             */
  /* -------------------------------------------------------------------------- */

  const handleDeleteSuccess = async () => {
    if (result.items.length === 1 && filters.page > 1) {
      setPage(filters.page - 1);

      setDeleteUser(null);

      return;
    }

    await refetch();

    setDeleteUser(null);
  };

  /* -------------------------------------------------------------------------- */
  /* Render                                                                     */
  /* -------------------------------------------------------------------------- */

  return (
    <section className="space-y-6">
      {/* Header */}

      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-bold text-text-primary">Users</h1>

          <p className="mt-1 text-sm text-text-secondary">
            Manage your store customers and administrators.
          </p>
        </div>

        <Button
          type="button"
          onClick={openCreateModal}
          className="inline-flex items-center justify-center gap-2"
        >
          <Plus size={18} />
          Add User
        </Button>
      </div>

      {/* Toolbar */}

      <Toolbar
        filters={filters}
        onFilterChange={handleFilterChange}
        onPerPageChange={setPerPage}
        onClearFilters={clearFilters}
      />

      {/* Fetching */}

      {isFetching && !isLoading && (
        <div className="text-sm text-text-secondary">Updating users...</div>
      )}

      {/* Desktop */}

      <Table
        isLoading={isLoading}
        length={result.items.length}
        emptyText="No users found."
        width="950"
        itemsHeader={["User", "Phone", "Role", "Status"]}
      >
        {result.items.map((user) => (
          <UserRow
            key={user.id}
            user={user}
            handleToggleActive={handleToggleActive}
            openEditModal={openEditModal}
            openDeleteAlert={setDeleteUser}
          />
        ))}
      </Table>

      {/* Mobile */}

      <div className="space-y-3 md:hidden">
        {isLoading ? (
          Array.from({ length: 5 }).map((_, index) => (
            <div
              key={index}
              className="h-44 animate-pulse rounded-2xl bg-bg-tertiary"
            />
          ))
        ) : result.items.length === 0 ? (
          <div className="rounded-2xl border border-border bg-bg-primary px-5 py-12 text-center">
            <p className="text-sm text-text-secondary">No users found.</p>
          </div>
        ) : (
          result.items.map((user) => (
            <UserCardMobile
              key={user.id}
              user={user}
              handleToggleActive={handleToggleActive}
              openEditModal={openEditModal}
              openDeleteAlert={setDeleteUser}
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
          editingUser={editingUser}
          closeModal={closeModal}
          onSuccess={handleUserMutationSuccess}
        />
      )}

      {/* Delete */}

      {deleteUser && (
        <DeleteUser
          id={deleteUser.id}
          name={deleteUser.name}
          closeAlert={() => setDeleteUser(null)}
          onSuccess={handleDeleteSuccess}
        />
      )}
    </section>
  );
};

export default AdminUsers;
