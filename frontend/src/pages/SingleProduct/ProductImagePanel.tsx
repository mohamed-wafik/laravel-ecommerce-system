import { useMemo, useState } from "react";
import type { IProductImage } from "../../interface";
import Image from "../../components/ui/Image";

interface ProductImagePanelProps {
  images: IProductImage[];
  title: string;
  comparePrice: string | null;
  price: string;
}

const ProductImagePanel = ({
  images,
  title,
  comparePrice,
  price,
}: ProductImagePanelProps) => {
  const orderedImages = useMemo(
    () => [...images].sort((a, b) => a.sort_order - b.sort_order),
    [images],
  );

  const [selectedImage, setSelectedImage] = useState(
    orderedImages[0]?.url ?? "",
  );

  const hasDiscount =
    comparePrice !== null && Number(comparePrice) > Number(price);

  const discountPercentage = hasDiscount
    ? Math.round(
        ((Number(comparePrice) - Number(price)) / Number(comparePrice)) * 100,
      )
    : 0;

  return (
    <div className="relative flex flex-col bg-gray-50 p-4 sm:p-6 lg:min-h-[620px] lg:p-10">
      {/* Discount */}
      {hasDiscount && (
        <div className="absolute right-4 top-4 z-10 rounded-full bg-primary px-3 py-1.5 text-xs font-bold text-white shadow-md sm:right-6 sm:top-6">
          -{discountPercentage}% OFF
        </div>
      )}

      {/* Main image */}
      <div className="flex min-h-80  flex-1 items-center justify-center rounded-2xl overflow-hidden">
        {selectedImage ? (
          <img
            src={selectedImage}
            alt={title}
            className="h-full w-full object-contain transition-transform duration-300 hover:scale-[1.03]"
          />
        ) : (
          <div className="flex h-80 items-center justify-center text-sm text-gray-400">
            No image available
          </div>
        )}
      </div>

      {/* Thumbnails */}
      {orderedImages.length > 1 && (
        <div className="mt-5 flex gap-2 overflow-x-auto pb-1 sm:mt-6 sm:justify-center">
          {orderedImages.map((image) => {
            const isSelected = selectedImage === image.url;

            return (
              <button
                key={image.id}
                type="button"
                onClick={() => setSelectedImage(image.url)}
                className={`h-16 w-16 shrink-0 overflow-hidden rounded-xl border-2 bg-white transition sm:h-20 sm:w-20 ${
                  isSelected
                    ? "border-primary"
                    : "border-transparent hover:border-gray-300"
                }`}
              >
                <Image
                  src={image.url}
                  alt={`${title} preview`}
                  className="h-full w-full object-cover"
                />
              </button>
            );
          })}
        </div>
      )}
    </div>
  );
};

export default ProductImagePanel;
