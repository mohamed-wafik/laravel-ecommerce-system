import { authAPI } from "../api/modules/Auth/auth.api";
import type { IVerfiyOpt } from "../interface";
import cookieService from "./cookie.service";

export const authHandler = {
  login: async (data: { email: string; password: string }) => {
    const res = await authAPI.login(data);

    return { ...res.data.data, email: data.email };
  },

  register: async (data: {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
  }) => {
    const res = await authAPI.register(data);

    return {
      ...res.data,
      email: data.email,
    };
  },

  verifyOtp: async (data: IVerfiyOpt) => {
    const res = await authAPI.verify(data);

    return res.data;
  },

  me: async () => {
    const res = await authAPI.me();
    console.log(res);
    return res.data.data;
  },

  logout: async () => {
    return authAPI.logout();
  },

  setToken: (name: string, token: string, time: Date) => {
    cookieService.set(name, token, {
      expires: time,
      secure: true,
      sameSite: "strict",
      path: "/",
    });
  },
};
