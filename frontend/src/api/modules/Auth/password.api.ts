import type { IVerfiyOpt } from "../../../interface";
import { authHandler } from "../../../Service/auth.service";
import cookieService from "../../../Service/cookie.service";
import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";
import type { IResetPassword } from "../../type";

export const passwordAPI = {
  sendOtp: (email: string) =>
    api.post(ENDPOINTS.AUTH.PASSWORD.SEND_OTP, { email }),

  verifyOtp: async (data: IVerfiyOpt) => {
    const res = await api.post(ENDPOINTS.AUTH.PASSWORD.VERIFY_OTP, data);
    const { reset_token } = res.data.data;
    authHandler.setToken(
      "reset_token",
      reset_token,
      new Date(Date.now() + 15 * 60 * 60 * 1000),
    );
    return res;
  },

  resetPassword: (data: IResetPassword) => {
    const token = cookieService.get("reset_token");
    return api.post(ENDPOINTS.AUTH.PASSWORD.RESET, data, {
      headers: {
        Authorization: token ? `Bearer ${token}` : "",
      },
    });
  },
};
