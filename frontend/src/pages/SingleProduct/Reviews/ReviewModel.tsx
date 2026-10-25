import { useState } from "react";
import { Star } from "lucide-react";
import Button from "../../../components/ui/Button";
import toast from "react-hot-toast";

import { useAuthStore } from "../../../store/useAuth";

import { reviewAPI } from "../../../api/modules/Customer/review.api";

interface IProps {
  id: string;
}

const ReviewModel = ({ id }: IProps) => {
  const { user } = useAuthStore();
  const [isOpen, setIsOpen] = useState(false);
  const [rating, setRating] = useState<number>(0);
  const [hoverRating, setHoverRating] = useState<number>(0);
  const [review, setReview] = useState("");
  const [anonymous, setAnonymous] = useState(false);
  const [isLoading, setIsLoading] = useState<boolean>(false);

  const handlerClose = () => {
    setIsOpen(false);
    setRating(0);
    setHoverRating(0);
    setReview("");
    setAnonymous(false);
  };

  const handlerOnSubmit = async () => {
    if (rating === 0) {
      toast.error("Please select a rating");
      return;
    }

    if (review.trim().length < 5) {
      toast.error("Review must be at least 5 characters");
      return;
    }

    setIsLoading(true);
    try {
      const res = await reviewAPI.create(id, {
        rating: rating,
        comment: review,
      });

      console.log("Review submitted:", res.data);

      toast.success("Review submitted successfully!");

      handlerClose();
    } catch (err: any) {
      toast.error(
        err?.response?.data?.message || "Failed to submit review. Try again.",
      );
    } finally {
      setIsLoading(false);
    }
  };

  const openModal = () => {
    if (!user) {
      toast.error("Please login to submit a review");
      return;
    }

    setIsOpen(true);
  };

  return (
    <div>
      <Button onClick={openModal}>Rate This Product</Button>

      {isOpen && (
        <>
          {/* Backdrop */}
          <div
            className="fixed inset-0 bg-black/40 backdrop-blur-sm z-50"
            onClick={handlerClose}
          ></div>

          {/* Modal */}
          <div className="fixed inset-0 flex items-center justify-center px-4 z-50">
            <div className="bg-white w-full max-w-lg rounded-xl shadow-xl p-6 animate-fade-in">
              {/* Header */}
              <div className="flex items-center justify-between mb-4">
                <h4 className="text-lg font-semibold">Your Rating</h4>
                <button
                  onClick={handlerClose}
                  className="text-sm text-gray-500 hover:text-red-500"
                >
                  Clear
                </button>
              </div>

              {/* Star Rating */}
              <div className="flex gap-2 mb-6">
                {[1, 2, 3, 4, 5].map((index) => (
                  <Star
                    key={index}
                    size={32}
                    className={
                      (hoverRating || rating) >= index
                        ? "text-yellow-400 fill-yellow-400 cursor-pointer"
                        : "text-gray-400 cursor-pointer"
                    }
                    onClick={() => setRating(index)}
                    onMouseEnter={() => setHoverRating(index)}
                    onMouseLeave={() => setHoverRating(0)}
                  />
                ))}
              </div>

              {/* Review Textarea */}
              <div className="mb-6">
                <h4 className="text-lg font-semibold mb-2">Your Review</h4>
                <textarea
                  value={review}
                  onChange={(e) => setReview(e.target.value)}
                  className="w-full h-32 border border-gray-300 rounded-lg p-3 resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Write your review..."
                ></textarea>
              </div>

              {/* Actions */}
              <div className="flex items-center justify-between">
                {/* Anonymous checkbox */}
                <label className="flex items-center gap-2 text-sm">
                  <input
                    type="checkbox"
                    checked={anonymous}
                    onChange={(e) => setAnonymous(e.target.checked)}
                    className="w-4 h-4"
                  />
                  Review anonymously
                </label>

                {/* Buttons */}
                <div className="flex gap-3">
                  <Button variant="outline" rounded="lg" onClick={handlerClose}>
                    Close
                  </Button>
                  <Button
                    rounded="lg"
                    onClick={handlerOnSubmit}
                    loading={isLoading}
                  >
                    Submit
                  </Button>
                </div>
              </div>
            </div>
          </div>
        </>
      )}
    </div>
  );
};

export default ReviewModel;
