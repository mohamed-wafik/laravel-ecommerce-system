import React, { useEffect, useState } from "react";
import {
  ArrowRight,
  CheckCircle2,
  Gift,
  PackageCheck,
  ShieldCheck,
  Sparkles,
  Truck,
  WalletCards,
} from "lucide-react";
import { useNavigate } from "react-router-dom";

import Button from "../../components/ui/Button";
import LayoutSection from "./LayoutSection";

interface IBenefit {
  icon: React.ElementType;
  title: string;
  description: string;
}

const benefits: IBenefit[] = [
  {
    icon: PackageCheck,
    title: "Quality Products",
    description: "Carefully selected products that meet our quality standards.",
  },
  {
    icon: Truck,
    title: "Fast Delivery",
    description:
      "Get your orders delivered quickly and safely to your doorstep.",
  },
  {
    icon: ShieldCheck,
    title: "Secure Shopping",
    description:
      "Your account and payment information are protected at every step.",
  },
  {
    icon: WalletCards,
    title: "Flexible Payments",
    description: "Choose a convenient payment method that works for you.",
  },
];

const WhyShopSection: React.FC = () => {
  const navigate = useNavigate();
  const [isVisible, setIsVisible] = useState(false);

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsVisible(true);
          observer.disconnect();
        }
      },
      { threshold: 0.1 },
    );

    const element = document.getElementById("why-shop-section");

    if (element) {
      observer.observe(element);
    }

    return () => observer.disconnect();
  }, []);

  return (
    <LayoutSection
      id="why-shop-section"
      label="Why Choose Us"
      Icon={Sparkles}
      subTitle="We focus on making every part of your shopping experience convenient, secure, and enjoyable."
      title="Shopping Made"
      titleHighlight="Simple"
    >
      {/* Main Layout */}
      <div className="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
        {/* Benefits */}
        <div
          className="grid gap-4 sm:grid-cols-2 animate-[0.8s_ease-out_150ms_both_fadeInUp]"
          style={{
            animationPlayState: isVisible ? "running" : "paused",
          }}
        >
          {benefits.map((benefit, index) => {
            const Icon = benefit.icon;

            return (
              <div
                key={benefit.title}
                className={`group rounded-3xl border border-border bg-bg-primary p-7 shadow-sm transition-all duration-500 hover:-translate-y-1 hover:border-primary-200 hover:shadow-xl [animation:0.6s_ease-out_${
                  index * 100 + 250
                }ms_both_fadeInUp]`}
                style={{
                  animationPlayState: isVisible ? "running" : "paused",
                }}
              >
                <div className="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-50 transition-all duration-300 group-hover:scale-110 group-hover:bg-primary">
                  <Icon className="h-6 w-6 text-primary transition-colors group-hover:text-white" />
                </div>

                <h3 className="text-lg font-bold text-text-primary">
                  {benefit.title}
                </h3>

                <p className="mt-2 text-sm leading-6 text-text-secondary">
                  {benefit.description}
                </p>
              </div>
            );
          })}
        </div>

        {/* Promotional Card */}
        <div
          className="group relative overflow-hidden rounded-3xl bg-linear-to-br from-primary to-secondary p-8 shadow-xl sm:p-10 animate-[0.8s_ease-out_300ms_both_fadeInUp]"
          style={{
            animationPlayState: isVisible ? "running" : "paused",
          }}
        >
          {/* Decorations */}
          <div className="absolute -right-20 -top-20 h-56 w-56 rounded-full border border-white/10 bg-white/5" />

          <div className="absolute -bottom-24 -left-20 h-64 w-64 rounded-full border border-white/10 bg-white/5" />

          <div className="absolute right-10 top-10 h-3 w-3 rounded-full bg-white/30 animate-[float_3s_ease-in-out_infinite]" />

          <div className="absolute bottom-24 right-20 h-2 w-2 rounded-full bg-white/40 animate-[float-reverse_4s_ease-in-out_infinite]" />

          <div className="relative flex h-full flex-col justify-between">
            {/* Icon */}
            <div className="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15 backdrop-blur-sm">
              <Gift className="h-8 w-8 text-white" />
            </div>

            <div className="mt-12">
              <p className="text-sm font-semibold uppercase tracking-wider text-white/70">
                Your Shopping Experience
              </p>

              <h3 className="mt-3 text-3xl font-bold leading-tight text-white sm:text-4xl">
                Everything you need,
                <br />
                all in one place.
              </h3>

              <p className="mt-5 max-w-md text-sm leading-7 text-white/75">
                Discover products you'll love with a simple shopping experience
                designed around your needs.
              </p>

              {/* Checklist */}
              <div className="mt-7 space-y-3">
                {[
                  "Easy product discovery",
                  "Secure checkout",
                  "Reliable order tracking",
                ].map((item) => (
                  <div
                    key={item}
                    className="flex items-center gap-3 text-sm font-medium text-white"
                  >
                    <CheckCircle2 className="h-5 w-5 shrink-0 text-white/80" />
                    {item}
                  </div>
                ))}
              </div>

              {/* CTA */}
              <div className="mt-8">
                <Button
                  type="button"
                  variant="light"
                  size="md"
                  rounded="lg"
                  width="fit"
                  className="group/btn gap-3"
                  onClick={() => navigate("/products")}
                >
                  <span>Explore Products</span>

                  <ArrowRight className="h-5 w-5 transition-transform duration-300 group-hover/btn:translate-x-1" />
                </Button>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Bottom Stats */}
      <div
        className="mt-6 grid grid-cols-2 gap-4 md:grid-cols-4 animate-[0.8s_ease-out_500ms_both_fadeInUp]"
        style={{
          animationPlayState: isVisible ? "running" : "paused",
        }}
      >
        {[
          { value: "10K+", label: "Happy Customers" },
          { value: "5K+", label: "Products" },
          { value: "99%", label: "Secure Orders" },
          { value: "24/7", label: "Online Shopping" },
        ].map((stat) => (
          <div
            key={stat.label}
            className="rounded-2xl border border-border bg-bg-primary px-5 py-5 text-center shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md"
          >
            <p className="text-xl font-bold text-primary">{stat.value}</p>

            <p className="mt-1 text-xs text-text-tertiary">{stat.label}</p>
          </div>
        ))}
      </div>
    </LayoutSection>
  );
};

export default WhyShopSection;
