import useFetch from "../../../hook/useAuthentienticatedQuery";
import { ENDPOINTS } from "../../endpoints";

export const brandApi = {
  getAll: () =>
    useFetch({ queryKey: ["brands"], url: ENDPOINTS.GUEST.BRAND.ALL }),
};
