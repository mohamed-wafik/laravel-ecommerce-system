import type { ReactNode } from "react";

interface IInfoItemProps {
  label: string;
  value?: string | ReactNode;
}

const InfoItem = ({ label, value }: IInfoItemProps) => {
  return (
    <div className="min-w-0">
      <p className="text-xs font-medium text-text-tertiary">{label}</p>

      <p className="mt-1 break-all text-sm font-medium text-text-primary">
        {value || "-"}
      </p>
    </div>
  );
};
export default InfoItem;
