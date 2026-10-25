import { create } from "zustand";
import type { IForgetPassword } from "../interface";
import { passwordAPI } from "../api/modules/Auth/password.api";
// import { AuthService } from "../Service/auth.service";

interface IResetPassState {
  email: string;
  isForgetPass: boolean;
  isVerifyOtp: boolean;
  isChangePass: boolean;

  sendOtp: (data: IForgetPassword) => Promise<void>;
  verifyOtp: (data: { otp: string }) => Promise<void>;
  resetPassword: (data: { password: string }) => Promise<void>;
  reset: () => void;
}

const initialState = {
  email: "",
  isChangePass: false,
  isForgetPass: false,
  isVerifyOtp: false,
};

export const useResetPassStore = create<IResetPassState>((set, get) => ({
  ...initialState,

  sendOtp: async (data: IForgetPassword) => {
    set({ isForgetPass: true });
    try {
      const res = await passwordAPI.sendOtp(data.email);
      const { success } = res.data.data;
      if (success === true) {
        set({ email: data.email });
      }
    } catch (error) {
      // errors handled by caller / toast layer
    } finally {
      set({ isForgetPass: false });
    }
  },

  verifyOtp: async (data: { otp: string }) => {
    set({ isVerifyOtp: true });
    try {
      const res = await passwordAPI.verifyOtp({
        ...data,
        email: get().email,
      });
      const { success } = res.data.data;
      if (success === true) {
      }
    } catch (error) {
      // errors handled by caller / toast layer
    } finally {
      set({ isVerifyOtp: false });
    }
  },

  resetPassword: async (data: { password: string }) => {
    set({ isChangePass: true });
    try {
      const res = await passwordAPI.resetPassword({
        password: data.password,
        password_confirmation: data.password,
        email: get().email,
      });
      const { success } = res.data.data;
      if (success === true) {
        get().reset();
      }
    } catch (error) {
      // errors handled by caller / toast layer
    } finally {
      set({ isChangePass: false });
    }
  },

  reset: () => set(initialState),
}));
