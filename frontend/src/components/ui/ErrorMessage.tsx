import { AlertCircle } from "lucide-react";

interface ErrorMessageProps {
  message?: string;
  className?: string;
}

const ErrorMessage = ({ message, className = "" }: ErrorMessageProps) => {
  if (!message) return null;

  return (
    <div
      role="alert"
      className={`flex items-center gap-2 px-3 text-sm text-error ${className}`}
    >
      <AlertCircle className="h-4 w-4 shrink-0" />

      <span>{message}</span>
    </div>
  );
};

export default ErrorMessage;
