import axios, {
  type AxiosError,
  type AxiosInstance,
  type InternalAxiosRequestConfig,
} from "axios";
import {
  clearTokens,
  getAccessToken,
  getRefreshToken,
  setTokens,
} from "../Service/authTokens";

const API_URL = "http://localhost:8000/api/v1";

interface IRefreshResponse {
  success: boolean;
  message: string;
  data: {
    access_token: string;
    refresh_token: string;
    token_type: string;
    expires_in: number;
  };
}

interface IRetryConfig extends InternalAxiosRequestConfig {
  _retry?: boolean;
}

interface IFailedRequest {
  resolve: (token: string) => void;
  reject: (error: unknown) => void;
}

const api: AxiosInstance = axios.create({
  baseURL: API_URL,
  headers: {
    Accept: "application/json",
    "Content-Type": "application/json",
  },
});

/*
|--------------------------------------------------------------------------
| Refresh state
|--------------------------------------------------------------------------
*/

let isRefreshing = false;

let failedRequests: IFailedRequest[] = [];

const resolveFailedRequests = (error: unknown, token?: string) => {
  failedRequests.forEach(({ resolve, reject }) => {
    if (error) {
      reject(error);
    } else if (token) {
      resolve(token);
    }
  });

  failedRequests = [];
};

/*
|--------------------------------------------------------------------------
| Request interceptor
|--------------------------------------------------------------------------
*/

api.interceptors.request.use((config) => {
  const token = getAccessToken();

  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
});

/*
|--------------------------------------------------------------------------
| Response interceptor
|--------------------------------------------------------------------------
*/

api.interceptors.response.use(
  (response) => response,

  async (error: AxiosError) => {
    const originalRequest = error.config as IRetryConfig | undefined;

    /*
     * Only handle unauthorized responses.
     */
    if (error.response?.status !== 401 || !originalRequest) {
      return Promise.reject(error);
    }

    /*
     * Never refresh the refresh endpoint itself.
     */
    if (originalRequest.url?.includes("/auth/refresh")) {
      clearTokens();

      return Promise.reject(error);
    }

    /*
     * Prevent infinite retry loops.
     */
    if (originalRequest._retry) {
      clearTokens();

      return Promise.reject(error);
    }

    originalRequest._retry = true;

    const refreshToken = getRefreshToken();

    /*
     * No refresh token → user must login.
     */
    if (!refreshToken) {
      clearTokens();

      return Promise.reject(error);
    }

    /*
     * Another request is already refreshing.
     * Put this request in the queue.
     */
    if (isRefreshing) {
      return new Promise<string>((resolve, reject) => {
        failedRequests.push({
          resolve,
          reject,
        });
      }).then((newAccessToken) => {
        originalRequest.headers.Authorization = `Bearer ${newAccessToken}`;

        return api(originalRequest);
      });
    }

    /*
     * Start refresh.
     */
    isRefreshing = true;

    try {
      const response = await axios.post<IRefreshResponse>(
        `${API_URL}/auth/refresh`,
        {},
        {
          headers: {
            Accept: "application/json",
            Authorization: `Bearer ${refreshToken}`,
          },
        },
      );

      const { access_token, refresh_token } = response.data.data;

      /*
       * Save rotated tokens.
       */
      setTokens(access_token, refresh_token);

      /*
       * Release queued requests.
       */
      resolveFailedRequests(null, access_token);

      /*
       * Retry original request.
       */
      originalRequest.headers.Authorization = `Bearer ${access_token}`;

      return api(originalRequest);
    } catch (refreshError) {
      /*
       * Reject all queued requests.
       */
      resolveFailedRequests(refreshError);

      /*
       * Refresh failed → logout.
       */
      clearTokens();

      return Promise.reject(refreshError);
    } finally {
      isRefreshing = false;
    }
  },
);

export default api;
