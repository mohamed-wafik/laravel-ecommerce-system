import { Users } from "lucide-react";

const CustomerCard = ({
  customers,
}: {
  customers: {
    new: number;
    returning: number;
  };
}) => {
  const total = customers.new + customers.returning;

  const newPercentage =
    total > 0 ? Math.round((customers.new / total) * 100) : 0;

  const returningPercentage =
    total > 0 ? Math.round((customers.returning / total) * 100) : 0;

  return (
    <div className="rounded-2xl border border-border bg-bg-primary p-5 shadow-sm">
      <div className="mb-5 flex items-center justify-between">
        <div>
          <h2 className="font-semibold text-text-primary">
            Customer Analytics
          </h2>

          <p className="mt-1 text-xs text-text-tertiary">
            New vs returning customers
          </p>
        </div>

        <Users className="h-5 w-5 text-primary" />
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="rounded-xl bg-primary-50 p-4">
          <p className="text-xs text-text-secondary">New Customers</p>

          <p className="mt-2 text-2xl font-bold text-primary">
            {customers.new}
          </p>

          <p className="mt-1 text-xs text-text-tertiary">
            {newPercentage}% of customers
          </p>
        </div>

        <div className="rounded-xl bg-success-50 p-4">
          <p className="text-xs text-text-secondary">Returning</p>

          <p className="mt-2 text-2xl font-bold text-success">
            {customers.returning}
          </p>

          <p className="mt-1 text-xs text-text-tertiary">
            {returningPercentage}% of customers
          </p>
        </div>
      </div>

      <div className="mt-5">
        <div className="mb-2 flex justify-between text-xs">
          <span className="text-text-secondary">Customer mix</span>

          <span className="font-medium text-text-primary">{total}</span>
        </div>

        <div className="flex h-2 overflow-hidden rounded-full bg-bg-tertiary">
          <div
            className="bg-primary transition-all"
            style={{ width: `${newPercentage}%` }}
          />

          <div
            className="bg-success transition-all"
            style={{ width: `${returningPercentage}%` }}
          />
        </div>
      </div>
    </div>
  );
};

export default CustomerCard;
