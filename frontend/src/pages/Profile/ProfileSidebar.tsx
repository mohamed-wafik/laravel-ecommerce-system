import { User, ShieldCheck, ShoppingBag, type LucideIcon } from "lucide-react";
import { Link } from "react-router-dom";
import AvatarUpload from "./AvatarUpload";
import { useAuthStore } from "../../store/useAuth";

export type ProfileTab = "overview" | "security";

interface NavItem {
  id: ProfileTab;
  label: string;
  icon: LucideIcon;
  description: string;
}

const NAV_ITEMS: NavItem[] = [
  {
    id: "overview",
    label: "Profile",
    icon: User,
    description: "Name & avatar",
  },
  {
    id: "security",
    label: "Security",
    icon: ShieldCheck,
    description: "Password & email",
  },
];

interface ProfileSidebarProps {
  activeTab: ProfileTab;
  onTabChange: (tab: ProfileTab) => void;
}

const ProfileSidebar = ({ activeTab, onTabChange }: ProfileSidebarProps) => {
  const { user } = useAuthStore();

  return (
    <aside className="w-full lg:w-64 shrink-0">
      <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {/* Avatar + name */}
        <div className="p-6 border-b border-gray-100 text-center">
          <AvatarUpload />
          <h2 className="mt-4 text-base font-extrabold text-dark truncate">
            {user?.name ?? "—"}
          </h2>
          <p className="text-xs text-gray-400 truncate mt-0.5">{user?.email}</p>
          {user?.created_at && (
            <p className="text-xs text-gray-300 mt-1">
              Member since{" "}
              {new Date(user.created_at).toLocaleDateString(undefined, {
                month: "long",
                year: "numeric",
              })}
            </p>
          )}
        </div>

        {/* Nav */}
        <nav className="p-2 space-x-0.5 space-y-1">
          {/* Profile tabs */}
          {NAV_ITEMS.map(({ id, label, icon: Icon, description }) => {
            const active = activeTab === id;
            return (
              <button
                key={id}
                onClick={() => onTabChange(id)}
                className={`w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left transition-all ${
                  active
                    ? "bg-primary/8 text-primary"
                    : "text-gray-500 hover:bg-gray-50 hover:text-dark"
                }`}
              >
                <Icon
                  className={`w-4 h-4 shrink-0 ${
                    active ? "text-primary" : "text-gray-400"
                  }`}
                />
                <div>
                  <p
                    className={`text-sm font-semibold leading-none ${active ? "text-primary" : "text-dark"}`}
                  >
                    {label}
                  </p>
                  <p className="text-xs text-gray-400 mt-0.5">{description}</p>
                </div>
                {active && (
                  <div className="ml-auto w-1.5 h-1.5 rounded-full bg-primary" />
                )}
              </button>
            );
          })}

          {/* Divider */}
          <div className="my-2 h-px bg-gray-100" />

          {/* Orders — external link, not a tab */}
          <Link
            to="/orders"
            className="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-dark transition-all"
          >
            <ShoppingBag className="w-4 h-4 shrink-0 text-gray-400" />
            <div>
              <p className="text-sm font-semibold text-dark leading-none">
                My Orders
              </p>
              <p className="text-xs text-gray-400 mt-0.5">Order history</p>
            </div>
          </Link>
        </nav>
      </div>
    </aside>
  );
};

export default ProfileSidebar;
