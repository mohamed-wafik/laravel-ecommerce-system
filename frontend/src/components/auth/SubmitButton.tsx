import type { ReactNode } from "react";

interface SubmitButtonProps {
  loading: boolean;
  children: ReactNode;
  loadingText?: string;
}

const SubmitButton = ({
  loading,
  children,
  loadingText = "Please wait…",
}: SubmitButtonProps) => (
  <button
    type="submit"
    disabled={loading}
    className="w-full flex items-center justify-center gap-2 bg-primary text-white font-bold text-sm py-3.5 rounded-xl hover:opacity-90 active:scale-[0.98] transition-all disabled:opacity-60 disabled:cursor-not-allowed"
  >
    {loading ? (
      <>
        <span className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
        {loadingText}
      </>
    ) : (
      children
    )}
  </button>
);

export default SubmitButton;
