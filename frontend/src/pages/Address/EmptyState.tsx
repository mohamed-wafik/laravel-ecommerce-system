import { MapPin, Plus } from "lucide-react";

interface IProps {
  onAdd: () => void;
}
const EmptyState = ({ onAdd }: IProps) => {
  return (
    <div className="rounded-2xl border border-dashed border-border-dark bg-bg-primary px-6 py-16 text-center">
      <div className="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-primary-50 text-primary">
        <MapPin size={28} />
      </div>

      <h2 className="mt-5 text-xl font-bold text-text-primary">
        No saved addresses
      </h2>

      <p className="mx-auto mt-2 max-w-md text-sm leading-6 text-text-secondary">
        Add a delivery address to make checkout faster and easier.
      </p>

      <button
        type="button"
        onClick={onAdd}
        className="mt-6 inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white transition hover:bg-primary-dark"
      >
        <Plus size={18} />
        Add Your First Address
      </button>
    </div>
  );
};

export default EmptyState;
