export interface IDashboardSummary {
  revenue: number;
  orders: number;
  products_sold: number;
  customers: number;
  average_order_value: number;
}

export interface ISalesByDay {
  date: string;
  revenue: number;
  orders: number;
}

export interface IOrdersByStatus {
  status: string;
  count: number;
}

export interface ITopProduct {
  id: string;
  name: string;
  sold: number;
  revenue: number;
}

export interface ISalesByCategory {
  id: string;
  name: string;
  revenue: number;
}

export interface ICustomerAnalytics {
  new: number;
  returning: number;
}

export interface IInventoryAnalytics {
  total: number;
  active: number;
  low_stock: number;
  out_of_stock: number;
}

export interface IAdminDashboard {
  summary: IDashboardSummary;
  sales: ISalesByDay[];
  orders_by_status: IOrdersByStatus[];
  top_products: ITopProduct[];
  sales_by_category: ISalesByCategory[];
  customers: ICustomerAnalytics;
  inventory: IInventoryAnalytics;
}
