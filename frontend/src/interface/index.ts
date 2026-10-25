export interface IUser {
  id: string;
  name: string;
  email: string;
  avatar_url: string | null;
  role: string;
  phone?: string;
  is_active: boolean;
  created_at: string;
  updated_at: string;
}
export interface IAddress {
  id: string;
  user_id: string;
  label: string;
  full_name: string;
  phone: string;
  country: string;
  city: string;
  street: string;
  postal_code: string;
  is_default: boolean;
  created_at: string;
  updated_at: string;
}
export interface IPagination {
  current_page: number;
  per_page: number;
  total: number;
  last_page: number;
  from: number;
  to: number;
}

export interface IVerfiyOpt {
  code: string;
  email: string;
  type?: string;
}
export interface IAddCartItemPayload {
  product_id: string;
  quantity?: number;
  variant_id?: string;
}

export interface ShippingFormState {
  customer_name: string;
  customer_email: string;
  customer_phone: string;
  shipping_address: string;
  city: string;
  postal_code?: string;
  shipping_method: string;
  payment_method: string;
}
export interface IAddressShipping {
  id: string;
  user_id: string;
  label: string;
  full_name: string;
  phone: string;
  country: string;
  city: string;
  street: string;
  postal_code: string | null;
  is_default: boolean;
}
export interface IVariant {
  id: string;
  product_id: string;

  color: string;
  size: string;

  sku: string;

  price_override: string | number | null;
  stock_quantity: number;
}
export interface IBrand {
  id: string;
  name: string;
  slug: string;

  logo_url: string | null;
  logo_public_id: string | null;

  is_active: boolean;
}

export interface IProduct {
  id: string;
  category_id: string;
  brand_id: string;

  name: string;
  slug: string;
  description: string;

  price: string | number;
  compare_price: string | number | null;

  sku: string;
  stock_quantity: number;

  is_active: boolean;

  created_at: string;
  updated_at: string;
  deleted_at: string | null;

  approved_reviews_count: number;
  average_rating: number | null;

  images: IProductImage[];

  variants: IVariant[];

  category?: ICategory;
  brand?: IBrand;

  reviews?: IReview[];
}
export interface IProductImage {
  id: string;
  product_id: string;
  url: string;
  public_id: string;
  is_primary: boolean;
  sort_order: number;
}
export interface ICategory {
  id: string;
  parent_id: string | null;
  name: string;
  slug: string;
  image_url: string | null;
  image_public_id: string | null;
  is_active: boolean;
  sort_order: number;
  children: ICategory[];
}

export interface ICartItem {
  id: number;
  user_id: number;
  product_id: number;
  quantity: number;
  created_at: string;
  price_snapshot: number;
  updated_at: string;
  product: IProduct;
}

export interface ICartResponse {
  message: string;
  data: ICartItem[];
  status: number;
}

export interface IRegisterInput {
  name: "email" | "name" | "password";
  placeholder: string;
  type: string;
  validation: {
    required?: boolean;
    minLength?: number;
    pattern?: RegExp;
  };
}

export interface ILoginInput {
  name: "email" | "password";
  placeholder: string;
  type: string;
  validation: {
    required?: boolean;
    minLength?: number;
    pattern?: RegExp;
  };
}
export interface IReview {
  id: string;
  product_id: string;
  user_id: string;
  order_item_id: string | null;
  rating: number;
  comment: string;
  is_approved: boolean;
  created_at: string;
  product: IProduct;
  user: IUser;
}

interface IOrderItem {
  id: number;
  order_id: number;
  product_id: number;
  quantity: number;
  price: string;
  total: string;
  created_at: string;
  updated_at: string;
  product: IProduct;
}
export interface IResponseOrderCreate {
  id: number;
  user_id: number;
  customer_name: string;
  customer_email: string;
  customer_phone: string;
  shipping_address: string;
  city: string;
  postal_code: string;
  shipping_method: string;
  shipping_cost: string;
  subtotal: string;
  discount: string;
  tax: string;
  total: string;
  payment_method: string;
  payment_status: string;
  order_status: string;
  order_number: string;
  created_at: string;
  updated_at: string;
}
export interface IOrder extends IResponseOrderCreate {
  payment_session_id: string | null;
  payment_intent_id: string | null;
  payment_id: string | null;
  transaction_id: string | null;
  paid_at?: string | null;
  items: IOrderItem[];
}

export interface IForgetPassword {
  email: string;
}
export interface IVerifyOtp {
  email: string;
  otp: string;
}

export interface IChangePassword {
  email: string;
  password: string;
}
