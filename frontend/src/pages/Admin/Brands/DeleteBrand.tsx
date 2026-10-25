import { useState } from "react";
import toast from "react-hot-toast";

import Alert from "../../../components/ui/Alert";
import Button from "../../../components/ui/Button";
import { adminBrandAPI } from "../../../api/modules/Admin/brand.api";

interface IProps {
  id: string;
  name: string;
  closeAlert: () => void;
  onSuccess: () => Promise<void>;
}

const DeleteBrand = ({ id, name, closeAlert, onSuccess }: IProps) => {
  const [isLoading, setIsLoading] = useState(false);

  const handleDelete = async () => {
    setIsLoading(true);

    try {
      await adminBrandAPI.delete(id);

      toast.success("Brand deleted successfully");

      await onSuccess();

      closeAlert();
    } catch (error) {
      console.error(error);

      toast.error("Failed to delete brand");
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <Alert
      title="Delete Brand?"
      subTitle={`Are you sure you want to delete "${name}"? This action cannot be undone.`}
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

export default DeleteBrand;
