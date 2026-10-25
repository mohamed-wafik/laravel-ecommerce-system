import { create } from "zustand";
import { toast } from "react-hot-toast";
import { getErrorMessage } from "../lib/utils";
import type { IUser, IVerfiyOpt } from "../interface";
import cookieService from "../Service/cookie.service";
import { authHandler } from "../Service/auth.service";

interface IAuthState {
  user: IUser | null;
  userIsLoggedIn: boolean;

  isLoadingLogin: boolean;
  isLoadingRegister: boolean;
  isLoadingCheck: boolean;
  isLoadingLogout: boolean;

  login: (data: {
    email: string;
    password: string;
  }) => Promise<string | undefined>;

  register: (data: {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
  }) => Promise<string | undefined>;

  verifyOtpRegister: (data: IVerfiyOpt) => Promise<void>;

  check: () => Promise<void>;

  logout: () => Promise<void>;

  setToken: (access_toke: string, refresh_token: string) => void;
}

export const useAuthStore = create<IAuthState>((set, get) => ({
  user: null,
  userIsLoggedIn: false,

  isLoadingLogin: false,
  isLoadingRegister: false,
  isLoadingCheck: false,
  isLoadingLogout: false,

  login: async (data) => {
    set({
      isLoadingLogin: true,
    });
    try {
      const res = await authHandler.login(data);

      console.log(res);

      set({ userIsLoggedIn: res.verified });

      if (!res.verified) {
        return res.email;
      }

      const { user, access_token, refresh_token } = res;

      get().setToken(access_token, refresh_token);

      set({
        user,
      });

      toast.success("Login successful 🎉");
    } catch (error) {
      toast.error(getErrorMessage(error));
    } finally {
      set({ isLoadingLogin: false });
    }
  },

  register: async (data) => {
    set({ isLoadingRegister: true });

    try {
      const res = await authHandler.register(data);

      set({
        userIsLoggedIn: false,
      });

      toast.success("Registration successful 🎉");

      return res.email;
    } catch (error) {
      toast.error(getErrorMessage(error));
    } finally {
      set({ isLoadingRegister: false });
    }
  },

  verifyOtpRegister: async (data) => {
    set({ isLoadingRegister: true });

    try {
      const res = await authHandler.verifyOtp(data);

      const { access_token, refresh_token, user } = res.data;

      get().setToken(access_token, refresh_token);

      set({
        user,
        userIsLoggedIn: true,
      });

      toast.success("Email verified successfully 🎉");

      window.location.href = "/";
    } catch (error) {
      toast.error(getErrorMessage(error));
    } finally {
      set({ isLoadingRegister: false });
    }
  },

  check: async () => {
    set({ isLoadingCheck: true });

    try {
      const user = await authHandler.me();

      set({
        user,
        userIsLoggedIn: true,
      });
    } catch {
      set({
        user: null,
        userIsLoggedIn: false,
      });
    } finally {
      set({ isLoadingCheck: false });
    }
  },

  logout: async () => {
    set({ isLoadingLogout: true });

    try {
      await authHandler.logout();

      cookieService.remove("access_token");
      cookieService.remove("refresh_token");

      set({
        user: null,
        userIsLoggedIn: false,
      });

      toast.success("Logged out successfully 👋");

      window.location.href = "/login";
    } catch (error) {
      toast.error(getErrorMessage(error));
    } finally {
      set({ isLoadingLogout: false });
    }
  },
  setToken(accessToken, refreshToken) {
    authHandler.setToken(
      "access_token",
      accessToken,
      new Date(Date.now() + 30 * 60 * 60 * 1000),
    );
    authHandler.setToken(
      "refresh_token",
      refreshToken,
      new Date(Date.now() + 30 * 24 * 60 * 60 * 1000),
    );
  },
}));
