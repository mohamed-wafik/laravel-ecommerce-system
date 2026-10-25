import {
  LayoutDashboard,
  Package,
  ShoppingCart,
  FolderTree,
  Users,
  Settings,
  X,
  CreditCard,
  Tags,
  LifeBuoy,
  Star,
} from "lucide-react";
import NavSection from "./NavSection";
import Logout from "../Logout";

interface IProps {
  open?: boolean;
  onClose?: () => void;
}

const overviewItems = [
  {
    label: "Dashboard",
    path: "/admin",
    icon: LayoutDashboard,
  },
  {
    label: "Products",
    path: "/admin/products",
    icon: Package,
  },
  {
    label: "Orders",
    path: "/admin/orders",
    icon: ShoppingCart,
  },
  {
    label: "Payments",
    path: "/admin/payments",
    icon: CreditCard,
  },
  {
    label: "Reviews",
    path: "/admin/reviews",
    icon: Star,
  },
];

const managementItems = [
  {
    label: "Categories",
    path: "/admin/categories",
    icon: FolderTree,
  },
  {
    label: "Brands",
    path: "/admin/brands",
    icon: Tags,
  },
  {
    label: "Users",
    path: "/admin/users",
    icon: Users,
  },
  {
    label: "Support",
    path: "/admin/support",
    icon: LifeBuoy,
  },
  {
    label: "Settings",
    path: "/admin/settings",
    icon: Settings,
  },
];
const AdminAside = ({ open = true, onClose }: IProps) => {
  return (
    <>
      {/* Mobile Overlay */}
      {open && (
        <button
          aria-label="Close sidebar"
          onClick={onClose}
          className="fixed inset-0 z-40 bg-dark/30 backdrop-blur-sm lg:hidden"
        />
      )}

      <aside
        className={`
          fixed inset-y-0 start-0 z-50
          flex w-64 flex-col
          border-e border-border
          bg-bg-primary
          shadow-xl shadow-dark/5
          transition-transform duration-300 ease-out
          lg:z-auto lg:translate-x-0 lg:shadow-none
          ${open ? "translate-x-0" : "-translate-x-full"}
        `}
      >
        {/* ========================= */}
        {/* Logo */}
        {/* ========================= */}
        <div className="flex h-20 shrink-0 items-center justify-between border-b border-border px-5">
          <div className="flex items-center gap-3">
            {/* Logo */}
            <div
              className="
                flex h-10 w-10 shrink-0 items-center justify-center
                rounded-xl
                bg-primary
                text-white
                shadow-lg shadow-primary/20
              "
            >
              <span className="text-lg font-bold">A</span>
            </div>

            {/* Brand */}
            <div className="min-w-0">
              <h1 className="truncate text-sm font-bold text-text-primary">
                Admin Panel
              </h1>

              <p className="truncate text-xs text-text-tertiary">Management</p>
            </div>
          </div>

          {/* Mobile close */}
          <button
            type="button"
            onClick={onClose}
            className="
              rounded-lg p-2
              text-text-tertiary
              transition-colors
              hover:bg-bg-tertiary
              hover:text-text-primary
              lg:hidden
            "
          >
            <X size={18} />
          </button>
        </div>

        {/* ========================= */}
        {/* Navigation */}
        {/* ========================= */}
        <nav className="flex-1 overflow-y-auto px-3 py-6">
          {/* Overview */}
          <NavSection
            title="Overview"
            items={overviewItems}
            onClose={onClose}
          />

          {/* Management */}
          <NavSection
            title="Management"
            items={managementItems}
            onClose={onClose}
          />
        </nav>

        {/* ========================= */}
        {/* Bottom Actions */}
        {/* ========================= */}
        <div className="shrink-0 border-t border-border p-3 flex flex-col gap-2.5">
          {/* Logout */}
          <Logout />
        </div>
      </aside>
    </>
  );
};

export default AdminAside;
