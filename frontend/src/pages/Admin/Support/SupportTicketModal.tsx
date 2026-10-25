import { useEffect, useState } from "react";
import { useForm, type SubmitHandler } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import toast from "react-hot-toast";
import { Check, Clock, Mail, MessageSquare, User, X } from "lucide-react";

import Model from "../../../components/ui/Model";
import FormField from "../../../components/ui/FormField";
import Button from "../../../components/ui/Button";
import SectionTitle from "../../../components/ui/SectionTitle";

import {
  adminSupportAPI,
  SUPPORT_STATUSES,
  type ISupportTicket,
  type SupportTicketStatus,
} from "../../../api/modules/Admin/contactSupport.api";

import SupportStatusBadge from "./StatusBadge";

import { inputClass } from "../../../data/input";
import {
  supportReplySchema,
  type SupportReplyFormValues,
} from "../../../schema/Admin/admin.support.schema";

interface IProps {
  ticket: ISupportTicket | null;
  closeModal: () => void;
  onSuccess: () => Promise<void>;
}

const statusLabels: Record<SupportTicketStatus, string> = {
  open: "Open",
  in_progress: "In Progress",
  resolved: "Resolved",
  closed: "Closed",
};

const SupportTicketModal = ({ ticket, closeModal, onSuccess }: IProps) => {
  const [selectedStatus, setSelectedStatus] =
    useState<SupportTicketStatus>("open");

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors, isSubmitting },
  } = useForm<SupportReplyFormValues>({
    resolver: zodResolver(supportReplySchema),
    defaultValues: {
      message: "",
    },
  });

  useEffect(() => {
    if (!ticket) return;

    setSelectedStatus(ticket.status);

    reset({
      message: "",
    });
  }, [ticket, reset]);

  if (!ticket) return null;

  const onSubmit: SubmitHandler<SupportReplyFormValues> = async (values) => {
    try {
      if (status === ticket.status) return;
      await adminSupportAPI.reply(ticket.id, {
        message: values.message,
        status: selectedStatus,
      });

      toast.success("Reply sent successfully");

      await onSuccess();
    } catch (error) {
      console.error(error);

      toast.error("Failed to send reply");
    }
  };

  const handleStatusUpdate = async (status: SupportTicketStatus) => {
    if (status === ticket.status) return;

    try {
      await adminSupportAPI.updateStatus(ticket.id, status);

      setSelectedStatus(status);

      toast.success("Ticket status updated");

      await onSuccess();
    } catch (error) {
      console.error(error);

      toast.error("Failed to update ticket status");

      setSelectedStatus(ticket.status);
    }
  };

  return (
    <Model
      title="Support Ticket"
      subTitle="View customer request, reply, and manage ticket status."
      isDisabled={isSubmitting}
      onClose={closeModal}
    >
      <div className="space-y-6 p-5 sm:p-6">
        {/* Customer */}
        <div className="rounded-2xl border border-border bg-bg-secondary p-4">
          <div className="flex items-start gap-4">
            <div className="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full bg-bg-tertiary">
              {ticket.user?.avatar_url ? (
                <img
                  src={ticket.user.avatar_url}
                  alt={ticket.name}
                  className="h-full w-full object-cover"
                />
              ) : (
                <span className="font-semibold text-text-secondary">
                  {ticket.name.charAt(0).toUpperCase()}
                </span>
              )}
            </div>

            <div className="min-w-0 flex-1">
              <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                  <h3 className="font-semibold text-text-primary">
                    {ticket.name}
                  </h3>

                  <div className="mt-1 flex items-center gap-2 text-sm text-text-secondary">
                    <Mail size={14} />

                    <span className="truncate">{ticket.email}</span>
                  </div>
                </div>

                <SupportStatusBadge status={selectedStatus} />
              </div>
            </div>
          </div>
        </div>

        {/* Ticket Information */}
        <div>
          <SectionTitle
            title="Ticket Information"
            description="Customer support request details."
          />

          <div className="mt-4 rounded-2xl border border-border bg-bg-primary p-4">
            <div className="flex items-start gap-3">
              <div className="mt-0.5 rounded-lg bg-primary/10 p-2 text-primary">
                <MessageSquare size={17} />
              </div>

              <div className="min-w-0 flex-1">
                <p className="text-xs font-medium text-text-tertiary">
                  Subject
                </p>

                <h3 className="mt-1 font-semibold text-text-primary">
                  {ticket.subject}
                </h3>
              </div>
            </div>

            <div className="mt-5 border-t border-border pt-4">
              <p className="text-xs font-medium text-text-tertiary">
                Customer Message
              </p>

              <p className="mt-2 whitespace-pre-wrap text-sm leading-6 text-text-secondary">
                {ticket.message}
              </p>
            </div>
          </div>
        </div>

        {/* Metadata */}
        <div className="grid gap-3 sm:grid-cols-3">
          <div className="rounded-xl border border-border bg-bg-secondary p-3">
            <div className="flex items-center gap-2 text-text-tertiary">
              <User size={15} />

              <span className="text-xs">Customer</span>
            </div>

            <p className="mt-2 truncate text-sm font-medium text-text-primary">
              {ticket.name}
            </p>
          </div>

          <div className="rounded-xl border border-border bg-bg-secondary p-3">
            <div className="flex items-center gap-2 text-text-tertiary">
              <Clock size={15} />

              <span className="text-xs">Created</span>
            </div>

            <p className="mt-2 text-sm font-medium text-text-primary">
              {new Date(ticket.created_at).toLocaleDateString()}
            </p>
          </div>

          <div className="rounded-xl border border-border bg-bg-secondary p-3">
            <div className="flex items-center gap-2 text-text-tertiary">
              <MessageSquare size={15} />

              <span className="text-xs">Last Reply</span>
            </div>

            <p className="mt-2 text-sm font-medium text-text-primary">
              {ticket.last_reply_at
                ? new Date(ticket.last_reply_at).toLocaleDateString()
                : "No reply"}
            </p>
          </div>
        </div>

        {/* Status */}
        <div>
          <SectionTitle
            title="Ticket Status"
            description="Update the current support ticket status."
          />

          <div className="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-4">
            {SUPPORT_STATUSES.map((status) => {
              const active = selectedStatus === status;

              return (
                <button
                  key={status}
                  type="button"
                  disabled={isSubmitting}
                  onClick={() => {
                    setSelectedStatus(status);
                    handleStatusUpdate(status);
                  }}
                  className={`rounded-xl border px-3 py-3 text-sm font-medium transition ${
                    active
                      ? "border-primary bg-primary/10 text-primary"
                      : "border-border bg-bg-secondary text-text-secondary hover:border-primary/50 hover:text-text-primary"
                  }`}
                >
                  {statusLabels[status]}
                </button>
              );
            })}
          </div>
        </div>

        {/* Reply */}
        <form onSubmit={handleSubmit(onSubmit)} className="space-y-5">
          <SectionTitle
            title="Reply to Customer"
            description="Send a response to the customer."
          />

          <FormField
            htmlFor="message"
            label="Message"
            error={errors.message?.message}
            required
          >
            <textarea
              id="message"
              {...register("message")}
              rows={6}
              maxLength={5000}
              placeholder="Write your reply..."
              className={`${inputClass} min-h-36 resize-y py-3`}
            />
          </FormField>

          {/* Footer */}
          <div className="flex flex-col-reverse gap-2 border-t border-border pt-5 sm:flex-row sm:justify-end">
            <Button
              type="button"
              variant="outline"
              onClick={closeModal}
              disabled={isSubmitting}
              className="inline-flex items-center justify-center gap-2"
            >
              <X size={17} />
              Cancel
            </Button>

            <Button
              type="submit"
              disabled={isSubmitting}
              loading={isSubmitting}
              className="inline-flex items-center justify-center gap-2"
            >
              <Check size={17} />

              {isSubmitting ? "Sending..." : "Send Reply"}
            </Button>
          </div>
        </form>
      </div>
    </Model>
  );
};

export default SupportTicketModal;
