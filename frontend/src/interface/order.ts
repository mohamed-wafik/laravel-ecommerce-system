import type { IAddress, IPagination, IProduct, IUser } from ".";

export type OrderStatus =
  | "pending"
  | "processing"
  | "paid"
  | "shipped"
  | "delivered"
  | "cancelled";

export type PaymentStatus =
  | "pending"
  | "succeeded"
  | "failed"
  | "cancelled"
  | string;

export interface IOrderItem {
  id: string;
  order_id: string;
  product_id: string;
  variant_id: string | null;
  product_name_snapshot: string;
  price_snapshot: string | number;
  quantity: number;
  subtotal: string | number;
  product?: IProduct | null;
}

export interface IOrderPayment {
  id: string;
  order_id: string;
  user_id: string;
  provider: string;
  checkout_session_id?: string | null;
  payment_intent_id?: string | null;
  idempotency_key?: string | null;
  amount: string | number;
  currency: string;
  status: PaymentStatus;
  payment_method?: string | null;
  paid_at?: string | null;
  created_at: string;
}

export interface IOrder {
  id: string;
  user_id: string;
  order_number: string;
  status: OrderStatus;

  subtotal: string | number;
  tax: string | number;
  shipping_fee: string | number;
  total: string | number;
  currency: string;

  shipping_address_id: string | null;
  billing_address_id: string | null;
  idempotency_key: string;

  created_at: string;
  updated_at: string;

  user?: IUser | null;
  items: IOrderItem[];

  payment?: IOrderPayment | null;

  shipping_address?: IAddress | null;
  billing_address?: IAddress | null;
}

export interface IOrderResponse {
  items: IOrder[];
  pagination: IPagination;
}
