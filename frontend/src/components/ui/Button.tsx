import * as React from "react";
import { cva, type VariantProps } from "class-variance-authority";
import { cn } from "../../lib/utils";

const buttonVariants = cva(
  "inline-flex items-center justify-center font-medium transition-all select-none active:scale-95 focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:pointer-events-none cursor-pointer duration-150",
  {
    variants: {
      variant: {
        primary:
          "bg-primary text-text-light hover:bg-secondary focus:ring-primary",

        secondary:
          "bg-secondary text-text-light hover:bg-primary focus:ring-secondary",

        dark: "bg-text-primary text-text-light hover:opacity-90 focus:ring-text-primary",

        destructive:
          "bg-error text-text-light hover:opacity-90 focus:ring-error",

        light:
          "bg-bg-secondary text-text-primary hover:bg-bg-tertiary focus:ring-border",

        outline:
          "bg-transparent text-text-primary border border-border hover:bg-bg-secondary focus:ring-border",
      },

      size: {
        sm: "min-h-8 px-3 text-sm",
        md: "min-h-10 px-4 text-base",
        lg: "min-h-12 px-6 text-lg",
      },

      rounded: {
        none: "rounded-none",
        sm: "rounded-md",
        lg: "rounded-xl",
        full: "rounded-full",
      },

      width: {
        full: "w-full",
        fit: "w-fit",
      },

      loading: {
        true: "opacity-90 pointer-events-none",
        false: "",
      },
    },

    defaultVariants: {
      variant: "primary",
      size: "md",
      rounded: "lg",
      width: "fit",
      loading: false,
    },
  },
);

export interface ButtonProps
  extends
    React.ButtonHTMLAttributes<HTMLButtonElement>,
    VariantProps<typeof buttonVariants> {
  loading?: boolean;
}

const Button = React.forwardRef<HTMLButtonElement, ButtonProps>(
  (
    { className, variant, size, rounded, loading, width, children, ...props },
    ref,
  ) => {
    return (
      <button
        ref={ref}
        disabled={loading ?? false}
        className={cn(
          buttonVariants({ variant, size, rounded, loading, width }),
          className,
        )}
        {...props}
      >
        {loading && (
          <span className="mr-2 h-4 w-4 border-2 border-current border-t-transparent rounded-full animate-spin" />
        )}
        {children}
      </button>
    );
  },
);

Button.displayName = "Button";

export default Button;
