import type { ReactNode } from "react";

interface AuthLayoutProps {
  left: ReactNode;
  right: ReactNode;
}

/**
 * Splits the viewport: decorative info panel on the left (hidden on mobile),
 * scrollable form panel on the right.
 */
const AuthLayout = ({ left, right }: AuthLayoutProps) => (
  <div className="min-h-screen grid grid-cols-1 lg:grid-cols-2">
    {/* Left — hidden on small screens */}
    <div className="hidden lg:flex">{left}</div>

    {/* Right — always visible */}
    <div className="flex items-center justify-center bg-gray-50 px-6 py-12">
      <div className="w-full max-w-md">{right}</div>
    </div>
  </div>
);

export default AuthLayout;
