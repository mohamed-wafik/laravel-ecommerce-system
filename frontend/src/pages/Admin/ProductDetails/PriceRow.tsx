interface PriceRowProps {
  label: string;
  value: string;
}

const PriceRow = ({ label, value }: PriceRowProps) => (
  <div className="flex items-center justify-between gap-4">
    <span className="text-sm text-text-secondary">{label}</span>
    <span className="text-sm font-semibold text-text-primary">{value}</span>
  </div>
);
export default PriceRow;
