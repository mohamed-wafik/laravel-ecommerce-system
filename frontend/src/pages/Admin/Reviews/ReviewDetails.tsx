import { useEffect } from "react";
import { Link, useNavigate, useParams } from "react-router-dom";
import {
  ArrowLeft,
  Calendar,
  CheckCircle2,
  Clock,
  Mail,
  Package,
  Phone,
  Star,
  User,
  XCircle,
} from "lucide-react";

import { adminReviewAPI } from "../../../api/modules/Admin/review.api";
import renderStars from "../../../components/ui/renderStars";
import Empty from "../ReviewDetails/Empty";
import Loading from "../ReviewDetails/Loading";
import { formatDate } from "../../../lib/utils";
import SectionCard from "../ProductDetails/SectionCard";
import SectionHeader from "../ProductDetails/SectionHeader";
import InfoItem from "../../../components/ui/InfoItem";

const ReviewDetails = () => {
  const navigate = useNavigate();

  const { reviewId } = useParams<{
    reviewId: string;
  }>();

  useEffect(() => {
    if (!reviewId) {
      navigate("/admin/review", { replace: true });
    }
  }, [reviewId, navigate]);

  const { data, isLoading, isFetching } = adminReviewAPI.getById(
    String(reviewId),
  );

  const review = data?.data;

  /* -------------------------------------------------------------------------- */
  /* Loading                                                                    */
  /* -------------------------------------------------------------------------- */

  if (isLoading) {
    return <Loading />;
  }

  /* -------------------------------------------------------------------------- */
  /* Empty                                                                      */
  /* -------------------------------------------------------------------------- */

  if (!review) {
    return <Empty />;
  }

  const {
    comment,
    rating,
    is_approved,
    created_at,
    user,
    product,
    order_item_id,
  } = review;

  return (
    <section className="space-y-6">
      {/* -------------------------------------------------------------------- */}
      {/* Header                                                               */}
      {/* -------------------------------------------------------------------- */}

      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <Link
            to={"/admin/reviews"}
            className="mb-3 inline-flex items-center gap-2 text-sm font-medium text-text-secondary transition hover:text-text-primary"
          >
            <ArrowLeft size={17} />
            Back to reviews
          </Link>

          <h1 className="text-2xl font-bold text-text-primary">
            Review Details
          </h1>

          <p className="mt-1 text-sm text-text-secondary">
            View customer review, product, and approval information.
          </p>
        </div>

        {/* Status */}

        {is_approved ? (
          <span className="inline-flex w-fit items-center gap-2 rounded-full bg-success-50 px-3 py-1.5 text-sm font-medium text-success">
            <CheckCircle2 size={16} />
            Approved
          </span>
        ) : (
          <span className="inline-flex w-fit items-center gap-2 rounded-full bg-warning-50 px-3 py-1.5 text-sm font-medium text-warning">
            <Clock size={16} />
            Pending / Rejected
          </span>
        )}
      </div>

      {/* Updating */}

      {isFetching && !isLoading && (
        <div className="text-sm text-text-secondary">Updating review...</div>
      )}

      {/* -------------------------------------------------------------------- */}
      {/* Main Grid                                                            */}
      {/* -------------------------------------------------------------------- */}

      <div className="grid gap-6 lg:grid-cols-3">
        {/* ================================================================== */}
        {/* Left                                                               */}
        {/* ================================================================== */}

        <div className="space-y-6 lg:col-span-2">
          {/* Review */}

          <SectionCard>
            <SectionHeader
              title="Customer Review"
              icon={<Star size={18} className="fill-current text-warning" />}
            />
            <div className="p-4 sm:p-5">
              {/* Rating */}

              <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                  <p className="text-xs font-medium uppercase tracking-wide text-text-tertiary">
                    Rating
                  </p>

                  <div className="mt-2 flex items-center gap-3">
                    <div className="flex items-center">
                      {renderStars(rating)}
                    </div>

                    <span className="text-lg font-bold text-text-primary">
                      {rating}/5
                    </span>
                  </div>
                </div>

                {/* Date */}

                <div className="flex items-center gap-2 text-sm text-text-secondary">
                  <Calendar size={16} />

                  {formatDate(created_at)}
                </div>
              </div>

              {/* Comment */}

              <div className="mt-6 rounded-xl border border-border bg-bg-secondary p-4 sm:p-5">
                <p className="text-xs font-medium uppercase tracking-wide text-text-tertiary">
                  Comment
                </p>

                <p className="mt-3 text-sm leading-7 text-text-primary">
                  {comment || "No comment provided."}
                </p>
              </div>
            </div>
          </SectionCard>
          {/* Product */}

          <SectionCard>
            <SectionHeader
              title="Product Information"
              icon={<Package size={32} className="text-text-tertiary" />}
            />{" "}
            <div className="p-4 sm:p-5">
              <div className="flex flex-col gap-5 sm:flex-row">
                {/* Product Icon */}

                <div className="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-bg-secondary">
                  <Package size={32} className="text-text-tertiary" />
                </div>

                {/* Product */}

                <div className="min-w-0 flex-1">
                  <h3 className="truncate text-lg font-semibold text-text-primary">
                    {product?.name || "Unknown product"}
                  </h3>

                  <p className="mt-1 text-sm text-text-secondary">
                    {product?.slug}
                  </p>

                  <div className="mt-4 grid gap-3 sm:grid-cols-2">
                    <InfoItem label="Product ID" value={product?.id} />

                    <InfoItem label="SKU" value={product?.sku} />

                    <InfoItem
                      label="Price"
                      value={
                        product?.price
                          ? `$${Number(product.price).toFixed(2)}`
                          : "-"
                      }
                    />

                    <InfoItem
                      label="Stock"
                      value={String(product?.stock_quantity ?? "-")}
                    />
                  </div>
                </div>
              </div>
            </div>
          </SectionCard>

          {/* Review Metadata */}

          <SectionCard>
            <SectionHeader title="Review Information" />{" "}
            <div className="grid gap-4 p-4 sm:grid-cols-2 sm:p-5">
              <InfoItem label="Review ID" value={review.id} />

              <InfoItem label="Product ID" value={review.product_id} />

              <InfoItem label="User ID" value={review.user_id} />

              <InfoItem
                label="Order Item ID"
                value={order_item_id || "Not linked"}
              />

              <InfoItem
                label="Created At"
                value={new Date(created_at).toLocaleString("en-US")}
              />

              <InfoItem
                label="Approval"
                value={is_approved ? "Approved" : "Pending / Rejected"}
              />
            </div>
          </SectionCard>
        </div>
        {/* ================================================================== */}
        {/* Right                                                              */}
        {/* ================================================================== */}

        <div className="space-y-6">
          {/* Customer */}

          <SectionCard>
            <SectionHeader
              title="Customer"
              icon={<User size={18} className="text-primary" />}
            />

            <div className="p-4 sm:p-5">
              {/* Avatar */}

              <div className="flex items-center gap-3">
                {user?.avatar_url ? (
                  <img
                    src={user.avatar_url}
                    alt={user.name}
                    className="h-12 w-12 rounded-full object-cover"
                  />
                ) : (
                  <div className="flex h-12 w-12 items-center justify-center rounded-full bg-bg-secondary text-sm font-semibold text-text-secondary">
                    {user?.name
                      ?.split(" ")
                      .map((name: string) => name[0])
                      .join("")
                      .slice(0, 2)
                      .toUpperCase()}
                  </div>
                )}

                <div className="min-w-0">
                  <p className="truncate font-semibold text-text-primary">
                    {user?.name || "Unknown user"}
                  </p>

                  <p className="truncate text-xs text-text-tertiary">
                    {user?.role || "customer"}
                  </p>
                </div>
              </div>

              {/* Contact */}

              <div className="mt-5 space-y-4">
                <div className="flex items-start gap-3">
                  <Mail
                    size={17}
                    className="mt-0.5 shrink-0 text-text-tertiary"
                  />

                  <div className="min-w-0">
                    <p className="text-xs text-text-tertiary">Email</p>

                    <p className="mt-0.5 break-all text-sm text-text-primary">
                      {user?.email || "-"}
                    </p>
                  </div>
                </div>

                <div className="flex items-start gap-3">
                  <Phone
                    size={17}
                    className="mt-0.5 shrink-0 text-text-tertiary"
                  />

                  <div>
                    <p className="text-xs text-text-tertiary">Phone</p>

                    <p className="mt-0.5 text-sm text-text-primary">
                      {user?.phone || "-"}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </SectionCard>

          {/* Status Card */}
          <SectionCard>
            <SectionHeader
              title="Approval Status"
              icon={<User size={18} className="text-primary" />}
            />
            <div className="p-4 sm:p-5">
              {is_approved ? (
                <div className="flex items-start gap-3 rounded-xl bg-success-50 p-4">
                  <CheckCircle2
                    size={20}
                    className="mt-0.5 shrink-0 text-success"
                  />

                  <div>
                    <p className="text-sm font-semibold text-success">
                      Review Approved
                    </p>

                    <p className="mt-1 text-xs leading-5 text-text-secondary">
                      This review is currently visible as an approved customer
                      review.
                    </p>
                  </div>
                </div>
              ) : (
                <div className="flex items-start gap-3 rounded-xl bg-warning-50 p-4">
                  <XCircle size={20} className="mt-0.5 shrink-0 text-warning" />

                  <div>
                    <p className="text-sm font-semibold text-warning">
                      Review Not Approved
                    </p>

                    <p className="mt-1 text-xs leading-5 text-text-secondary">
                      This review is currently pending or rejected.
                    </p>
                  </div>
                </div>
              )}
            </div>
          </SectionCard>
        </div>
      </div>
    </section>
  );
};
export default ReviewDetails;
