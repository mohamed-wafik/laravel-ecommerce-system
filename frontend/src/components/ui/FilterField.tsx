interface IFilterFieldProps {
  label: string;
  children: React.ReactNode;
}

const FilterField = ({ label, children }: IFilterFieldProps) => {
  return (
    <div className="min-w-0">
      <label className="mb-1.5 block text-xs font-medium text-text-secondary">
        {label}
      </label>

      {children}
    </div>
  );
};

export default FilterField;
