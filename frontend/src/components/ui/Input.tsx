import * as React from "react";
import { cva, type VariantProps } from "class-variance-authority";
import { cn } from "../../lib/utils";

export const inputVariants = cva(
  "w-full px-4 py-2 border focus:outline-none focus:ring-2 focus:ring-offset-1 transition-all disabled:opacity-60",
  {
    variants: {
      variant: {
        default: "",
        error: "",
        filled: "",
      },
      inputSize: {
        sm: "h-8 text-sm",
        md: "h-10 text-base",
        lg: "h-12 text-lg",
      },
      rounded: {
        none: "rounded-none",
        sm: "rounded-md",
        lg: "rounded-xl",
        full: "rounded-full",
      },
    },
    defaultVariants: {
      variant: "default",
      inputSize: "md",
      rounded: "lg",
    },
  },
);

export const variantStyles: Record<string, React.CSSProperties> = {
  default: {
    borderColor: "var(--color-border-dark)",
    color: "var(--color-text-primary)",
    background: "var(--color-bg-primary)",
    // focus handled via onFocus/onBlur
  },
  error: {
    borderColor: "var(--color-error)",
    color: "var(--color-error-dark)",
    background: "var(--color-error-50)",
  },
  filled: {
    borderColor: "transparent",
    color: "var(--color-text-primary)",
    background: "var(--color-bg-tertiary)",
  },
};

export const variantFocusStyles: Record<string, React.CSSProperties> = {
  default: {
    borderColor: "var(--color-primary)",
    outline: "none",
    boxShadow: "0 0 0 2px var(--color-primary-100)",
  },
  error: {
    borderColor: "var(--color-error)",
    outline: "none",
    boxShadow: "0 0 0 2px var(--color-error-100)",
  },
  filled: {
    borderColor: "var(--color-primary)",
    outline: "none",
    boxShadow: "0 0 0 2px var(--color-primary-100)",
  },
};

export interface IInputProps
  extends
    Omit<React.InputHTMLAttributes<HTMLInputElement>, "size">,
    VariantProps<typeof inputVariants> {}

const Input = React.forwardRef<HTMLInputElement, IInputProps>(
  (
    {
      className,
      variant,
      inputSize,
      rounded,
      style,
      onFocus,
      onBlur,
      ...props
    },
    ref,
  ) => {
    const resolvedVariant = variant ?? "default";
    const baseStyle = variantStyles[resolvedVariant] ?? {};
    const focusStyle = variantFocusStyles[resolvedVariant] ?? {};

    const [isFocused, setIsFocused] = React.useState(false);

    const handleFocus = (e: React.FocusEvent<HTMLInputElement>) => {
      setIsFocused(true);
      onFocus?.(e);
    };

    const handleBlur = (e: React.FocusEvent<HTMLInputElement>) => {
      setIsFocused(false);
      onBlur?.(e);
    };

    return (
      <input
        ref={ref}
        className={cn(
          inputVariants({ variant, inputSize, rounded }),
          className,
        )}
        style={{
          ...baseStyle,
          ...(isFocused ? focusStyle : {}),
          ...style,
        }}
        onFocus={handleFocus}
        onBlur={handleBlur}
        {...props}
      />
    );
  },
);

Input.displayName = "Input";

export default Input;
