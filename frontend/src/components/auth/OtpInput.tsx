import {
  useRef,
  useState,
  useEffect,
  forwardRef,
  useImperativeHandle,
  type ClipboardEvent,
  type KeyboardEvent,
} from "react";

export interface OtpInputHandle {
  getValue: () => string;
  clear: () => void;
  focus: () => void;
}

interface OtpInputProps {
  length?: number;
  error?: string;
  disabled?: boolean;
  onChange?: (value: string) => void;
}

const OtpInput = forwardRef<OtpInputHandle, OtpInputProps>(
  ({ length = 6, error, disabled = false, onChange }, ref) => {
    const [cells, setCells] = useState<string[]>(Array(length).fill(""));
    const inputRefs = useRef<(HTMLInputElement | null)[]>([]);

    // Expose handle to parent (RHF Controller)
    useImperativeHandle(ref, () => ({
      getValue: () => cells.join(""),
      clear: () => {
        setCells(Array(length).fill(""));
        inputRefs.current[0]?.focus();
      },
      focus: () => inputRefs.current[0]?.focus(),
    }));

    // Notify RHF / parent of full value whenever cells change
    useEffect(() => {
      onChange?.(cells.join(""));
    }, [cells, onChange]);

    const updateCell = (index: number, digit: string) => {
      if (!/^\d?$/.test(digit)) return; // only single digit or empty
      const next = [...cells];
      next[index] = digit;
      setCells(next);
      if (digit && index < length - 1) {
        inputRefs.current[index + 1]?.focus();
      }
    };

    const handleKeyDown = (
      index: number,
      e: KeyboardEvent<HTMLInputElement>,
    ) => {
      if (e.key === "Backspace") {
        if (cells[index]) {
          // Clear current cell
          updateCell(index, "");
        } else if (index > 0) {
          // Move back and clear previous
          inputRefs.current[index - 1]?.focus();
          updateCell(index - 1, "");
        }
        e.preventDefault();
      }
      if (e.key === "ArrowLeft" && index > 0)
        inputRefs.current[index - 1]?.focus();
      if (e.key === "ArrowRight" && index < length - 1)
        inputRefs.current[index + 1]?.focus();
    };

    const handlePaste = (e: ClipboardEvent<HTMLInputElement>) => {
      e.preventDefault();
      const pasted = e.clipboardData
        .getData("text")
        .replace(/\D/g, "")
        .slice(0, length);
      const next = [...cells];
      pasted.split("").forEach((ch, i) => {
        next[i] = ch;
      });
      setCells(next);
      // Focus the cell after the last pasted character
      const nextFocus = Math.min(pasted.length, length - 1);
      inputRefs.current[nextFocus]?.focus();
    };

    return (
      <div className="flex flex-col items-center gap-3">
        <div className="flex gap-3">
          {cells.map((cell, i) => (
            <input
              key={i}
              ref={(el) => {
                inputRefs.current[i] = el;
              }}
              type="text"
              inputMode="numeric"
              maxLength={1}
              value={cell}
              disabled={disabled}
              onChange={(e) => updateCell(i, e.target.value)}
              onKeyDown={(e) => handleKeyDown(i, e)}
              onPaste={handlePaste}
              onFocus={(e) => e.target.select()}
              aria-label={`Digit ${i + 1}`}
              className={`w-12 h-14 text-center text-xl font-bold rounded-xl border outline-none
                transition-all duration-150 bg-white
                ${
                  error
                    ? "border-red-400 text-red-600 focus:ring-2 focus:ring-red-100"
                    : cell
                      ? "border-primary text-primary bg-primary/5 focus:ring-2 focus:ring-primary/20"
                      : "border-gray-200 text-dark focus:border-primary focus:ring-2 focus:ring-primary/10"
                }
                disabled:opacity-50 disabled:cursor-not-allowed`}
            />
          ))}
        </div>
        {error && <p className="text-xs text-red-500 text-center">{error}</p>}
      </div>
    );
  },
);

OtpInput.displayName = "OtpInput";

export default OtpInput;
