import type { IVerfiyOpt } from "../../../interface";

import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";

export const profileAPI = {
  /**
   * Update name and/or avatar.
   * Must be sent as multipart/form-data so the backend can handle the file.
   */
  updateProfile: (data: FormData) => {
    data.append("_method", "PATCH");

    return api.post(ENDPOINTS.CUSTOMER.PROFILE.UPDATE, data, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
  },

  /**
   * Deletes the Cloudinary avatar and clears avatar fields on the user.
   */
  removeAvatar: () => api.delete(ENDPOINTS.CUSTOMER.PROFILE.REMOVE_AVATAR),

  /**
   * Changes the authenticated user's email.
   * Requires the current password for verification.
   * Revokes all tokens and sends OTP to the new email.
   */
  changeEmail: (data: { new_email: string; password: string }) =>
    api.post(ENDPOINTS.CUSTOMER.PROFILE.CHANGE_EMAIL, data),
  /*
   * Verifies the new email with the OTP sent to it.
   * Revokes all tokens and returns a new access token.
   */
  verifyEmailChange: (data: IVerfiyOpt) =>
    api.post(ENDPOINTS.CUSTOMER.PROFILE.VERIFY_EMAIL, data),
  /**
   * Changes the authenticated user's password.
   * Revokes all existing tokens and returns a new access token.
   */
  changePassword: (data: {
    current_password: string;
    new_password: string;
    new_password_confirmation: string;
  }) => api.put(ENDPOINTS.CUSTOMER.PROFILE.CHANGE_PASSWORD, data),
};
