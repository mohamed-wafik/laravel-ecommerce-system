import Alert from "../../../components/ui/Alert";
import Button from "../../../components/ui/Button";
import { adminProductAPI } from "../../../api/modules/Admin/product.api";
import toast from "react-hot-toast";
import { useState } from "react";

interface IProps {
  id: string;
  name: string;
  closeAlert: () => void;
  onSuccess: () => Promise<void>;
}
const DeleteProduct = ({ id, closeAlert, onSuccess, name }: IProps) => {
  const [isLoading, setIsLoading] = useState<boolean>(false);
  const handleDelete = async () => {
    setIsLoading(true);
    try {
      await adminProductAPI.delete(id);

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
      title="Delete product?"
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

export default DeleteProduct;
