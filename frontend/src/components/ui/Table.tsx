import type { ReactNode } from "react";

interface IProps {
  isLoading?: boolean;
  length: number;
  emptyText: string;
  itemsHeader: string[];
  width: string;
  children: ReactNode;
}
export const Table = ({
  itemsHeader,
  emptyText,
  length,
  isLoading,
  width,
  children,
}: IProps) => {
  return (
    <div className="hidden overflow-hidden rounded-2xl border border-border bg-bg-primary shadow-sm md:block">
      <div className="overflow-x-auto">
        <table className={`w-full min-w-[${width}px]`}>
          <thead>
            <tr className="border-b border-border bg-bg-secondary">
              {itemsHeader.map((title, index) => (
                <Th key={index} content={title} />
              ))}
              <Th content={"Action"} style="text-right" />
            </tr>
          </thead>

          <tbody className="divide-y divide-border">
            {" "}
            {isLoading ? (
              Array.from({ length: 10 }).map((_, index) => (
                <tr key={index}>
                  {Array.from({ length: itemsHeader.length + 1 }).map(
                    (__, cell) => (
                      <Td key={cell}>
                        <div className="h-10 animate-pulse rounded-lg bg-bg-tertiary" />
                      </Td>
                    ),
                  )}
                </tr>
              ))
            ) : length === 0 ? (
              <tr>
                <td colSpan={7} className="px-5 py-12 text-center">
                  <div className="text-sm text-text-secondary">{emptyText}</div>
                </td>
              </tr>
            ) : (
              children
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export const Th = ({ content, style }: { content: string; style?: string }) => {
  return (
    <th
      className={`px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-text-secondary ${style}`}
    >
      {content}
    </th>
  );
};
export const Td = ({ children }: { children: ReactNode }) => {
  return <td className="px-5 py-4">{children}</td>;
};
