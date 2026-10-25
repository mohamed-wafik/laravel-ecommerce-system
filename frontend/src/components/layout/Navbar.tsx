import {
  Phone,
  Mail,
  ShoppingBag,
  Heart,
  ShoppingCart,
  Menu,
} from "lucide-react";
import { useEffect, useRef, useState } from "react";
import { Link, NavLink } from "react-router-dom";
import { useCart } from "../../store/useCart";
import UserProfile from "../UserPorfile";
import SearchInput from "../SearchInput";
import useWishlist from "../../store/useWishlist";
const navItems = [
  { path: "/", label: "Home" },
  { path: "/shop", label: "Shop" },
  { path: "/Categories", label: "Categories" },
  { path: "/brands", label: "Brands" },
  { path: "/contact", label: "Contact" },
];
// Define links array
const helpLinks = [
  { path: "#", label: "Track Order" },
  { path: "#", label: "FAQ" },
  { path: "#", label: "Help" },
];

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const { countItems, isLoadingCart } = useCart();
  const { items } = useWishlist();
  const mobileRef = useRef<HTMLElement | null>(null);

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (
        mobileRef.current &&
        !mobileRef.current.contains(event.target as Node)
      ) {
        console.log(mobileRef.current.contains(event.target as Node));
        setIsOpen(false);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);

    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  return (
    <>
      {/* Top Bar */}
      <div className="bg-dark text-white py-2 text-sm">
        <div className="container mx-auto px-4 flex justify-between items-center">
          <div className="flex space-x-4">
            <span className="flex items-center">
              <Phone size={14} className="mr-1" /> +1 (555) 123-4567
            </span>
            <span className="flex items-center">
              <Mail size={14} className="mr-1" /> support@shopeasy.com
            </span>
          </div>

          <div className="hidden md:flex space-x-4">
            {helpLinks.map((link, index) => (
              <Link
                key={index}
                to={link.path}
                className="hover:text-accent transition-colors"
              >
                {link.label}
              </Link>
            ))}
          </div>
        </div>
      </div>

      {/* Header */}
      <header className="bg-white shadow-md sticky top-0 z-50">
        <div className="container mx-auto px-4 py-4">
          <div className="flex justify-between items-center">
            {/* Logo */}
            <div className="flex items-center">
              <ShoppingBag className="text-primary w-8 h-8 mr-2" />
              <span className="text-2xl font-bold text-gray-800">
                Shop<span className="text-primary">Easy</span>
              </span>
            </div>

            {/* Search Bar */}
            <div className="hidden md:flex flex-1 max-w-2xl mx-8">
              <SearchInput />
            </div>

            {/* User Actions */}
            <div className="flex items-center space-x-6">
              <UserProfile />
              {/* <User className="text-gray-700 hover:text-primary cursor-pointer transition-colors" /> */}
              <Link to="/wishlist">
                <div className="relative">
                  <Heart className="text-gray-700 hover:text-primary cursor-pointer transition-colors" />
                  {items.length > 0 && (
                    <span className="absolute -top-2 -right-2 bg-accent text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                      {items.length}
                    </span>
                  )}
                </div>
              </Link>
              <div className="relative">
                <Link to="/cart">
                  <ShoppingCart className="text-gray-700 hover:text-primary cursor-pointer transition-colors" />
                  {isLoadingCart ? (
                    <span className="absolute -top-2 -right-2 rounded-full w-5 h-5 bg-gray-200 animate-pulse"></span>
                  ) : (
                    <span className="absolute -top-2 -right-2 bg-accent text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                      {countItems}
                    </span>
                  )}
                </Link>
              </div>
            </div>

            {/* Mobile Menu Button */}
            <button
              className="md:hidden p-2 text-gray-700 hover:text-primary transition"
              onClick={() => setIsOpen(!isOpen)}
            >
              <Menu size={28} />
            </button>
          </div>

          {/* Mobile Search */}
          <div className="md:hidden mt-3">
            <SearchInput />
          </div>

          {/* Navigation */}
          <nav
            className={`mt-4 ${isOpen ? "block" : "hidden"} md:block`}
            // ref={mobileRef}
          >
            <ul className="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-8">
              {navItems.map((item) => (
                <li key={item.path}>
                  <NavLink
                    to={item.path}
                    className="text-gray-700 hover:text-primary font-medium"
                  >
                    {item.label}
                  </NavLink>
                </li>
              ))}
            </ul>
          </nav>
        </div>
      </header>
    </>
  );
};

export default Navbar;
