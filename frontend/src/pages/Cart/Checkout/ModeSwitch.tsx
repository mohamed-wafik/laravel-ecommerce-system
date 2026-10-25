interface ModeSwitchProps {
  mode: "saved" | "new";
  onChange: (mode: "saved" | "new") => void;
  disabled?: boolean;
}

const ModeSwitch = ({ mode, onChange, disabled }: ModeSwitchProps) => (
  <div className="mb-5 flex gap-2 rounded-xl bg-bg-secondary p-1">
    <button
      type="button"
      disabled={disabled}
      onClick={() => onChange("saved")}
      className={`flex-1 rounded-lg py-2 text-sm font-medium transition-all ${
        mode === "saved"
          ? "bg-white text-text-primary shadow-sm"
          : "text-text-tertiary"
      }`}
    >
      Use saved address
    </button>

    <button
      type="button"
      disabled={disabled}
      onClick={() => onChange("new")}
      className={`flex-1 rounded-lg py-2 text-sm font-medium transition-all ${
        mode === "new"
          ? "bg-white text-text-primary shadow-sm"
          : "text-text-tertiary"
      }`}
    >
      Add new address
    </button>
  </div>
);

export default ModeSwitch;
