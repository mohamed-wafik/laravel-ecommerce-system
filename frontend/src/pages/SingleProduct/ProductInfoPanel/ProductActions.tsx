import { Bolt, Heart, ShoppingCart } from "lucide-react";
import Button from "../../../components/ui/Button";
import useWishlist from "../../../store/useWishlist";
import type { IProduct } from "../../../interface";

interface ProductActionsProps {
  product: IProduct;
  quantity: number;
  isLoadingCart: boolean;
  onAddToCart: () => void;
  disabled: boolean;
}

const ProductActions = ({
  product,
  isLoadingCart,
  onAddToCart,
  disabled,
}: ProductActionsProps) => {
  const { toggleWishlist, checkIfInWishlist } = useWishlist();

  const isWishlisted = checkIfInWishlist(product.id);

  return (
    <div className="mb-8 grid grid-cols-[1] gap-3 sm:flex sm:gap-4">
      <Button
        width="full"
        size="lg"
        loading={isLoadingCart}
        disabled={disabled}
        onClick={onAddToCart}
        className="rounded-xl font-bold sm:flex-1"
      >
        <ShoppingCart className="mr-2 h-4 w-4" />
        <span>Add to Cart</span>
      </Button>

      <Button
        size="lg"
        variant="outline"
        onClick={() => toggleWishlist(product)}
        className={`rounded-xl border-gray-200 px-4 transition-colors ${
          isWishlisted
            ? "border-primary bg-primary/5 text-primary"
            : "hover:border-primary hover:text-primary"
        }`}
        aria-label={isWishlisted ? "Remove from wishlist" : "Add to wishlist"}
      >
        <Heart className={`h-4 w-4 ${isWishlisted ? "fill-primary" : ""}`} />
      </Button>
    </div>
  );
};

export default ProductActions;
