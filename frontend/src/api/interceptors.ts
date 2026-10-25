import cookieService from "../Service/cookie.service";
import { authHandler } from "../Service/auth.service";
import api from "./axios";
import { ENDPOINTS } from "./endpoints";

// Separate, bare instance for refresh — must NOT go through the interceptors below,
// or a failed refresh will recursively trigger itself.

let isRefreshing = false;
let refreshSubscribers: Array<(token: string) => void> = [];

function subscribeTokenRefresh(cb: (token: string) => void) {
  refreshSubscribers.push(cb);
}

function onRefreshed(token: string) {
  refreshSubscribers.forEach((cb) => cb(token));
  refreshSubscribers = [];
}

function onRefreshFailed() {
  refreshSubscribers = [];
  cookieService.remove("access_token");
  window.location.href = "/login";
}

api.interceptors.request.use((config) => {
  const token = cookieService.get("access_token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config;

    if (error.response?.status !== 401 || originalRequest._retry) {
      return Promise.reject(error);
    }

    if (isRefreshing) {
      // Queue this request until the in-flight refresh finishes
      return new Promise((resolve, reject) => {
        subscribeTokenRefresh((token) => {
          originalRequest.headers.Authorization = `Bearer ${token}`;
          resolve(api(originalRequest));
        });
        // optional: reject after a timeout if refresh never resolves
      });
    }

    originalRequest._retry = true;
    isRefreshing = true;

    try {
      const res = await api.post(ENDPOINTS.AUTH.REFRESH); // adjust endpoint
      const accessToken = res.data.data.access_token;
      const refreshToken = res.data.data.refresh_token;

      authHandler.setToken(
        "access_token",
        accessToken,
        new Date(Date.now() + 30 * 60 * 1000), // 30 minutes, fixed from *60*1000 typo
      );

      authHandler.setToken(
        "refresh_token",
        refreshToken,
        new Date(Date.now() + 7 * 24 * 60 * 60 * 1000), // 7 days
      );

      isRefreshing = false;
      onRefreshed(accessToken);

      originalRequest.headers.Authorization = `Bearer ${accessToken}`;
      return api(originalRequest);
    } catch (refreshError) {
      isRefreshing = false;
      onRefreshFailed();
      return Promise.reject(refreshError);
    }
  },
);

export default api;
