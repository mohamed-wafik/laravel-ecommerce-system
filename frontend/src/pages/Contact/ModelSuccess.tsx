import { Link } from "react-router-dom";
import { ArrowRight, CheckCircle } from "lucide-react";

import Model from "../../components/ui/Model";
import Button from "../../components/ui/Button";

interface IProps {
  onClose: () => void;
  ticketId?: string;
}

const ModelSuccess = ({ onClose, ticketId }: IProps) => {
  return (
    <Model
      title="Message Sent Successfully"
      subTitle="We've received your message and will get back to you as soon as possible."
      onClose={onClose}
    >
      <div className="space-y-4 p-6">
        {/* Success Information */}
        <div className="flex items-start gap-3 rounded-lg border border-success-100 bg-success-50 p-4 dark:border-success-dark/30 dark:bg-success-dark/10">
          <CheckCircle size={20} className="mt-0.5 shrink-0 text-success" />

          <div>
            <p className="text-sm font-medium text-success-dark dark:text-success-light">
              Your request has been submitted
            </p>

            <p className="mt-1 text-sm text-success-dark/80 dark:text-success-light/70">
              Our support team will review your message and respond as soon as
              possible.
            </p>
          </div>
        </div>

        {/* Ticket Details */}
        {ticketId && (
          <div className="rounded-lg border border-border bg-bg-secondary p-4 dark:border-gray-700 dark:bg-gray-800/50">
            <div className="flex items-center justify-between gap-4">
              <span className="text-sm text-text-secondary">Ticket ID</span>

              <span className="max-w-[65%] truncate font-mono text-sm font-medium text-text-primary dark:text-text-light">
                #{ticketId}
              </span>
            </div>

            <div className="mt-3 flex items-center justify-between">
              <span className="text-sm text-text-secondary">Status</span>

              <span className="rounded-full bg-warning-50 px-2.5 py-1 text-xs font-medium text-warning-dark dark:bg-warning-dark/15 dark:text-warning-light">
                Pending
              </span>
            </div>
          </div>
        )}

        {/* Support information */}
        <p className="text-center text-sm text-gray-500 dark:text-gray-400">
          You can check your ticket and follow the response from your{" "}
          <Link
            to="/support"
            onClick={onClose}
            className="inline-flex items-center gap-1 font-medium text-primary hover:underline"
          >
            Support page
            <ArrowRight size={14} />
          </Link>
          .
        </p>

        {/* Close */}
        <Button type="button" onClick={onClose} variant="outline" width="full">
          Close
        </Button>
      </div>
    </Model>
  );
};

export default ModelSuccess;
