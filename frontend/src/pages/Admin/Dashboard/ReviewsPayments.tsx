import { CheckCircle2, CreditCard, Star, XCircle } from "lucide-react";

interface IProps {
  summary: any;
}
const ReviewsPayments = ({ summary }: IProps) => {
  return (
    <section className="grid gap-6 lg:grid-cols-2">
      {/* Reviews */}
      <div className="rounded-2xl border border-border bg-bg-primary p-6 shadow-sm">
        <div className="flex items-center justify-between">
          <div>
            <h2 className="font-semibold text-text-primary">Reviews</h2>

            <p className="mt-1 text-sm text-text-secondary">
              Customer review overview
            </p>
          </div>

          <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-rating/10 text-rating">
            <Star className="h-5 w-5 fill-current" />
          </div>
        </div>

        <div className="mt-6 grid grid-cols-2 gap-4">
          <div className="rounded-xl bg-bg-secondary p-4">
            <p className="text-sm text-text-secondary">Average Rating</p>

            <div className="mt-2 flex items-center gap-2">
              <span className="text-2xl font-bold text-text-primary">
                {summary.reviews.average_rating.toFixed(2)}
              </span>

              <Star className="h-5 w-5 fill-current text-rating" />
            </div>
          </div>

          <div className="rounded-xl bg-bg-secondary p-4">
            <p className="text-sm text-text-secondary">Pending Approval</p>

            <p className="mt-2 text-2xl font-bold text-text-primary">
              {summary.reviews.pending_approval}
            </p>
          </div>
        </div>
      </div>

      {/* Payments */}
      <div className="rounded-2xl border border-border bg-bg-primary p-6 shadow-sm">
        <div className="flex items-center justify-between">
          <div>
            <h2 className="font-semibold text-text-primary">
              Payment Overview
            </h2>

            <p className="mt-1 text-sm text-text-secondary">
              Current payment status
            </p>
          </div>

          <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary">
            <CreditCard className="h-5 w-5" />
          </div>
        </div>

        <div className="mt-6 space-y-4">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-3">
              <CheckCircle2 className="h-5 w-5 text-success" />

              <span className="text-sm text-text-secondary">Succeeded</span>
            </div>

            <span className="font-semibold text-text-primary">
              {summary.payments.succeeded}
            </span>
          </div>

          <div className="flex items-center justify-between">
            <div className="flex items-center gap-3">
              <XCircle className="h-5 w-5 text-error" />

              <span className="text-sm text-text-secondary">Failed</span>
            </div>

            <span className="font-semibold text-text-primary">
              {summary.payments.failed}
            </span>
          </div>

          <div className="flex items-center justify-between">
            <div className="flex items-center gap-3">
              <CreditCard className="h-5 w-5 text-warning" />

              <span className="text-sm text-text-secondary">Refunded</span>
            </div>

            <span className="font-semibold text-text-primary">
              {summary.payments.refunded}
            </span>
          </div>
        </div>
      </div>
    </section>
  );
};

export default ReviewsPayments;
