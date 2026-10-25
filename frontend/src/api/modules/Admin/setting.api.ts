import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";

export const adminSettingAPI = {
  update: (data: Record<string, unknown>) =>
    api.patch(ENDPOINTS.ADMIN.SETTINGS.UPDATE, data),
};
