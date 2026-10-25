import { useEffect, useRef, useState } from "react";
import { useAuthStore } from "../store/useAuth";
import {
  Heart,
  LogOut,
  MapPin,
  User as UserMain,
  User as UserIcon,
  LayoutDashboard,
  ShoppingBag,
} from "lucide-react";
import { Link } from "react-router-dom";
import Button from "./ui/Button";
import Image from "./ui/Image";

const userMenuItems = [
  { icon: <UserIcon className="w-4 h-4" />, name: "Profile", href: "/profile" },
  {
    icon: <ShoppingBag className="w-4 h-4" />,
    name: "Orders",
    href: "/orders",
  },
  { icon: <Heart className="w-4 h-4" />, name: "Wishlist", href: "/wishlist" },
  { icon: <MapPin className="w-4 h-4" />, name: "Addresses", href: "/address" },
];

const UserProfile = () => {
  const [isOpen, setIsOpen] = useState(false);
  const userMenuRef = useRef<HTMLDivElement>(null);
  const { user, userIsLoggedIn, logout, isLoadingLogout } = useAuthStore();

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (
        userMenuRef.current &&
        !userMenuRef.current.contains(event.target as Node)
      ) {
        setIsOpen(false);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  const handlerLogout = () => {
    logout();
  };

  return (
    <div className="relative" ref={userMenuRef}>
      {/* Toggle Button */}
      <button onClick={() => setIsOpen((prev) => !prev)}>
        <UserMain className="text-gray-700 hover:text-primary cursor-pointer transition-colors" />
      </button>

      {isOpen && (
        <div className="absolute z-50 top-full right-1/2 translate-x-1/2 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden animate-[0.3s_ease-out_dropdown-slide]">
          {userIsLoggedIn && user ? (
            <>
              {/* User Info */}
              <div className="p-4 border-b border-gray-100 bg-linear-to-r from-gray-50 to-white">
                <div className="flex items-center gap-3">
                  <Image
                    src={user.avatar_url as string}
                    alt={user.name}
                    className="w-12 h-12 rounded-full border-2 border-blue-500"
                  />
                  <div className="flex-1 min-w-0">
                    <p className="font-semibold text-gray-900 truncate">
                      {user.name}
                    </p>
                    <p className="text-sm text-gray-500 truncate">
                      {user.email}
                    </p>
                  </div>
                </div>
              </div>

              {/* Menu Items */}
              <div className="p-2">
                {user.role.toLocaleLowerCase() === "admin" && (
                  <Link
                    to={"/admin"}
                    className="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-primary transition-all duration-200 group"
                  >
                    <div className="text-gray-400 group-hover:text-secondary transition-colors">
                      <LayoutDashboard className="w-4 h-4" />
                    </div>
                    <span className="font-medium">Dashboard</span>
                  </Link>
                )}
                {userMenuItems.map((item, index) => (
                  <Link
                    key={index}
                    to={item.href}
                    className="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-primary transition-all duration-200 group"
                  >
                    <div className="text-gray-400 group-hover:text-secondary transition-colors">
                      {item.icon}
                    </div>
                    <span className="font-medium">{item.name}</span>
                  </Link>
                ))}
              </div>

              {/* Logout */}
              <div className="p-2 border-t border-gray-100">
                <Button
                  variant={"destructive"}
                  loading={isLoadingLogout}
                  width={"full"}
                  className="space-x-2"
                  onClick={handlerLogout}
                >
                  <LogOut className="w-4 h-4" />
                  <span className="font-medium">Logout</span>
                </Button>
              </div>
            </>
          ) : (
            /* Not logged in view */
            <div className="p-4 text-center">
              <p className="text-gray-700 mb-2 font-medium">
                You are not logged in
              </p>
              <Link
                to="/login"
                className="text-primary hover:underline font-semibold"
              >
                Go to Login
              </Link>
            </div>
          )}
        </div>
      )}
    </div>
  );
};

export default UserProfile;
