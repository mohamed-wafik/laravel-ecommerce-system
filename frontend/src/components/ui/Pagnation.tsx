import { ChevronLeft, ChevronRight } from "lucide-react";

import type { IPagination } from "../../interface";
import { useMemo } from "react";

interface IProps {
  pagination: IPagination;
  setPage: (val: number) => void;
}

const Pagination = ({ pagination, setPage }: IProps) => {
  const { current_page, last_page } = pagination;
  const pageInfo = useMemo(() => {
    if (!pagination) return "";

    return `${pagination.from ?? 0}-${pagination.to ?? 0} of ${pagination.total}`;
  }, [pagination]);
  return (
    <div className="flex flex-col gap-3 rounded-2xl border border-border bg-bg-primary p-4 sm:flex-row sm:items-center sm:justify-between">
      <p className="text-sm text-text-secondary">
        Showing{" "}
        <span className="font-semibold text-text-primary">{pageInfo}</span>
      </p>

      <div className="flex items-center gap-2">
        <button
          type="button"
          disabled={current_page === 1}
          onClick={() => setPage(current_page - 1)}
          className="flex h-9 w-9 items-center justify-center rounded-lg border border-border text-text-secondary transition hover:border-primary hover:text-primary disabled:cursor-not-allowed disabled:opacity-40"
        >
          <ChevronLeft size={17} />
        </button>

        <span className="min-w-20 text-center text-sm font-medium text-text-primary">
          Page {current_page} / {last_page}
        </span>

        <button
          type="button"
          disabled={current_page === last_page}
          onClick={() => setPage(current_page + 1)}
          className="flex h-9 w-9 items-center justify-center rounded-lg border border-border text-text-secondary transition hover:border-primary hover:text-primary disabled:cursor-not-allowed disabled:opacity-40"
        >
          <ChevronRight size={17} />
        </button>
      </div>
    </div>
  );
};

export default Pagination;
