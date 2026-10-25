import { ArrowLeft, Package } from "lucide-react";
import { Link } from "react-router-dom";

interface IProps {
  link: string;
}
const NotFound = ({ link }: IProps) => {
  return (
    <section className="flex min-h-[50vh] items-center justify-center px-4">
      <div className="text-center">
        <Package size={42} className="mx-auto text-text-tertiary" />

        <h2 className="mt-4 text-lg font-semibold text-text-primary">
          Order not found
        </h2>

        <p className="mt-1 text-sm text-text-secondary">
          The order you are looking for does not exist.
        </p>

        <Link
          to={link}
          className="mt-5 inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90"
        >
          <ArrowLeft size={16} />
          Back to Orders
        </Link>
      </div>
    </section>
  );
};

export default NotFound;
