interface SectionCardProps {
  children: React.ReactNode;
  className?: string;
}

const SectionCard = ({ children, className = "" }: SectionCardProps) => (
  <div
    className={`overflow-hidden rounded-2xl border border-border bg-bg-primary shadow-sm ${className}`}
  >
    {children}
  </div>
);
export default SectionCard;
