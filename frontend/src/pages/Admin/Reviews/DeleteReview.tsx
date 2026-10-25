import { useState } from "react";
import toast from "react-hot-toast";

import Alert from "../../../components/ui/Alert";
import Button from "../../../components/ui/Button";
import { adminReviewAPI } from "../../../api/modules/Admin/review.api";

interface IProps {
  id: string;
  customerName: string;
  closeAlert: () => void;
  onSuccess: () => Promise<void>;
}

const DeleteReview = ({ id, customerName, closeAlert, onSuccess }: IProps) => {
  const [isLoading, setIsLoading] = useState(false);

  const handleDelete = async () => {
    setIsLoading(true);

    try {
      await adminReviewAPI.delete(id);

      toast.success("Review deleted successfully");

      await onSuccess();

      closeAlert();
    } catch (error) {
      console.error(error);

      toast.error("Failed to delete review");
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <Alert
      title="Delete Review?"
      subTitle={`Are you sure you want to delete the review by "${customerName}"? This action cannot be undone.`}
      onClose={closeAlert}
    >
      <Button
        type="button"
        variant="outline"
        onClick={closeAlert}
        disabled={isLoading}
      >
        Cancel
      </Button>

      <Button
        type="button"
        onClick={handleDelete}
        loading={isLoading}
        className="bg-error text-white hover:bg-error-dark"
      >
        Delete
      </Button>
    </Alert>
  );
};

export default DeleteReview;
