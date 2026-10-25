import Alert from "../../../components/ui/Alert";
import Button from "../../../components/ui/Button";

import toast from "react-hot-toast";
import { adminCategoryAPI } from "../../../api/modules/Admin/category.api";
import { useState } from "react";

interface IProps {
  id: string;
  name: string;
  closeAlert: () => void;
  onSuccess: () => Promise<void>;
}
const DeleteCategory = ({ id, closeAlert, onSuccess, name }: IProps) => {
  const [isLoading, setIsLoading] = useState<boolean>(false);
  const handleDelete = async () => {
    setIsLoading(true);
    try {
      await adminCategoryAPI.delete(id);

      toast.success("Product deleted successfully");
      onSuccess();
      closeAlert();
    } catch (error) {
      console.error(error);
      toast.error("Failed to delete product");
    } finally {
      setIsLoading(false);
    }
  };
  return (
    <Alert
      title="Delete Category?"
      subTitle={`Are you sure you want to delete "${name}"? This action cannot be undone.`}
      onClose={closeAlert}
    >
      <Button type="button" variant="outline" onClick={closeAlert}>
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

export default DeleteCategory;
