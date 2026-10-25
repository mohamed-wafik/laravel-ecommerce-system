import { Star } from "lucide-react";
import ReviewCard from "./ReviewCard";
import type { IReview } from "../../../interface";
import ReviewModel from "./ReviewModel";
import renderStars from "../../../components/ui/renderStars";

interface ReviewsTabProps {
  productId: string;
  reviews: IReview[];
  isLoading: boolean;
}

interface RatingStats {
  average: number;
  total: number;
  distribution: Record<number, number>;
  percentages: Record<number, number>;
}

// ─────────────────────────────────────────────────────────────────────────────
// Rating Bar
// ─────────────────────────────────────────────────────────────────────────────

const RatingBar = ({
  rating,
  count,
  percentage,
}: {
  rating: number;
  count: number;
  percentage: number;
}) => {
  return (
    <div className="flex items-center gap-3">
      {/* Star number */}
      <div className="flex items-center gap-1 w-8 shrink-0">
        <span className="text-xs font-semibold text-gray-500">{rating}</span>

        <Star className="w-3 h-3 fill-yellow-400 text-yellow-400" />
      </div>

      {/* Progress */}
      <div className="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
        <div
          className="h-full bg-yellow-400 rounded-full transition-all duration-500"
          style={{
            width: `${percentage}%`,
          }}
        />
      </div>

      {/* Count */}
      <span className="text-xs text-gray-400 w-7 text-right">{count}</span>
    </div>
  );
};

// ─────────────────────────────────────────────────────────────────────────────
// Statistics
// ─────────────────────────────────────────────────────────────────────────────

const calculateStats = (reviews: IReview[]): RatingStats => {
  const distribution: Record<number, number> = {
    1: 0,
    2: 0,
    3: 0,
    4: 0,
    5: 0,
  };

  reviews.forEach((review) => {
    const rating = Math.round(Number(review.rating));

    if (rating >= 1 && rating <= 5) {
      distribution[rating]++;
    }
  });

  const total = reviews.length;

  const totalRating = reviews.reduce(
    (sum, review) => sum + Number(review.rating),
    0,
  );

  const average = total > 0 ? totalRating / total : 0;

  const percentages: Record<number, number> = {
    1: total ? (distribution[1] / total) * 100 : 0,
    2: total ? (distribution[2] / total) * 100 : 0,
    3: total ? (distribution[3] / total) * 100 : 0,
    4: total ? (distribution[4] / total) * 100 : 0,
    5: total ? (distribution[5] / total) * 100 : 0,
  };

  return {
    average,
    total,
    distribution,
    percentages,
  };
};

// ─────────────────────────────────────────────────────────────────────────────
// Main Component
// ─────────────────────────────────────────────────────────────────────────────

const ReviewsTab = ({ productId, reviews, isLoading }: ReviewsTabProps) => {
  const stats = calculateStats(reviews);

  return (
    <div>
      {/* ───────────────────── Statistics Header ───────────────────── */}
      <div className="p-6 sm:p-8 border-b border-gray-100">
        <div className="flex flex-col lg:flex-row lg:items-center gap-8 lg:gap-12">
          {/* Overall Rating */}
          <div className="flex flex-col items-center justify-center min-w-[150px]">
            <span className="text-5xl font-black text-dark tracking-tight">
              {stats.average.toFixed(1)}
            </span>

            <span className="text-sm text-gray-400 mt-1">out of 5</span>

            {renderStars(stats.average)}

            <span className="text-xs text-gray-400 mt-2">
              Based on {stats.total} review
              {stats.total !== 1 ? "s" : ""}
            </span>
          </div>

          {/* Rating Distribution */}
          <div className="flex-1 w-full max-w-xl">
            <div className="flex flex-col gap-2.5">
              {[5, 4, 3, 2, 1].map((rating) => (
                <RatingBar
                  key={rating}
                  rating={rating}
                  count={stats.distribution[rating]}
                  percentage={stats.percentages[rating]}
                />
              ))}
            </div>
          </div>

          {/* Write Review */}
          {reviews.length ? (
            <div className="lg:ml-auto">
              <ReviewModel id={productId} />
            </div>
          ) : null}
        </div>
      </div>

      {/* ───────────────────── Reviews ───────────────────── */}
      <div className="p-6 sm:p-8">
        {isLoading ? (
          <div className="flex flex-col items-center justify-center py-16">
            <div className="w-7 h-7 border-2 border-primary border-t-transparent rounded-full animate-spin" />

            <p className="text-sm text-gray-400 mt-3">Loading reviews...</p>
          </div>
        ) : reviews.length > 0 ? (
          <div className="space-y-6">
            {reviews.map((review) => (
              <ReviewCard key={review.id} rev={review} />
            ))}
          </div>
        ) : (
          <div className="flex flex-col items-center justify-center py-16 text-center">
            <div className="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mb-4">
              <Star className="w-6 h-6 text-gray-300" />
            </div>

            <h3 className="text-base font-bold text-dark">No reviews yet</h3>

            <p className="text-sm text-gray-400 mt-1 max-w-xs">
              Be the first to share your experience with this product.
            </p>

            <div className="mt-5">
              <ReviewModel id={productId} />
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default ReviewsTab;
