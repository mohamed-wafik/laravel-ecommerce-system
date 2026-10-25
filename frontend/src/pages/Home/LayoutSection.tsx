import { type LucideIcon } from "lucide-react";
import type { ReactNode } from "react";

interface IProps {
  id: string;
  title: string;
  titleHighlight?: string;
  subTitle: string;
  label: string;
  Icon: LucideIcon;
  children: ReactNode;
}
const LayoutSection = ({
  id,
  label,
  title,
  titleHighlight,
  subTitle,
  Icon,
  children,
}: IProps) => {
  return (
    <section id={id} className="py-20 bg-linear-to-b from-gray-50 to-white">
      <div className="container mx-auto px-4">
        {/* Enhanced Heading */}
        <div className="text-center mb-16 animate-[0.8s_ease-out_fade-in-up]">
          <div className="inline-flex items-center gap-2 bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-sm font-semibold mb-4">
            <Icon className="w-4 h-4" />
            {label}
          </div>
          <h2 className="text-5xl font-bold text-gray-900 mb-6">
            {title}
            <span className="text-transparent bg-clip-text bg-linear-to-r from-blue-600 to-purple-600">
              {titleHighlight}
            </span>
          </h2>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
            {subTitle}
          </p>
        </div>

        {children}
      </div>
    </section>
  );
};

export default LayoutSection;
