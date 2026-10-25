interface ProductPriceProps {
  price: number;
  comparePrice: number | null;
  discount: number | null;
}

const ProductPrice = ({ price, comparePrice, discount }: ProductPriceProps) => {
  const hasDiscount = comparePrice !== null && comparePrice > price;

  return (
    <div className="mb-6 flex flex-wrap items-end gap-3">
      <span className="text-3xl font-black tracking-tight text-gray-900 sm:text-4xl">
        ${price.toFixed(2)}
      </span>

      {hasDiscount && comparePrice && (
        <div className="pb-1">
          <span className="block text-sm leading-none text-gray-400 line-through">
            ${comparePrice.toFixed(2)}
          </span>

          {discount !== null && (
            <span className="mt-1 block text-xs font-bold text-green-600">
              Save {discount}%
            </span>
          )}
        </div>
      )}
    </div>
  );
};

export default ProductPrice;
