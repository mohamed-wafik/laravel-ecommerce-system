interface IActionButtonProps {
  label: string;
  danger?: boolean;
  onClick: () => void;
  children: React.ReactNode;
}

const ActionButton = ({
  label,
  danger,
  onClick,
  children,
}: IActionButtonProps) => {
  return (
    <button
      type="button"
      title={label}
      onClick={onClick}
      className={`flex h-9 w-9 items-center justify-center rounded-lg border transition ${
        danger
          ? "border-error/20 text-error hover:bg-error-50"
          : "border-border text-text-secondary hover:border-primary hover:text-primary"
      }`}
    >
      {children}
    </button>
  );
};

export default ActionButton;
