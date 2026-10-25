import { useQuery } from "@tanstack/react-query";

import type { AxiosRequestConfig } from "axios";
import api from "../api/axios";
interface Iprops {
  queryKey: string[];
  url: string;
  config?: AxiosRequestConfig;
}
const useFetch = ({ queryKey, url, config }: Iprops) => {
  return useQuery({
    queryKey,
    queryFn: async () => {
      const { data } = await api.get(url, config);
      return data;
    },
  });
};

export default useFetch;
