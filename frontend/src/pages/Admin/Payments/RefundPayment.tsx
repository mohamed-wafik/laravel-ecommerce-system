import Alert from "../../../components/ui/Alert";
import Button from "../../../components/ui/Button";

import toast from "react-hot-toast";
import { useState } from "react";
import { adminOrderAPI } from "../../../api/modules/Admin/order.api";
import type { IOrder } from "../../../interface/order";

interface IProps {
  order: IOrder;
  closeAlert: () => void;
  onSuccess: () => Promise<void>;
}

const RefundPayment = ({ order, closeAlert, onSuccess }: IProps) => {
  const [isLoading, setIsLoading] = useState(false);

  const handleRefund = async () => {
    if (!order.payment || order.payment.status !== "succeeded") {
      toast.error("Only successful payments can be refunded.");
      return;
    }

    try {
      setIsLoading(true);

      await adminOrderAPI.refund(order.id);

      toast.success("Payment refunded successfully");

      await onSuccess();
      closeAlert();
    } catch (error) {
      console.error("Failed to refund payment:", error);

      toast.error("Failed to refund payment");
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <Alert
      title="Refund Payment?"
      subTitle={`Are you sure you want to refund payment "${order.id}"? This action cannot be undone.`}
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
        onClick={handleRefund}
        loading={isLoading}
        disabled={isLoading}
        className="bg-error text-white hover:bg-error-dark"
      >
        Refund
      </Button>
    </Alert>
  );
};

export default RefundPayment;
