import { Building2 } from "lucide-react";

interface IProps {
  query: string;
}
const EmptyState = ({ query }: IProps) => {
  return (
    <div className="flex flex-col items-center gap-3 rounded-2xl border border-dashed border-border-dark bg-bg-secondary px-6 py-16 text-center">
      <Building2 className="h-10 w-10 text-text-tertiary" strokeWidth={1.5} />
      <p className="font-medium text-text-primary">
        {query ? "No brands match your search" : "No brands yet"}
      </p>
      <p className="max-w-xs text-sm text-text-tertiary">
        {query
          ? `Try a different name than "${query}".`
          : "Brands will show up here as soon as they're added."}
      </p>
    </div>
  );
};

export default EmptyState;
