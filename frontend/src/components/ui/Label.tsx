interface IProps {
  htmlFor: string;
  text: string;
  className?: string;
}
const Label = ({ htmlFor, text, className }: IProps) => {
  return (
    <label
      htmlFor={htmlFor}
      className={`text-sm font-medium text-text-primary ${className}`}
    >
      {text}
    </label>
  );
};

export default Label;
