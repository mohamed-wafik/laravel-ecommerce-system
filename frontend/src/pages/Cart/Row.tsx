const Row = ({
  label,
  value,
  green = false,
  bold = false,
}: {
  label: string;
  value: string;
  green?: boolean;
  bold?: boolean;
}) => (
  <div
    className={`flex justify-between items-center ${bold ? "text-base font-black" : "text-sm"}`}
  >
    <span className={bold ? "text-dark" : "text-gray-500"}>{label}</span>
    <span
      className={
        bold
          ? "text-primary text-lg"
          : green
            ? "text-green-600 font-semibold"
            : "font-semibold text-dark"
      }
    >
      {value}
    </span>
  </div>
);

export default Row;
