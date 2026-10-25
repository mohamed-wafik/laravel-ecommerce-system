import { CheckCircle2 } from "lucide-react";

interface IProp {
  sentEmail: string;
  setSentEmail: (val: string | null) => void;
}
const ErrorSendEmail = ({ sentEmail, setSentEmail }: IProp) => {
  return (
    <section className="mx-auto flex min-h-[60vh] w-full max-w-2xl items-center justify-center px-4 py-16 sm:px-6">
      <div className="w-full rounded-3xl border border-border bg-bg-primary p-8 text-center shadow-sm sm:p-12">
        <div className="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-success-50 text-success">
          <CheckCircle2 className="h-8 w-8" strokeWidth={1.75} />
        </div>

        <h1 className="mt-6 text-2xl font-semibold text-text-primary sm:text-3xl">
          Message sent
        </h1>

        <p className="mx-auto mt-3 max-w-md text-sm leading-6 text-text-secondary sm:text-base">
          Thanks for contacting us. We'll get back to you at{" "}
          <span className="font-medium text-text-primary">{sentEmail}</span>{" "}
          within one business day.
        </p>

        <button
          type="button"
          onClick={() => setSentEmail(null)}
          className="mt-8 rounded-full border border-border px-6 py-2.5 text-sm font-medium text-text-primary transition-all hover:border-primary hover:text-primary"
        >
          Send another message
        </button>
      </div>
    </section>
  );
};

export default ErrorSendEmail;
