interface ProductBadgesProps {
  categoryName?: string;
  brandName?: string;
  stock: number;
}

const ProductBadges = ({
  categoryName,
  brandName,
  stock,
}: ProductBadgesProps) => {
  return (
    <div className="flex flex-wrap items-center gap-2">
      {categoryName && (
        <span className="rounded-full border border-primary/20 bg-primary/5 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-primary sm:text-xs">
          {categoryName}
        </span>
      )}

      {brandName && (
        <span className="rounded-full border border-gray-200 bg-white px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-gray-600 sm:text-xs">
          {brandName}
        </span>
      )}

      <span
        className={`rounded-full border px-3 py-1 text-[10px] font-bold uppercase tracking-widest sm:text-xs ${
          stock > 0
            ? "border-green-200 bg-green-50 text-green-700"
            : "border-red-200 bg-red-50 text-red-600"
        }`}
      >
        {stock > 0 ? `${stock} in stock` : "Out of stock"}
      </span>
    </div>
  );
};

export default ProductBadges;
