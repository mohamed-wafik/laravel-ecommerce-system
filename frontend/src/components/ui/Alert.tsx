import { AlertTriangle } from "lucide-react";
import { type ReactNode } from "react";

interface IProps {
  title: string;
  subTitle: string;
  onClose: () => void;
  children: ReactNode;
}

const Alert = ({ title, subTitle, onClose, children }: IProps) => {
  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
      onMouseDown={(event) => {
        if (event.target === event.currentTarget) {
          onClose();
        }
      }}
    >
      <div className="w-full max-w-sm rounded-2xl border border-gray-100 bg-white p-5 shadow-2xl">
        {/* Icon + message */}
        <div className="mb-5 flex items-start gap-3">
          <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50">
            <AlertTriangle className="h-5 w-5 text-red-500" />
          </div>

          <div className="min-w-0">
            <h3 className="text-sm font-bold text-dark">{title}</h3>

            <p className="mt-1 text-xs leading-relaxed text-gray-400">
              {subTitle}
            </p>
          </div>
        </div>

        {/* Actions */}
        <div className="flex items-center justify-end gap-2">{children}</div>
      </div>
    </div>
  );
};

export default Alert;
