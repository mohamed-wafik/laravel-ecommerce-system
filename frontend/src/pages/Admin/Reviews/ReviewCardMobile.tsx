import type { IProps } from "./ReviewRow";
import ActionButton from "../../../components/ui/ActionButton";
import { Check, Trash2, X } from "lucide-react";
import renderStars from "../../../components/ui/renderStars";
import { formatDate } from "../../../lib/utils";

const ReviewCardMobile = ({
  handleApprove,
  handleReject,
  review,
  setDeleteReview,
}: IProps) => {
  return (
    <div
      key={review.id}
      className="rounded-2xl border border-border bg-bg-primary p-4 shadow-sm"
    >
      {/* Customer + status */}

      <div className="flex items-start justify-between gap-3">
        <div className="min-w-0">
          <p className="truncate font-semibold text-text-primary">
            {review.user.name}
          </p>

          <p className="truncate text-xs text-text-tertiary">
            {review.user.email}
          </p>
        </div>

        <span
          className={`shrink-0 rounded-full px-2.5 py-1 text-xs font-medium ${
            review.is_approved
              ? "bg-success-50 text-success"
              : "bg-warning-50 text-warning"
          }`}
        >
          {review.is_approved ? "Approved" : "Pending"}
        </span>
      </div>

      {/* Product */}

      <div className="mt-4 rounded-xl bg-bg-secondary p-3">
        <p className="text-sm font-medium text-text-primary">
          {review.product.name}
        </p>

        <p className="mt-1 text-xs text-text-tertiary">
          SKU: {review.product.sku}
        </p>
      </div>

      {/* Rating */}

      <div className="mt-4 flex items-center justify-between">
        {renderStars(review.rating)}

        <span className="text-xs text-text-secondary">
          {formatDate(review.created_at)}
        </span>
      </div>

      {/* Comment */}

      <p className="mt-3 text-sm leading-6 text-text-secondary">
        {review.comment}
      </p>

      {/* Actions */}

      <div className="mt-4 flex gap-2 border-t border-border pt-4">
        {!review.is_approved && (
          <ActionButton
            onClick={() => handleApprove(review)}
            label="Approve review"
          >
            <Check size={17} />
          </ActionButton>
        )}

        {review.is_approved && (
          <ActionButton
            onClick={() => handleReject(review)}
            label="Reject review"
            danger
          >
            <X size={17} />
          </ActionButton>
        )}
        <ActionButton
          onClick={() => handleReject(review)}
          label="Reject review"
          danger
        >
          <X size={17} />
        </ActionButton>
        <ActionButton label="Delete" danger onClick={setDeleteReview}>
          <Trash2 size={16} />
        </ActionButton>
      </div>
    </div>
  );
};

export default ReviewCardMobile;
