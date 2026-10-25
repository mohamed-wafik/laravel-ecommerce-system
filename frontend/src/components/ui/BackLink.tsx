import { ArrowLeft } from "lucide-react";
import { Link } from "react-router-dom";

const BackLink = ({ to, label }: { to: string; label: string }) => (
  <Link
    to={to}
    className="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-violet-600 transition-colors mb-8 group"
  >
    <ArrowLeft
      size={15}
      className="group-hover:-translate-x-0.5 transition-transform"
    />
    {label}
  </Link>
);
export default BackLink;
