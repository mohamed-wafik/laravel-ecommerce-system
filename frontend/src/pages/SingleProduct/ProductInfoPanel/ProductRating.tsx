import renderStars from "../../../components/ui/renderStars";
interface ProductRatingProps {
  avgRating: number;
  reviewsCount: number;
}

const ProductRating = ({ avgRating, reviewsCount }: ProductRatingProps) => {
  return (
    <div className="mb-6 flex flex-wrap items-center gap-3">
      {renderStars(avgRating)}

      <span className="text-sm font-semibold text-gray-700">
        {avgRating > 0 ? Number(avgRating).toFixed(1) : "No rating"}
      </span>

      <span className="text-sm text-gray-400">
        ({reviewsCount} {reviewsCount === 1 ? "review" : "reviews"})
      </span>
    </div>
  );
};

export default ProductRating;
