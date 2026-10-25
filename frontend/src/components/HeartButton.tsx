import { Heart } from "lucide-react";
import type { IProduct } from "../interface";
import useWishlist from "../store/useWishlist";

interface IProps {
  product: IProduct;
}

const HeartButton = ({ product }: IProps) => {
  const { toggleWishlist, checkIfInWishlist } = useWishlist();
  const isWishlisted = checkIfInWishlist(product.id);

  return (
    <button
      type="button"
      onClick={() => toggleWishlist(product)}
      aria-label={
        isWishlisted
          ? `Remove ${product.name} from wishlist`
          : `Add ${product.name} to wishlist`
      }
      className={`absolute right-5 top-5 z-20 flex h-9 w-9 items-center justify-center rounded-full border backdrop-blur-md transition-all duration-200 sm:h-10 sm:w-10 ${
        isWishlisted
          ? "border-red-500 bg-red-500 text-white shadow-md"
          : "border-gray-200 bg-white/90 text-gray-500 hover:border-red-200 hover:text-red-500"
      }`}
    >
      <Heart
        className={`h-4 w-4 sm:h-5 sm:w-5 ${
          isWishlisted ? "fill-current" : ""
        }`}
      />
    </button>
  );
};

export default HeartButton;
