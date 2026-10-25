interface FormFieldProps {
  label: string;
  htmlFor: string;
  error?: string;
  required?: boolean;
  children: React.ReactNode;
}

function FormField({
  label,
  htmlFor,
  error,
  required,
  children,
}: FormFieldProps) {
  return (
    <div className="space-y-1.5">
      <label
        htmlFor={htmlFor}
        className="block text-sm font-medium text-text-primary"
      >
        {label}

        {required && (
          <span className="ml-1 text-error" aria-hidden="true">
            *
          </span>
        )}
      </label>

      {children}

      {error && (
        <p
          id={`${htmlFor}-error`}
          className="text-xs leading-5 text-error"
          role="alert"
        >
          {error}
        </p>
      )}
    </div>
  );
}

export default FormField;
