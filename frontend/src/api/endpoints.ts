export const ENDPOINTS = {
  AUTH: {
    LOGIN: "/auth/login",
    REGISTER: "/auth/register",
    ME: "/auth/me",
    LOGOUT: "/auth/logout",
    REFRESH: "/auth/refresh",

    PASSWORD: {
      SEND_OTP: "/auth/forgot-password",
      VERIFY_OTP: "/auth/otp-check",
      RESET: "/auth/reset-password",
    },

    OTP: {
      VERIFY: (type: string) => `/auth/${type}/verify`,
      RESEND: "/auth/otp/resend",
    },
  },

  CUSTOMER: {
    PROFILE: {
      UPDATE: "/customer/profile",
      CHANGE_PASSWORD: "/customer/profile/change-password",
      CHANGE_EMAIL: "/customer/profile/change-email",
      VERIFY_EMAIL: "/customer/profile/verify-email-change",
      REMOVE_AVATAR: "/customer/profile/remove-avatar",
    },

    CONTACT_SUPPORT: {
      ALL: "/customer/support/tickets",
      CREATE: "/customer/support/tickets",
      SHOW: (id: string) => `/customer/support/tickets/${id}`,
    },

    ADDRESS: {
      ALL: "/customer/address",
      CREATE: "/customer/address",
      UPDATE: (id: string) => `/customer/address/${id}`,
      DELETE: (id: string) => `/customer/address/${id}`,
    },

    CART: {
      ALL: "/customer/cart",
      ADD: "/customer/cart/items",
      UPDATE: (id: string) => `/customer/cart/items/${id}`,
      DELETE: (id: string) => `/customer/cart/items/${id}`,
      CLEAR: "/customer/cart/clear",
    },

    CHECKOUT: {
      CREATE: "/customer/checkout",
    },

    REVIEWS: {
      CREATE: (productId: string) => `/customer/reviews/${productId}`,
    },

    ORDERS: {
      ALL: "/customer/orders",
      SHOW: (orderId: string) => `/customer/orders/${orderId}`,
    },
  },

  GUEST: {
    PRODUCTS: {
      ALL: "/guest/products",
      TOP: "/guest/products/top-deals",
      SHOW: (slug: string) => `/guest/products/${slug}`,
    },

    BRAND: {
      ALL: "/guest/brands",
    },

    CATEGORIES: {
      ALL: "/guest/categories",
      SHOW: (slug: string) => `/guest/categories/${slug}`,
    },
  },

  ADMIN: {
    DASHBOARD: {
      SUMMARY: "/admin/dashboard/summary",
      SALES_BY_DAY: "/admin/dashboard/sales-by-day",
    },

    USERS: {
      ALL: "/admin/users",
      CREATE: "/admin/users",
      SHOW: (id: string) => `/admin/users/${id}`,
      UPDATE: (id: string) => `/admin/users/${id}`,
      DELETE: (id: string) => `/admin/users/${id}`,

      TOGGLE_ACTIVE: (id: string) => `/admin/users/${id}/toggle-active`,

      ORDERS: (id: string) => `/admin/users/${id}/orders`,
    },

    CATEGORIES: {
      ALL: "/admin/categories",
      CREATE: "/admin/categories",
      SHOW: (id: string) => `/admin/categories/${id}`,
      UPDATE: (id: string) => `/admin/categories/${id}`,
      DELETE: (id: string) => `/admin/categories/${id}`,

      TREE: "/admin/categories-tree",

      REORDER: "/admin/categories/reorder",

      TOGGLE_ACTIVE: (id: string) => `/admin/categories/${id}/toggle-active`,
    },

    BRANDS: {
      ALL: "/admin/brands",
      CREATE: "/admin/brands",
      SHOW: (id: string) => `/admin/brands/${id}`,
      UPDATE: (id: string) => `/admin/brands/${id}`,
      DELETE: (id: string) => `/admin/brands/${id}`,

      TOGGLE_ACTIVE: (id: string) => `/admin/brands/${id}/toggle-active`,
    },

    PRODUCTS: {
      ALL: "/admin/products",
      CREATE: "/admin/products",
      SHOW: (id: string) => `/admin/products/${id}`,
      UPDATE: (id: string) => `/admin/products/${id}`,
      DELETE: (id: string) => `/admin/products/${id}`,

      LOW_STOCK: "/admin/products-low-stock",

      TOGGLE_ACTIVE: (id: string) => `/admin/products/${id}/toggle-active`,

      ADJUST_STOCK: (id: string) => `/admin/products/${id}/adjust-stock`,
    },

    ORDERS: {
      ALL: "/admin/orders",
      SHOW: (id: string) => `/admin/orders/${id}`,
      STATS: "/admin/orders/stats",

      UPDATE_STATUS: (id: string) => `/admin/orders/${id}/status`,
      REFUND: (id: string) => `/admin/orders/${id}/refund`,
    },

    PAYMENTS: {
      ALL: "/admin/payments",
      SHOW: (id: string) => `/admin/payments/${id}`,

      REFUND: (id: string) => `/admin/payments/${id}/refund`,
    },

    REVIEWS: {
      ALL: "/admin/reviews",
      SHOW: (id: string) => `/admin/reviews/${id}`,

      APPROVE: (id: string) => `/admin/reviews/${id}/approve`,

      REJECT: (id: string) => `/admin/reviews/${id}/reject`,

      DELETE: (id: string) => `/admin/reviews/${id}`,
    },

    SETTINGS: {
      ALL: "/admin/settings",
      UPDATE: "/admin/settings",
    },

    MEDIA: {
      CREATE: "/admin/media",
      BULK_CREATE: "/admin/media/bulk",
      DOCUMENT: "/admin/media/document",
      DELETE: "/admin/media",
    },

    CONTACT_SUPPORT: {
      ALL: "/admin/support/tickets",

      STATS: "/admin/support/tickets/stats",

      SHOW: (id: string) => `/admin/support/tickets/${id}`,

      REPLY: (id: string) => `/admin/support/tickets/${id}/reply`,

      UPDATE_STATUS: (id: string) => `/admin/support/tickets/${id}/status`,
    },
  },
} as const;
