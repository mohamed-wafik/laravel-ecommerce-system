import useFetch from "../../../hook/useAuthentienticatedQuery";
import type { IPagination, IUser } from "../../../interface";
import api from "../../axios";
import { ENDPOINTS } from "../../endpoints";

export const SUPPORT_STATUSES = [
  "open",
  "in_progress",
  "resolved",
  "closed",
] as const;

export type SupportTicketStatus = (typeof SUPPORT_STATUSES)[number];

export interface ISupportTicket {
  id: string;
  user_id: string;
  name: string;
  email: string;
  subject: string;
  message: string;
  status: SupportTicketStatus;
  last_reply_at: string | null;
  created_at: string;
  updated_at: string;
  user: IUser | null;
}

export interface ISupportTicketStats {
  open: number;
  in_progress: number;
  resolved: number;
  closed: number;
}

export interface ISupportTicketFilters {
  page: number;
  per_page: number;
  search: string;
  status: string;
}

export interface ISupportTicketResponse {
  items: ISupportTicket[];
  pagination: IPagination;
}

export const adminSupportAPI = {
  list: (filters?: ISupportTicketFilters) =>
    useFetch({
      queryKey: [
        "admin-support-tickets",
        `page=${filters?.page ?? 1}`,
        `per_page=${filters?.per_page ?? 10}`,
        `search=${filters?.search ?? ""}`,
        `status=${filters?.status ?? ""}`,
      ],
      url: ENDPOINTS.ADMIN.CONTACT_SUPPORT.ALL,
      config: {
        params: filters,
      },
    }),
  stats: () =>
    useFetch({
      queryKey: ["admin-support-ticket-stats"],
      url: ENDPOINTS.ADMIN.CONTACT_SUPPORT.STATS,
    }),

  reply: async (
    id: string,
    { message, status }: { message: string; status: SupportTicketStatus },
  ) => {
    const response = await api.post(ENDPOINTS.ADMIN.CONTACT_SUPPORT.REPLY(id), {
      message,
      status,
    });

    return response.data;
  },

  updateStatus: async (id: string, status: SupportTicketStatus) => {
    const response = await api.patch(
      ENDPOINTS.ADMIN.CONTACT_SUPPORT.UPDATE_STATUS(id),
      {
        status,
      },
    );

    return response.data;
  },
};
