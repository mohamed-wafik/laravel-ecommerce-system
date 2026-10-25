import type { IReview } from "../../../interface";
import renderStars from "../../../components/ui/renderStars";

const ReviewCard = ({ rev }: { rev: IReview }) => (
  <div className="flex gap-4 p-5 rounded-xl border border-gray-100 hover:border-gray-200 transition-colors">
    {/* Avatar */}
    <div className="shrink-0">
      {rev.user?.avatar_url ? (
        <img
          src={rev.user.avatar_url}
          alt={rev.user.name}
          className="w-10 h-10 rounded-full object-cover border-2 border-gray-100"
        />
      ) : (
        <div className="w-10 h-10 rounded-full bg-primary/10 text-primary font-bold flex items-center justify-center text-sm">
          {(rev.user?.name || "A").charAt(0).toUpperCase()}
        </div>
      )}
    </div>

    <div className="flex-1 min-w-0">
      <div className="flex items-start justify-between gap-2 mb-1">
        <div>
          <p className="text-sm font-semibold text-dark">
            {rev.user?.name || "Anonymous"}
          </p>
          {renderStars(rev.rating)}
        </div>
        <span className="text-xs text-gray-400 shrink-0">
          {new Date(rev.created_at).toLocaleDateString()}
        </span>
      </div>

      {rev.comment && (
        <p className="text-sm text-gray-600 mt-2 leading-relaxed">
          {rev.comment}
        </p>
      )}
    </div>
  </div>
);

export default ReviewCard;
