export interface IRegister {
  name: string;
  email: string;
  password: string;
}
export interface ILogin {
  email: string;
  password: string;
}
export interface IVerify {
  email: string;
  otp: string;
}
export interface IResend {
  email: string;
}
export interface IResetPassword {
  password: string;
  password_confirmation: string;
}
export interface ICreateOrder {
  customer_name: string;
  customer_email: string;
  customer_phone: string;
  shipping_address: string;
  city: string;
  postal_code?: string;
  shipping_method: "standard" | "express" | "pickup";
  coupon_code?: string;
}

export interface IProductFilters {
  category?: number;
  only_discounted?: boolean;
  min_price?: number;
  max_price?: number;
  search?: string;
  sort_by?: "latest" | "price_low" | "price_high" | "popular";
  per_page?: number;
}
export interface IReviewCreate {
  rating: number;
  comment?: string;
}
export interface IReviewFilters {
  sort_by?: "latest" | "oldest" | "highest_rating" | "lowest_rating";
  per_page?: number;
  rating?: number;
}
export interface Product {
  id: number;
  title: string;
  price: number;
  discount: number;
  thumbnail: string;
  stock: number;
  rating_avg?: number;
}

export interface CartItem {
  id: number;
  product_id: number;
  quantity: number;
  product: Product;
}

export interface Order {
  id: number;
  total: number;
  status: string;
}

export interface Review {
  id: number;
  rating: number;
  comment: string;
}
