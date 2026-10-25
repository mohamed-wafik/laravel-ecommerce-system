import type { IReview } from "../../../interface";
import { Check, Eye, Trash2, X } from "lucide-react";
import ActionButton from "../../../components/ui/ActionButton";
import renderStars from "../../../components/ui/renderStars";
import { useNavigate } from "react-router-dom";
import { formatDate } from "../../../lib/utils";

export interface IProps {
  review: IReview;
  handleReject: (review: IReview) => Promise<void>;
  handleApprove: (review: IReview) => Promise<void>;
  setDeleteReview: () => void;
}
const ReviewRow = ({
  handleApprove,
  handleReject,
  review,
  setDeleteReview,
}: IProps) => {
  const navigate = useNavigate();
  return (
    <tr className="transition hover:bg-bg-secondary/50">
      {/* Customer */}

      <td className="px-5 py-4">
        <div>
          <p className="font-medium text-text-primary">{review.user.name}</p>

          <p className="mt-0.5 text-xs text-text-tertiary">
            {review.user.email}
          </p>
        </div>
      </td>

      {/* Product */}

      <td className="px-5 py-4">
        <p className="max-w-[180px] truncate font-medium text-text-primary">
          {review.product.name}
        </p>

        <p className="mt-0.5 text-xs text-text-tertiary">
          SKU: {review.product.sku}
        </p>
      </td>

      {/* Rating */}

      <td className="px-5 py-4">
        <div className="space-y-1">
          {renderStars(review.rating)}

          <span className="text-xs text-text-secondary">{review.rating}/5</span>
        </div>
      </td>

      {/* Comment */}

      <td className="px-5 py-4">
        <p className="max-w-[260px] truncate text-sm text-text-secondary">
          {review.comment}
        </p>

        <p className="mt-1 text-xs text-text-tertiary">
          {formatDate(review.created_at)}
        </p>
      </td>

      {/* Status */}

      <td className="px-5 py-4">
        <span
          className={`inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ${
            review.is_approved
              ? "bg-success-50 text-success"
              : "bg-warning-50 text-warning"
          }`}
        >
          {review.is_approved ? "Approved" : "Pending"}
        </span>
      </td>

      {/* Actions */}

      <td className="px-5 py-4">
        <div className="flex items-center justify-end gap-2">
          <ActionButton
            onClick={() =>
              navigate(`/admin/reviews/${review.id}`, { replace: true })
            }
            label="View Review"
          >
            <Eye size={17} />
          </ActionButton>
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
          <ActionButton label="Delete" danger onClick={setDeleteReview}>
            <Trash2 size={16} />
          </ActionButton>
        </div>
      </td>
    </tr>
  );
};

export default ReviewRow;
