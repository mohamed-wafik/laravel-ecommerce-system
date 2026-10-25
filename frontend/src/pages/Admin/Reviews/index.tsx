import { useState } from "react";
import toast from "react-hot-toast";

import type { IPagination, IReview } from "../../../interface";

import Pagination from "../../../components/ui/Pagnation";

import { adminReviewAPI } from "../../../api/modules/Admin/review.api";

import ReviewCardMobile from "./ReviewCardMobile";
import ReviewRow from "./ReviewRow";
import Toolbar from "./Toolbar";
import DeleteReview from "./DeleteReview";

import PaginationSkeleton from "../../../components/Skeleton/PaginationSkeleton";
import { Table } from "../../../components/ui/Table";
import useReviewFilters from "../../../hook/useReviewFilters";

interface IReviewResponse {
  items: IReview[];
  pagination: IPagination;
}

const AdminReviews = () => {
  const { filters, setFilter, setPage, setPerPage, clearFilters } =
    useReviewFilters();

  const [deleteReview, setDeleteReview] = useState<IReview | null>(null);

  /* -------------------------------------------------------------------------- */
  /* Fetch                                                                      */
  /* -------------------------------------------------------------------------- */

  const { data, isLoading, isFetching, refetch } = adminReviewAPI.list(filters);

  const result: IReviewResponse = data?.data ?? {
    items: [],
    pagination: {} as IPagination,
  };

  /* -------------------------------------------------------------------------- */
  /* Approve                                                                    */
  /* -------------------------------------------------------------------------- */

  const handleApprove = async (review: IReview) => {
    try {
      await adminReviewAPI.approve(review.id);

      toast.success("Review approved");

      await refetch();
    } catch (error) {
      console.error(error);

      toast.error("Failed to approve review");
    }
  };

  /* -------------------------------------------------------------------------- */
  /* Reject                                                                     */
  /* -------------------------------------------------------------------------- */

  const handleReject = async (review: IReview) => {
    try {
      await adminReviewAPI.reject(review.id);

      toast.success("Review rejected");

      await refetch();
    } catch (error) {
      console.error(error);

      toast.error("Failed to reject review");
    }
  };

  /* -------------------------------------------------------------------------- */
  /* Delete success                                                             */
  /* -------------------------------------------------------------------------- */

  const handleDeleteSuccess = async () => {
    if (result.items.length === 1 && filters.page > 1) {
      setPage(filters.page - 1);
    } else {
      await refetch();
    }

    setDeleteReview(null);
  };

  /* -------------------------------------------------------------------------- */
  /* Render                                                                     */
  /* -------------------------------------------------------------------------- */

  return (
    <section className="space-y-6">
      {/* Header */}

      <div>
        <h1 className="text-2xl font-bold text-text-primary">Reviews</h1>

        <p className="mt-1 text-sm text-text-secondary">
          Manage customer reviews, ratings, and approval status.
        </p>
      </div>

      {/* Toolbar */}

      <Toolbar
        filters={filters}
        onFilterChange={setFilter}
        onPerPageChange={setPerPage}
        onClearFilters={clearFilters}
      />

      {/* Fetching */}

      {isFetching && !isLoading && (
        <div className="text-sm text-text-secondary">Updating reviews...</div>
      )}

      {/* Desktop */}

      <Table
        isLoading={isLoading}
        length={result.items.length}
        emptyText="No reviews found."
        width="1050"
        itemsHeader={["Customer", "Product", "Rating", "Comment", "Status"]}
      >
        {result.items.map((review) => (
          <ReviewRow
            key={review.id}
            review={review}
            handleApprove={handleApprove}
            handleReject={handleReject}
            setDeleteReview={() => setDeleteReview(review)}
          />
        ))}
      </Table>

      {/* Mobile */}

      <div className="space-y-3 md:hidden">
        {isLoading ? (
          Array.from({ length: 5 }).map((_, index) => (
            <div
              key={index}
              className="h-56 animate-pulse rounded-2xl bg-bg-tertiary"
            />
          ))
        ) : result.items.length === 0 ? (
          <div className="rounded-2xl border border-border bg-bg-primary px-5 py-12 text-center">
            <p className="text-sm text-text-secondary">No reviews found.</p>
          </div>
        ) : (
          result.items.map((review) => (
            <ReviewCardMobile
              key={review.id}
              review={review}
              handleApprove={handleApprove}
              handleReject={handleReject}
              setDeleteReview={() => setDeleteReview(review)}
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

      {/* Delete */}

      {deleteReview && (
        <DeleteReview
          id={deleteReview.id}
          customerName={deleteReview.user.name}
          closeAlert={() => setDeleteReview(null)}
          onSuccess={handleDeleteSuccess}
        />
      )}
    </section>
  );
};

export default AdminReviews;
