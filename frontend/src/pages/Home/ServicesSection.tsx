import React, { useState, useEffect } from "react";
import {
  Truck,
  RefreshCw,
  Lock,
  Headphones,
  Zap,
  BadgeCheck,
} from "lucide-react";
import LayoutSection from "./LayoutSection";

interface IService {
  icon: React.ReactNode;
  title: string;
  description: string;
  linkText: string;
  gradientBg: string;
  hoverGradient: string;
  features?: string[];
  delay?: number;
}

const services: IService[] = [
  {
    icon: <Truck className="w-10 h-10" />,
    title: "Free & Fast Shipping",
    description:
      "Free shipping on all orders over $50. Express delivery available for urgent needs with real-time tracking.",
    linkText: "Track Order",
    gradientBg: "bg-gradient-to-br from-blue-500 to-purple-600",
    hoverGradient: "from-blue-600 to-purple-700",
    features: ["Free over $50", "2-Day Express", "Real-time Tracking"],
    delay: 0,
  },
  {
    icon: <RefreshCw className="w-10 h-10" />,
    title: "Easy Returns",
    description:
      "Not satisfied? Return products within 30 days for a full refund. Hassle-free process with instant approvals.",
    linkText: "Return Policy",
    gradientBg: "bg-gradient-to-br from-green-500 to-emerald-600",
    hoverGradient: "from-green-600 to-emerald-700",
    features: ["30-Day Window", "Instant Approval", "Free Returns"],
    delay: 100,
  },
  {
    icon: <Lock className="w-10 h-10" />,
    title: "Secure Payment",
    description:
      "Your payment information is encrypted with military-grade security. Multiple payment options available.",
    linkText: "Security Info",
    gradientBg: "bg-gradient-to-br from-amber-500 to-orange-500",
    hoverGradient: "from-amber-600 to-orange-600",
    features: ["256-bit Encryption", "PCI DSS Compliant", "Multiple Methods"],
    delay: 200,
  },
  {
    icon: <Headphones className="w-10 h-10" />,
    title: "24/7 Support",
    description:
      "Our expert customer support team is available round-the-clock via chat, phone, or email.",
    linkText: "Get Help",
    gradientBg: "bg-gradient-to-br from-purple-500 to-pink-500",
    hoverGradient: "from-purple-600 to-pink-600",
    features: ["24/7 Availability", "Multi-channel", "Expert Agents"],
    delay: 300,
  },
];

const Services: React.FC = () => {
  const [isVisible, setIsVisible] = useState(false);

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsVisible(true);
        }
      },
      { threshold: 0.1 },
    );

    const element = document.getElementById("services-section");
    if (element) observer.observe(element);

    return () => observer.disconnect();
  }, []);

  return (
    <LayoutSection
      id="services-section"
      label="Premium Services"
      title="Why Choose"
      titleHighlight="Us"
      subTitle="Experience unparalleled service with our comprehensive suite of features designed to make your shopping journey seamless and secure"
      Icon={BadgeCheck}
    >
      {/* Enhanced Services Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
        {services.map((service, index) => (
          <div
            key={index}
            className={`relative group cursor-pointer [animation:0.8s_ease-out_${service.delay}ms_both_fade-in-up]`}
            style={{
              animationPlayState: isVisible ? "running" : "paused",
            }}
          >
            {/* Main Card */}
            <div className="relative bg-white rounded-2xl p-8 shadow-xl border border-gray-100 transition-all duration-500 hover:shadow-2xl group-hover:-translate-y-4 h-full flex flex-col">
              {/* Hover Gradient Overlay */}
              <div
                className={`absolute inset-0 bg-linear-to-br ${service.hoverGradient} opacity-0 group-hover:opacity-5 rounded-2xl transition-opacity duration-500`}
              ></div>

              {/* Animated Border */}
              <div
                className={`absolute inset-0 rounded-2xl bg-linear-to-r ${service.gradientBg} opacity-0 group-hover:opacity-100 transition-opacity duration-500 -z-10`}
              >
                <div className="absolute inset-0.5 bg-white rounded-2xl"></div>
              </div>

              {/* Icon Container */}
              <div
                className={`relative mb-6 ${service.gradientBg} w-20 h-20 rounded-2xl flex items-center justify-center mx-auto transition-all duration-500 group-hover:scale-110 group-hover:rounded-3xl shadow-lg`}
              >
                <div className="text-white animate-[2s_ease-in-out_infinite_bounce-subtle]">
                  {service.icon}
                </div>

                {/* Floating Particles */}
                <div className="absolute -top-1 -right-1 w-4 h-4 bg-white/30 rounded-full animate-[2s_ease-in-out_infinite_bounce-subtle]"></div>
                <div className="absolute -bottom-1 -left-1 w-3 h-3 bg-white/20 rounded-full animate-[4s_ease-in-out_infinite_float_reverse]"></div>
              </div>

              {/* Content */}
              <div className="flex-1 flex flex-col">
                <h3 className="text-2xl font-bold text-gray-900 mb-4 text-center group-hover:text-gray-800 transition-colors">
                  {service.title}
                </h3>

                <p className="text-gray-600 mb-6 leading-relaxed flex-1">
                  {service.description}
                </p>

                {/* Features List */}
                <div className="space-y-2">
                  {service.features?.map((feature, featureIndex) => (
                    <div
                      key={featureIndex}
                      className="flex items-center gap-2 text-sm text-gray-500 [animation:0.6s_ease-out_${featureIndex * 100 + 500}ms_both_fade-in-up]"
                    >
                      <div
                        className={`w-1.5 h-1.5 rounded-full ${service.gradientBg.replace(
                          "bg-linear-to-br",
                          "bg-linear-to-r",
                        )}`}
                      ></div>
                      {feature}
                    </div>
                  ))}
                </div>
              </div>

              {/* Pulse Effect on Hover */}
              <div
                className={`absolute inset-0 rounded-2xl ${service.gradientBg} opacity-0 group-hover:opacity-10 transition-opacity duration-500 -z-10 animate-[2s_ease-in-out_infinite_pulse-gentle]`}
              ></div>
            </div>

            {/* Background Glow Effect */}
            <div
              className={`absolute inset-0 ${service.gradientBg} rounded-2xl blur-xl opacity-0 group-hover:opacity-20 transition-opacity duration-500 -z-20`}
            ></div>
          </div>
        ))}
      </div>

      {/* Bottom CTA */}
      <div className="text-center mt-16 animate-[0.8s_ease-out_0.5s_both_fade-in-up]">
        <div className="inline-flex flex-col sm:flex-row items-center gap-6 bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
          <div className="flex items-center gap-3">
            <div className="w-12 h-12 bg-linear-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
              <Zap className="w-6 h-6 text-white" />
            </div>
            <div className="text-left">
              <h4 className="font-bold text-gray-900">
                Need Immediate Assistance?
              </h4>
              <p className="text-gray-600 text-sm">
                Our team is ready to help you 24/7
              </p>
            </div>
          </div>
          <button className="bg-linear-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 hover:scale-105 flex items-center gap-2">
            <Headphones className="w-5 h-5" />
            Contact Support
          </button>
        </div>
      </div>
    </LayoutSection>
  );
};

export default Services;
