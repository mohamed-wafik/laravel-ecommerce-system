import type { IVerfiyOpt } from "../../../interface";
import { getRefreshToken } from "../../../Service/authTokens";
import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";
import type { ILogin, IRegister, IResend } from "../../type";
// import api from "../../interceptors";

export const authAPI = {
  login: (data: ILogin) => api.post(ENDPOINTS.AUTH.LOGIN, data),

  register: (data: IRegister) => api.post(ENDPOINTS.AUTH.REGISTER, data),

  verify: (data: IVerfiyOpt) =>
    api.post(ENDPOINTS.AUTH.OTP.VERIFY(data.type as string), {
      code: data.code,
      email: data.email,
    }),

  resend: (data: IResend) => api.post(ENDPOINTS.AUTH.OTP.RESEND, data),

  refresh: () => api.post(ENDPOINTS.AUTH.REFRESH, {}, getRefreshToken()),

  me: () => api.get(ENDPOINTS.AUTH.ME),

  logout: () => api.post(ENDPOINTS.AUTH.LOGOUT),
};
