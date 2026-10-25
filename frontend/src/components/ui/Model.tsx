import type { ReactNode } from "react";
import Button from "./Button";
import { X } from "lucide-react";

interface IProps {
  title: string;
  subTitle: string;
  isDisabled?: boolean;
  onClose: () => void;
  children: ReactNode;
}
const Model = ({ title, subTitle, isDisabled, onClose, children }: IProps) => {
  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
      onMouseDown={(event) => {
        if (event.target === event.currentTarget) {
          onClose();
        }
      }}
    >
      <div className="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-bg-primary shadow-2xl">
        {/* Modal Header */}
        <div className="sticky top-0 z-10 flex items-center justify-between border-b border-border bg-bg-primary px-5 py-4 sm:px-6">
          <div>
            <h2 className="text-xl font-bold text-text-primary">{title}</h2>

            <p className="mt-1 text-sm text-text-secondary">{subTitle}</p>
          </div>

          <Button
            variant={"outline"}
            type="button"
            rounded={"full"}
            onClick={onClose}
            disabled={isDisabled}
            className="w-10 h-10 p-3"
          >
            <X size={20} />
          </Button>
        </div>

        {/* {contect} */}
        {children}
      </div>
    </div>
  );
};

export default Model;
