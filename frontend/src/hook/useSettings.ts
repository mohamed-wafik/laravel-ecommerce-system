import { useQuery } from "@tanstack/react-query";
import api from "../api/axios";

export const useSettings = () => {
  return useQuery(
    ["store-settings"],
    async () => {
      const response = await api.get("/settings");

      console.log("setings", response.data);
      return response.data.data;
    },
    {
      staleTime: 1000 * 60 * 30, // 30 minutes
      cacheTime: 1000 * 60 * 60, // 1 hour
      retry: 1,
    },
  );
};
