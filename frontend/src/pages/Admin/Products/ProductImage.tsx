import { ImageIcon } from "lucide-react";

interface IProductImageProps {
  src: string | null;
  alt: string;
  size?: "default" | "large";
}

const ProductImage = ({ src, alt, size = "default" }: IProductImageProps) => {
  const sizeClass = size === "large" ? "h-20 w-20" : "h-12 w-12";

  if (!src) {
    return (
      <div
        className={`flex ${sizeClass} shrink-0 items-center justify-center rounded-xl bg-bg-tertiary`}
      >
        <ImageIcon className="h-5 w-5 text-text-tertiary" />
      </div>
    );
  }

  return (
    <img
      src={src}
      alt={alt}
      className={`${sizeClass} shrink-0 rounded-xl border border-border object-cover`}
    />
  );
};
export default ProductImage;
