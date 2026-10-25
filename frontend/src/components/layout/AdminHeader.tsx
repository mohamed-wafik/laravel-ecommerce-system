import {
  Menu,
  Search,
  Bell,
  ChevronDown,
  User,
  Settings,
  LogOut,
  Home,
} from "lucide-react";
import { useState } from "react";
import Logout from "../Logout";
import { useAuthStore } from "../../store/useAuth";
import Image from "../ui/Image";
import { Link } from "react-router-dom";

interface IProps {
  onMenuClick?: () => void;
}

const AdminHeader = ({ onMenuClick }: IProps) => {
  const { user } = useAuthStore();
  const [profileOpen, setProfileOpen] = useState(false);

  return (
    <header
      className="
        sticky top-0 z-30
        h-16 shrink-0
        border-b border-border
        bg-bg-primary/95
        backdrop-blur-xl
      "
    >
      <div className="flex h-full items-center justify-between px-4 md:px-6">
        {/* ========================= */}
        {/* Left */}
        {/* ========================= */}
        <div className="flex min-w-0 items-center gap-3">
          {/* Mobile Menu */}
          <button
            type="button"
            onClick={onMenuClick}
            aria-label="Open navigation"
            className="
              rounded-xl p-2
              text-text-secondary
              transition-all duration-200
              hover:bg-bg-secondary
              hover:text-primary
              lg:hidden
            "
          >
            <Menu size={21} />
          </button>

          {/* Page Title */}
          <div className="hidden sm:block">
            <h1 className="text-sm font-semibold text-text-primary">
              Dashboard
            </h1>

            <p className="mt-0.5 text-xs text-text-tertiary">
              Overview &amp; statistics
            </p>
          </div>
        </div>

        {/* ========================= */}
        {/* Right */}
        {/* ========================= */}
        <div className="flex items-center gap-2 md:gap-3">
          {/* Search */}
          {/* <button
            type="button"
            aria-label="Search"
            className="
              hidden items-center gap-2
              rounded-xl border border-border
              bg-bg-secondary
              px-3 py-2
              text-text-tertiary
              transition-all duration-200
              hover:border-primary-100
              hover:text-primary
              md:flex
            "
          >
            <Search size={17} />

            <span className="text-xs">Search</span>

            <kbd
              className="
                rounded-md border border-border
                bg-bg-primary
                px-1.5 py-0.5
                text-[10px]
                text-text-tertiary
              "
            >
              Ctrl K
            </kbd>
          </button> */}

          {/* Mobile Search */}
          {/* <button
            type="button"
            aria-label="Search"
            className="
              rounded-xl p-2
              text-text-secondary
              transition-all duration-200
              hover:bg-bg-secondary
              hover:text-primary
              md:hidden
            "
          >
            <Search size={19} />
          </button> */}

          {/* Divider */}
          {/* <div className="mx-1 hidden h-7 w-px bg-border md:block" /> */}

          {/* Notifications */}
          {/* <button
            type="button"
            aria-label="Notifications"
            className="
              relative rounded-xl p-2.5
              text-text-secondary
              transition-all duration-200
              hover:bg-bg-secondary
              hover:text-primary
            "
          >
            <Bell size={19} />

            <span
              className="
                absolute end-2 top-2
                h-2 w-2
                rounded-full
                bg-error
                ring-2 ring-bg-primary
              "
            />
          </button> */}

          {/* Divider */}
          {/* <div className="mx-1 h-7 w-px bg-border" /> */}

          {/* ========================= */}
          {/* Profile */}
          {/* ========================= */}
          <div className="relative">
            <button
              type="button"
              onClick={() => setProfileOpen((prev) => !prev)}
              className="
                group flex items-center gap-2
                rounded-xl p-1.5
                transition-all duration-200
                hover:bg-bg-secondary
              "
            >
              {/* Avatar */}
              {user?.avatar_url ? (
                <Image
                  src={user.avatar_url}
                  alt={user.name}
                  className="h-9 w-9 rounded-xl object-cover"
                />
              ) : (
                <div
                  className="
      flex h-9 w-9 items-center justify-center
      rounded-xl bg-primary
      text-white shadow-md shadow-primary/20
    "
                >
                  <span className="text-sm font-bold">
                    {user?.name?.charAt(0).toUpperCase() ?? "U"}
                  </span>
                </div>
              )}

              {/* User Info */}
              <div className="hidden text-start lg:block">
                <p className="text-xs font-semibold text-text-primary">
                  {user?.name}
                </p>

                <p className="text-[10px] text-text-tertiary">{user?.role}</p>
              </div>

              <ChevronDown
                size={15}
                className={`
                  hidden text-text-tertiary
                  transition-transform duration-200
                  lg:block
                  ${profileOpen ? "rotate-180" : ""}
                `}
              />
            </button>

            {/* ========================= */}
            {/* Profile Dropdown */}
            {/* ========================= */}
            {profileOpen && (
              <div
                className="
                  absolute end-0 top-full mt-2
                  w-56
                  overflow-hidden
                  rounded-2xl
                  border border-border
                  bg-bg-primary
                  p-2
                  shadow-xl shadow-dark/10
                  animate-[fadeInUp_0.2s_ease-out]
                "
              >
                {/* User */}
                <div
                  className="
                    mb-2 flex items-center gap-3
                    rounded-xl
                    bg-bg-secondary
                    p-3
                  "
                >
                  {user?.avatar_url ? (
                    <Image
                      src={user.avatar_url}
                      alt={user.name}
                      className="h-9 w-9 rounded-xl object-cover"
                    />
                  ) : (
                    <div
                      className="
      flex h-9 w-9 items-center justify-center
      rounded-xl bg-primary
      text-white shadow-md shadow-primary/20
    "
                    >
                      <span className="text-sm font-bold">
                        {user?.name?.charAt(0).toUpperCase() ?? "U"}
                      </span>
                    </div>
                  )}

                  <div className="min-w-0">
                    <p className="truncate text-xs font-semibold text-text-primary">
                      {user?.name}
                    </p>

                    <p className="truncate text-[10px] text-text-tertiary">
                      {user?.email}
                    </p>
                  </div>
                </div>

                {/* Home */}
                <Link
                  to="/"
                  className="
                    flex w-full items-center gap-3
                    rounded-xl px-3 py-2.5
                    text-sm text-text-secondary
                    transition-colors
                    hover:bg-bg-secondary
                    hover:text-text-primary
                  "
                >
                  <Home size={17} />
                  <span>Home</span>
                </Link>
                {/* Profile */}
                <Link
                  to="/profile"
                  className="
                    flex w-full items-center gap-3
                    rounded-xl px-3 py-2.5
                    text-sm text-text-secondary
                    transition-colors
                    hover:bg-bg-secondary
                    hover:text-text-primary
                  "
                >
                  <User size={17} />
                  <span>Profile</span>
                </Link>

                {/* Settings */}
                <Link
                  to="/admin/settings"
                  className="
                    flex w-full items-center gap-3
                    rounded-xl px-3 py-2.5
                    text-sm text-text-secondary
                    transition-colors
                    hover:bg-bg-secondary
                    hover:text-text-primary
                  "
                >
                  <Settings size={17} />
                  <span>Settings</span>
                </Link>

                <div className="my-2 h-px bg-border" />

                {/* Logout */}
                <Logout />
              </div>
            )}
          </div>
        </div>
      </div>
    </header>
  );
};

export default AdminHeader;
