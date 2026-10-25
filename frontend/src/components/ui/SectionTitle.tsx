interface ISectionTitleProps {
  title: string;
  description: string;
}

const SectionTitle = ({ title, description }: ISectionTitleProps) => {
  return (
    <div>
      <h3 className="font-semibold text-text-primary">{title}</h3>

      <p className="mt-1 text-xs text-text-tertiary">{description}</p>
    </div>
  );
};
export default SectionTitle;
