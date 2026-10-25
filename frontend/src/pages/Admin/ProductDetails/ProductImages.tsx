import { ImageIcon } from "lucide-react";
import type { IProductImage } from "../../../interface";

interface ProductImagesProps {
  images: IProductImage[];
  name: string;
}

const ProductImages = ({ images, name }: ProductImagesProps) => {
  const primaryImage =
    images.find((image) => image.is_primary)?.url ?? images[0]?.url;

  return (
    <div>
      <div className="overflow-hidden rounded-2xl border border-border bg-bg-secondary">
        {primaryImage ? (
          <img
            src={primaryImage}
            alt={name}
            className="aspect-square w-full object-cover"
          />
        ) : (
          <div className="flex aspect-square items-center justify-center">
            <ImageIcon size={48} className="text-text-tertiary" />
          </div>
        )}
      </div>

      {images.length > 1 && (
        <div className="mt-3 grid grid-cols-4 gap-2">
          {images.map((image) => (
            <div
              key={image.id}
              className={`overflow-hidden rounded-xl border bg-bg-secondary ${
                image.is_primary ? "border-primary" : "border-border"
              }`}
            >
              <img
                src={image.url}
                alt={name}
                className="aspect-square w-full object-cover"
              />
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default ProductImages;
