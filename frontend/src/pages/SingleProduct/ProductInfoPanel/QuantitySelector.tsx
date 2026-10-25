import { Minus, Plus } from "lucide-react";

interface QuantitySelectorProps {
  quantity: number;
  onDecrease: () => void;
  onIncrease: () => void;
  disabled?: boolean;
}

const QuantitySelector = ({
  quantity,
  onDecrease,
  onIncrease,
  disabled = false,
}: QuantitySelectorProps) => {
  return (
    <div className="mb-6 flex items-center justify-between gap-4 sm:mb-8">
      <span className="text-sm font-semibold text-gray-900">Quantity</span>

      <div className="flex overflow-hidden rounded-xl border border-gray-200">
        <button
          type="button"
          onClick={onDecrease}
          disabled={disabled || quantity <= 1}
          className="flex h-10 w-10 items-center justify-center text-gray-500 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40 sm:h-11 sm:w-11"
          aria-label="Decrease quantity"
        >
          <Minus className="h-4 w-4" />
        </button>

        <span className="flex h-10 w-12 items-center justify-center border-x border-gray-200 text-sm font-bold text-gray-900 sm:h-11">
          {quantity}
        </span>

        <button
          type="button"
          onClick={onIncrease}
          disabled={disabled || quantity >= 999}
          className="flex h-10 w-10 items-center justify-center text-gray-500 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40 sm:h-11 sm:w-11"
          aria-label="Increase quantity"
        >
          <Plus className="h-4 w-4" />
        </button>
      </div>
    </div>
  );
};

export default QuantitySelector;
