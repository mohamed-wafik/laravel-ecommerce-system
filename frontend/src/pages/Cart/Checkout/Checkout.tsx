import { useEffect, useState } from "react";

import { useCart } from "../../../store/useCart";
import { addressApi } from "../../../api/modules/Customer/address.api";
import type { IAddress } from "../../../interface";

import CartIsEmpty from "./CartIsEmpty";
import ModeSwitch from "./ModeSwitch";
import SavedAddressForm from "./SavedAddressForm";
import NewAddressForm from "./NewAddressForm";
import Model from "../../../components/ui/Model";

interface CheckoutProps {
  isOpen: boolean;
  setIsOpted: (isOpted: boolean) => void;
}

type Mode = "saved" | "new";

const Checkout = ({ isOpen, setIsOpted }: CheckoutProps) => {
  const { cartItems, countItems } = useCart();

  const {
    data: addressResponse,
    isLoading: isLoadingAddresses,
    error: addressError,
  } = addressApi.list();

  const addresses: IAddress[] = addressResponse?.data ?? [];

  const [mode, setMode] = useState<Mode>("new");
  const [isCheckingOut, setIsCheckingOut] = useState(false);

  // Sync mode once addresses finish loading — this belongs in an
  // effect, not in the render body, to avoid setting state mid-render.
  useEffect(() => {
    if (!isLoadingAddresses) {
      setMode(addresses.length === 0 ? "new" : "saved");
    }
  }, [isLoadingAddresses, addresses.length]);

  const isCartEmpty = !cartItems || cartItems.length === 0 || countItems === 0;

  const handleClose = () => {
    if (isCheckingOut) return;
    setIsOpted(false);
  };

  if (!isOpen) return null;

  return (
    <Model
      title="Order Details"
      subTitle="Review your order before payment"
      onClose={handleClose}
    >
      {isCartEmpty ? (
        <CartIsEmpty />
      ) : (
        <div className="mx-auto overflow-y-auto rounded-2xl bg-white p-6 sm:p-8">
          {addresses.length > 0 && (
            <ModeSwitch
              mode={mode}
              onChange={setMode}
              disabled={isCheckingOut}
            />
          )}

          {mode === "saved" ? (
            <SavedAddressForm
              addresses={addresses}
              isLoadingAddresses={isLoadingAddresses}
              addressError={addressError}
              isCartEmpty={isCartEmpty}
              onClose={handleClose}
            />
          ) : (
            <NewAddressForm isCartEmpty={isCartEmpty} onClose={handleClose} />
          )}
        </div>
      )}
    </Model>
  );
};

export default Checkout;
