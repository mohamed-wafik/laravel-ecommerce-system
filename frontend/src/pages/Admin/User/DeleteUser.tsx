import { useState } from "react";
import toast from "react-hot-toast";

import Alert from "../../../components/ui/Alert";
import Button from "../../../components/ui/Button";

import { adminUserAPI } from "../../../api/modules/Admin/user.api";

interface IProps {
  id: string;
  name: string;
  closeAlert: () => void;
  onSuccess: () => Promise<void>;
}

const DeleteUser = ({ id, name, closeAlert, onSuccess }: IProps) => {
  const [isLoading, setIsLoading] = useState(false);

  const handleDelete = async () => {
    setIsLoading(true);

    try {
      await adminUserAPI.delete(id);

      toast.success("User deleted successfully");

      await onSuccess();

      closeAlert();
    } catch (error) {
      console.error(error);

      toast.error("Failed to delete user");
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <Alert
      title="Delete User?"
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

export default DeleteUser;
