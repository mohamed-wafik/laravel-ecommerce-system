import { Star } from "lucide-react";

const renderStars = (rating: number) => {
  return (
    <div className="flex items-center gap-0.5">
      {Array.from({ length: 5 }).map((_, index) => (
        <Star
          key={index}
          size={15}
          className={
            index < rating
              ? "fill-current text-yellow-500"
              : "text-text-tertiary"
          }
        />
      ))}
    </div>
  );
};

export default renderStars;
