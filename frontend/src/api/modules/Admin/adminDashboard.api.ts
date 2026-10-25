import useFetch from "../../../hook/useAuthentienticatedQuery";
import { ENDPOINTS } from "../../endpoints";

export const adminDashboardAPI = {
  dashboard: (days: number = 30) =>
    useFetch({
      queryKey: ["admin-dashboard", `${days}`],
      url: `${ENDPOINTS.ADMIN.DASHBOARD.SUMMARY}?days=${days}`,
    }),
};
