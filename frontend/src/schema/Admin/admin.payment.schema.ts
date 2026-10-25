import type { IPagination, IUser } from "../../interface";
import type { IOrder } from "../../interface/order";

export type PaymentStatus =
  | "pending"
  | "succeeded"
  | "failed"
  | "cancelled"
  | "refunded"
  | string;

export interface IPayment {
  id: string;
  order_id: string;
  user_id: string;
  provider: string;
  checkout_session_id: string | null;
  payment_intent_id: string | null;
  idempotency_key: string;
  amount: string | number;
  currency: string;
  status: PaymentStatus;
  payment_method: string | null;
  paid_at: string | null;
  created_at: string;

  order?: IOrder | null;
  user?: IUser | null;
}

export interface IPaymentResponse {
  items: IPayment[];
  pagination: IPagination;
}
