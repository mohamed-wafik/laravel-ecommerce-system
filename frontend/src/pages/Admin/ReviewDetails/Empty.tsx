import { ArrowLeft } from "lucide-react";
import { Link } from "react-router-dom";

const Empty = () => {
  return (
    <section className="space-y-6">
      <Link
        to={"/admin/review"}
        className="inline-flex items-center gap-2 text-sm font-medium text-text-secondary transition hover:text-text-primary"
      >
        <ArrowLeft size={17} />
        Back to reviews
      </Link>

      <div className="rounded-2xl border border-border bg-bg-primary px-5 py-12 text-center shadow-sm">
        <p className="text-sm text-text-secondary">Review not found.</p>
      </div>
    </section>
  );
};

export default Empty;
