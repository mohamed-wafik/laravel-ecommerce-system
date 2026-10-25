import {
  ShoppingBag,
  Facebook,
  Twitter,
  Instagram,
  Youtube,
  MapPin,
  Phone,
  Mail,
  ArrowUp,
  Shield,
  Truck,
  Headphones,
  BadgeCheck,
} from "lucide-react";
import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import Image from "../ui/Image";

const Footer = () => {
  const [isVisible, setIsVisible] = useState(false);
  const [currentYear] = useState(new Date().getFullYear());

  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  useEffect(() => {
    const toggleVisibility = () => {
      if (window.pageYOffset > 300) {
        setIsVisible(true);
      } else {
        setIsVisible(false);
      }
    };

    window.addEventListener("scroll", toggleVisibility);
    return () => window.removeEventListener("scroll", toggleVisibility);
  }, []);

  const features = [
    { icon: <Truck className="w-5 h-5" />, text: "Free Shipping Over $50" },
    { icon: <Shield className="w-5 h-5" />, text: "2-Year Warranty" },
    { icon: <Headphones className="w-5 h-5" />, text: "24/7 Support" },
    { icon: <BadgeCheck className="w-5 h-5" />, text: "Authentic Products" },
  ];

  const quickLinks = [
    { name: "Home", href: "#" },
    { name: "Shop", href: "#" },
    { name: "Deals", href: "#" },
    { name: "About Us", href: "#" },
    { name: "Contact", href: "#" },
  ];

  const customerService = [
    { name: "Shipping Policy", href: "#" },
    { name: "Return Policy", href: "#" },
    { name: "Privacy Policy", href: "#" },
    { name: "Terms & Conditions", href: "#" },
    { name: "Track Your Order", href: "#" },
  ];

  const socialLinks = [
    { icon: <Facebook className="w-5 h-5" />, href: "#", name: "Facebook" },
    { icon: <Twitter className="w-5 h-5" />, href: "#", name: "Twitter" },
    { icon: <Instagram className="w-5 h-5" />, href: "#", name: "Instagram" },
    { icon: <Youtube className="w-5 h-5" />, href: "#", name: "YouTube" },
  ];

  const paymentMethods = [
    { name: "Visa", src: "/visa-master-logo-png--1024x256.png" },
    { name: "Mastercard", src: "/OIP.webp" },
    { name: "PayPal", src: "/PayPal-Transparent-Image.png" },
    {
      name: "Apple Pay",
      src: "/apple-pay-logo-png-apple-pay-icon-free-img-png-and-vector-256x256.png",
    },
    {
      name: "Google Pay",
      src: "/pngtree-google-pay-payment-software-vector-png-image_9183298.png",
    },
  ];

  return (
    <footer className="relative bg-gradient-to-b from-gray-900 to-gray-950 text-white overflow-hidden">
      {/* Background Pattern */}
      <div className="absolute inset-0 opacity-5">
        <div className="absolute top-0 left-0 w-96 h-96 bg-blue-500 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div className="absolute bottom-0 right-0 w-80 h-80 bg-purple-500 rounded-full translate-x-1/2 translate-y-1/2"></div>
      </div>

      {/* Features Bar */}
      <div className="border-b border-gray-800">
        <div className="container mx-auto px-4 py-6">
          <div className="grid grid-cols-2 lg:grid-cols-4 gap-6">
            {features.map((feature, index) => (
              <div
                key={index}
                className="flex items-center gap-3 text-gray-300 [animation:0.8s_ease-out_${index * 100}ms_both_fade-in-up]"
              >
                <div className="text-blue-400 [animation:2s_ease-in-out_infinite_bounce-subtle]">
                  {feature.icon}
                </div>
                <span className="text-sm font-medium">{feature.text}</span>
              </div>
            ))}
          </div>
        </div>
      </div>

      <div className="container mx-auto px-4 pt-12 pb-8 relative z-10">
        {/* Main Footer Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
          {/* Brand Section */}
          <div className="lg:col-span-2 [animation:0.8s_ease-out_fade-in-up]">
            <div className="flex items-center mb-6">
              <div className="relative">
                <div className="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-3 [animation:3s_ease-in-out_infinite_pulse-gentle]">
                  <ShoppingBag className="w-6 h-6 text-white" />
                </div>
                <div className="absolute -top-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-gray-900"></div>
              </div>
              <span className="text-2xl font-bold">
                Shop
                <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">
                  Easy
                </span>
              </span>
            </div>

            <p className="text-gray-400 mb-6 text-lg leading-relaxed max-w-md">
              Your trusted partner for quality products and exceptional shopping
              experiences. We bring the best to your doorstep with care and
              dedication.
            </p>

            {/* Social Links */}
            <div className="flex gap-4">
              {socialLinks.map((social, index) => (
                <Link
                  key={index}
                  to={social.href}
                  className="group relative p-3 bg-gray-800 rounded-xl hover:bg-gradient-to-r hover:from-blue-600 hover:to-purple-600 transition-all duration-300 hover:scale-110 hover:shadow-lg"
                  aria-label={social.name}
                >
                  {social.icon}
                  <div className="absolute -bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                    {social.name}
                  </div>
                </Link>
              ))}
            </div>
          </div>

          {/* Quick Links */}
          <div className="[animation:0.8s_ease-out_0.2s_both_fade-in-up]">
            <h3 className="text-xl font-bold mb-6 flex items-center gap-2">
              <div className="w-2 h-2 bg-blue-500 rounded-full [animation:2s_ease-in-out_infinite_ping]"></div>
              Quick Links
            </h3>
            <ul className="space-y-3">
              {quickLinks.map((link, index) => (
                <li key={index}>
                  <Link
                    to={link.href}
                    className="text-gray-400 hover:text-white transition-all duration-300 hover:translate-x-2 hover:underline hover:underline-offset-4 flex items-center gap-2 group"
                  >
                    <div className="w-1.5 h-1.5 bg-blue-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    {link.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Customer Service */}
          <div className="[animation:0.8s_ease-out_0.3s_both_fade-in-up]">
            <h3 className="text-xl font-bold mb-6 flex items-center gap-2">
              <div className="w-2 h-2 bg-green-500 rounded-full [animation:2s_ease-in-out_infinite_ping]"></div>
              Support
            </h3>
            <ul className="space-y-3">
              {customerService.map((service, index) => (
                <li key={index}>
                  <Link
                    to={service.href}
                    className="text-gray-400 hover:text-white transition-all duration-300 hover:translate-x-2 hover:underline hover:underline-offset-4 flex items-center gap-2 group"
                  >
                    <div className="w-1.5 h-1.5 bg-green-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    {service.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact Info */}
          <div className="[animation:0.8s_ease-out_0.4s_both_fade-in-up]">
            <h3 className="text-xl font-bold mb-6 flex items-center gap-2">
              <div className="w-2 h-2 bg-purple-500 rounded-full [animation:2s_ease-in-out_infinite_ping]"></div>
              Contact Us
            </h3>
            <ul className="space-y-4">
              <li className="flex items-start gap-3 group">
                <MapPin className="text-blue-400 w-5 h-5 mt-1 flex-shrink-0 [animation:2s_ease-in-out_infinite_bounce-subtle]" />
                <span className="text-gray-400 group-hover:text-white transition-colors">
                  123 Commerce Street,
                  <br />
                  New York, NY 10001
                </span>
              </li>
              <li className="flex items-center gap-3 group">
                <Phone className="text-green-400 w-5 h-5 flex-shrink-0 [animation:2s_ease-in-out_infinite_bounce-subtle]" />
                <span className="text-gray-400 group-hover:text-white transition-colors">
                  +1 (555) 123-4567
                </span>
              </li>
              <li className="flex items-center gap-3 group">
                <Mail className="text-purple-400 w-5 h-5 flex-shrink-0 [animation:2s_ease-in-out_infinite_bounce-subtle]" />
                <span className="text-gray-400 group-hover:text-white transition-colors">
                  support@shopeasy.com
                </span>
              </li>
            </ul>
          </div>
        </div>

        {/* Bottom Section */}
        <div className="border-t border-gray-800 pt-8 flex flex-col lg:flex-row justify-between items-center gap-6">
          <div className="text-center lg:text-left [animation:0.8s_ease-out_0.5s_both_fade-in-up]">
            <p className="text-gray-400 text-sm">
              © {currentYear}{" "}
              <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400 font-semibold">
                ShopEasy
              </span>
              . All rights reserved.
            </p>
            <p className="text-gray-500 text-xs mt-1">
              Made with ❤️ for amazing shopping experiences
            </p>
          </div>

          {/* Payment Methods */}
          <div className="flex items-center gap-4 [animation:0.8s_ease-out_0.6s_both_fade-in-up]">
            <span className="text-gray-400 text-sm mr-2">We accept:</span>
            <div className="flex gap-3">
              {paymentMethods.map((method, index) => (
                <div
                  key={index}
                  className="w-10 h-6 bg-gray-800 rounded flex items-center justify-center p-1 hover:scale-110 transition-transform duration-300"
                >
                  <Image
                    src={method.src}
                    alt={method.name}
                    className="max-w-full max-h-full object-contain filter brightness-0 invert opacity-70 hover:opacity-100 transition-opacity"
                  />
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Scroll to Top Button */}
      <button
        onClick={scrollToTop}
        className={`fixed bottom-6 right-6 w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white shadow-2xl transition-all duration-300 hover:scale-110 hover:shadow-3xl ${
          isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-10"
        }`}
        aria-label="Scroll to top"
      >
        <ArrowUp className="w-5 h-5" />
      </button>

      {/* Floating Elements */}
      <div className="absolute bottom-20 left-10 w-4 h-4 bg-blue-400 rounded-full opacity-30 [animation:4s_ease-in-out_infinite_float]"></div>
      <div className="absolute top-40 right-20 w-3 h-3 bg-purple-400 rounded-full opacity-30 [animation:5s_ease-in-out_infinite_float_reverse]"></div>
      <div className="absolute bottom-40 left-1/4 w-2 h-2 bg-green-400 rounded-full opacity-30 [animation:6s_ease-in-out_infinite_float_slow]"></div>
    </footer>
  );
};

export default Footer;
