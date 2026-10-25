import { useState, memo } from "react";

interface IProps {
  src: string;
  alt?: string;
  className?: string;
  fallback?: string;
  placeholder?: string;
  onClick?: () => void;
}

const Image = ({
  src,
  alt,
  className,
  fallback,
  placeholder,
  onClick,
}: IProps) => {
  const [imgSrc, setImgSrc] = useState(placeholder || src);

  const handleLoad = () => {
    if (placeholder) setImgSrc(src);
  };

  const handleError = () => {
    if (fallback) setImgSrc(fallback);
  };

  return (
    <img
      src={imgSrc}
      alt={alt}
      loading="lazy"
      onLoad={handleLoad}
      onError={handleError}
      className={className}
      onClick={onClick}
    />
  );
};

export default Image;
