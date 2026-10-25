import { useState, forwardRef, type InputHTMLAttributes } from "react";
import { Eye, EyeOff } from "lucide-react";
import FormField from "../ui/FormField";
import Input from "../ui/Input";

interface PasswordInputProps extends Omit<
  InputHTMLAttributes<HTMLInputElement>,
  "type"
> {
  label: string;
  error?: string;
  id: string;
}

/**
 * forwardRef tunnels RHF's register ref down into FormField → <input>.
 * The eye-toggle button is positioned inside the wrapper div, not inside
 * FormField, so it never interferes with the ref chain.
 */
const PasswordInput = forwardRef<HTMLInputElement, PasswordInputProps>(
  ({ label, error, id, className = "", ...rest }, ref) => {
    const [visible, setVisible] = useState(false);

    return (
      <div className="relative">
        <FormField htmlFor={id} label={label} error={error}>
          <Input
            ref={ref}
            id={id}
            variant={error ? "error" : "default"}
            type={visible ? "text" : "password"}
            className={`pr-11 ${className}`}
            {...rest}
          />
        </FormField>

        <button
          type="button"
          onClick={() => setVisible((v) => !v)}
          // Position above the reserved error-message line (1rem = 16px + 6px gap)
          className={`absolute right-3 ${error ? "bottom-9.5" : "bottom-3"} text-gray-400 hover:text-gray-600 transition-colors`}
          aria-label={visible ? "Hide password" : "Show password"}
          tabIndex={-1}
        >
          {visible ? (
            <Eye className="w-4 h-4" />
          ) : (
            <EyeOff className="w-4 h-4" />
          )}
        </button>
      </div>
    );
  },
);

PasswordInput.displayName = "PasswordInput";

export default PasswordInput;
