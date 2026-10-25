import { useState } from "react";
import { useCart } from "../store/useCart";
import { ShoppingCart } from "lucide-react";
import toast from "react-hot-toast";
import { useAuthStore } from "../store/useAuth";
interface IProps {
  id: string;
  disabled: boolean;
  variantId?: string;
}
const AddProductToCart = ({ id, disabled, variantId }: IProps) => {
  const { addItem } = useCart();
  const { user } = useAuthStore();
  const [isLoading, setIsloading] = useState(false);
  const handlerAdd = async () => {
    if (!user) {
      toast.error("You need to be logged in to add items to the cart");
      return;
    }
    setIsloading(true);
    await addItem({
      product_id: id,
      quantity: 1,
      variant_id: variantId,
    });
    setIsloading(false);
  };
  return (
    <button
      onClick={handlerAdd}
      disabled={isLoading || disabled}
      className={`relative p-3 rounded-xl font-semibold transition-all duration-300 cursor-pointer ${
        isLoading
          ? "bg-green-500 text-white scale-90"
          : "bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:shadow-lg hover:scale-105"
      }`}
    >
      {isLoading ? (
        <div className="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
      ) : (
        <ShoppingCart className="w-5 h-5" />
      )}
    </button>
  );
};

export default AddProductToCart;
