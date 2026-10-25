import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";
import type { IProduct } from "../interface";
import { STRENGTH } from "../data/auth-content";

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export const formatCurrency = (value: number, currency = "USD") => {
  return new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: currency,
    maximumFractionDigits: 0,
  }).format(value);
};

export const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

export const statusClass = (status: string) => {
  switch (status) {
    case "pending":
      return "bg-yellow-500/10 text-yellow-600";

    case "processing":
      return "bg-blue-500/10 text-blue-600";

    case "paid":
      return "bg-green-500/10 text-green-600";

    case "shipped":
      return "bg-purple-500/10 text-purple-600";

    case "delivered":
      return "bg-emerald-500/10 text-emerald-600";

    case "cancelled":
      return "bg-red-500/10 text-red-600";

    default:
      return "bg-gray-500/10 text-gray-600";
  }
};
export const getErrorMessage = (error: unknown) => {
  if (error && typeof error === "object" && "response" in error) {
    const response = (
      error as {
        response?: {
          data?: {
            message?: string;
            errors?: Record<string, string[]>;
          };
        };
      }
    ).response;

    if (response?.data?.message) {
      return response.data.message;
    }

    const errors = response?.data?.errors;

    if (errors) {
      return Object.values(errors).flat()[0] ?? "Validation failed.";
    }
  }

  return "Unable to send your message. Please try again.";
};
export const getDiscountedPrice = (product: IProduct) => {
  const price = Number(product.price);
  return product.compare_price
    ? price - Number(product.compare_price) / 100
    : price;
};
export const calcStrength = (pw: string) => {
  let s = 0;
  if (pw.length >= 8) s++;
  if (/[A-Z]/.test(pw)) s++;
  if (/[0-9]/.test(pw)) s++;
  if (/[^A-Za-z0-9]/.test(pw)) s++;
  return { score: s, ...STRENGTH[s] };
};
export const getProductImage = (product: IProduct) => {
  return (
    product.images?.find((image) => image.is_primary)?.url ??
    product.images?.[0]?.url ??
    null
  );
};

export const getStockStatus = (stock: number) => {
  if (stock <= 0) {
    return {
      label: "Out of stock",
      className: "bg-error-50 text-error",
    };
  }

  if (stock <= 5) {
    return {
      label: "Low stock",
      className: "bg-warning-50 text-warning",
    };
  }

  return {
    label: "In stock",
    className: "bg-success-50 text-success",
  };
};

export const formatPrice = (price: string | number) => {
  return Number(price).toFixed(2);
};
