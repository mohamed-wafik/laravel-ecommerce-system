import type { IBrand } from "../../interface";
import { Link } from "react-router-dom";

interface IProps {
  brand: IBrand;
}

const AVATAR_STYLES = [
  "bg-primary-50 text-primary",
  "bg-secondary-50 text-secondary",
  "bg-accent-50 text-accent-dark",
  "bg-info-50 text-info-dark",
];

function initials(name: string) {
  return name
    .split(" ")
    .filter(Boolean)
    .slice(0, 2)
    .map((word) => word[0]?.toUpperCase())
    .join("");
}

function hashIndex(id: string, length: number) {
  let hash = 0;
  for (let i = 0; i < id.length; i++) {
    hash = (hash * 31 + id.charCodeAt(i)) % length;
  }
  return hash;
}
const BrandCard = ({ brand }: IProps) => {
  const style = AVATAR_STYLES[hashIndex(brand.id, AVATAR_STYLES.length)];

  return (
    <Link
      to={`/shop?brand_id=${brand.id}`}
      type="button"
      className={`group flex flex-col items-center gap-3 rounded-2xl border bg-bg-primary p-6 text-center transition-colors ${"border-border hover:border-primary-light"}`}
    >
      {brand.logo_url ? (
        // eslint-disable-next-line @next/next/no-img-element
        <img
          src={brand.logo_url}
          alt={brand.name}
          className="h-14 w-14 rounded-full object-contain"
        />
      ) : (
        <span
          className={`flex h-14 w-14 items-center justify-center rounded-full text-lg font-semibold ${style}`}
        >
          {initials(brand.name)}
        </span>
      )}
      <span className="truncate text-sm font-medium text-text-primary">
        {brand.name}
      </span>
    </Link>
  );
};

export default BrandCard;
