import React, { useRef } from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Autoplay, Pagination } from "swiper/modules";
import type { Swiper as SwiperType } from "swiper";
import { ChevronLeft, ChevronRight, ArrowRight } from "lucide-react";
import { Link } from "react-router-dom";

import "swiper/css";
import "swiper/css/pagination";

interface ISlide {
  title: string;
  description: string;
  buttonText: string;
  secondaryButtonText: string;
  url: string;
  secondaryUrl: string;
  gradient: string;
  image: string;
  badge: string;
}

const slides: ISlide[] = [
  {
    badge: "Limited Time Offer",
    title: "Summer Sale Up To 50% Off",
    description:
      "Discover incredible deals across top brands and save more on your favorite products.",
    buttonText: "Shop Now",
    secondaryButtonText: "View Offers",
    url: "/shop?discount=true",
    secondaryUrl: "/shop?discount=true",
    gradient: "from-primary to-secondary",
    image: "🛍️",
  },
  {
    badge: "Just Arrived",
    title: "New Collection Just Arrived",
    description:
      "Explore the latest products and discover fresh styles selected for every season.",
    buttonText: "Explore Collection",
    secondaryButtonText: "View Products",
    url: "/shop?sort=newest",
    secondaryUrl: "/shop",
    gradient: "from-success-dark to-info-dark",
    image: "🌟",
  },
  {
    badge: "Free Delivery",
    title: "Free Shipping On Orders Over $50",
    description:
      "Enjoy fast and reliable delivery while shopping with confidence and convenience.",
    buttonText: "Start Shopping",
    secondaryButtonText: "Shipping Info",
    url: "/shop",
    secondaryUrl: "/shop",
    gradient: "from-error to-secondary",
    image: "🚚",
  },
  {
    badge: "Member Benefits",
    title: "Exclusive Member Benefits",
    description:
      "Unlock special discounts, early product releases, and exclusive rewards when you join us.",
    buttonText: "Join Now",
    secondaryButtonText: "Learn More",
    url: "/register",
    secondaryUrl: "/contact",
    gradient: "from-accent-dark to-warning",
    image: "👑",
  },
];

const HeroSlider: React.FC = () => {
  const swiperRef = useRef<SwiperType | null>(null);

  return (
    <section className="container mx-auto px-3 sm:px-4">
      <div className="group relative mx-auto w-full overflow-hidden rounded-3xl shadow-2xl">
        <Swiper
          modules={[Autoplay, Pagination]}
          autoplay={{
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          }}
          pagination={{
            clickable: true,
          }}
          loop
          speed={700}
          onSwiper={(swiper) => {
            swiperRef.current = swiper;
          }}
          className="hero-swiper relative h-[500px] sm:h-[560px] lg:h-[600px]"
        >
          {slides.map((slide, index) => (
            <SwiperSlide key={slide.title}>
              <div
                className={`absolute inset-0 bg-linear-to-br ${slide.gradient}`}
              >
                {/* Background Decorations */}
                <div className="pointer-events-none absolute inset-0 overflow-hidden">
                  <div className="absolute -left-24 -top-24 h-72 w-72 rounded-full bg-white/10" />

                  <div className="absolute -bottom-40 -right-24 h-120 w-120 rounded-full bg-white/10" />

                  <div className="absolute left-1/2 top-1/2 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/5 blur-3xl" />

                  <div className="absolute left-[15%] top-[20%] h-3 w-3 animate-pulse rounded-full bg-white/60" />

                  <div className="absolute right-[20%] top-[30%] h-2 w-2 animate-pulse rounded-full bg-white/70" />

                  <div className="absolute bottom-[20%] left-[40%] h-2.5 w-2.5 animate-pulse rounded-full bg-white/50" />
                </div>

                {/* Content */}
                <div className="relative h-full">
                  <div className="container mx-auto h-full px-8 sm:px-10 lg:px-14">
                    <div className="grid h-full grid-cols-1 items-center gap-10 lg:grid-cols-2 lg:gap-16">
                      {/* Text */}
                      <div
                        className={`space-y-6 text-white ${
                          index % 2 === 0 ? "lg:order-1" : "lg:order-2"
                        } animate-[0.8s_ease-out_0.2s_both_fadeInUp]`}
                      >
                        {/* Badge */}
                        <div className="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold backdrop-blur-md">
                          <span className="h-2 w-2 animate-pulse rounded-full bg-accent-light" />

                          {slide.badge}
                        </div>

                        {/* Heading */}
                        <h1 className="max-w-2xl text-4xl font-black leading-[1.05] tracking-tight sm:text-5xl lg:text-6xl xl:text-7xl">
                          {slide.title}
                        </h1>

                        {/* Description */}
                        <p className="max-w-xl text-base leading-7 text-white/85 sm:text-lg lg:text-xl">
                          {slide.description}
                        </p>

                        {/* Actions */}
                        <div className="flex flex-col gap-3 pt-3 sm:flex-row">
                          {/* Primary */}
                          <Link
                            to={slide.url}
                            className="group/primary inline-flex h-14 items-center justify-center gap-3 rounded-2xl bg-white px-7 font-bold text-primary shadow-xl transition-all duration-300 hover:-translate-y-1 hover:bg-light hover:shadow-2xl"
                          >
                            <span>{slide.buttonText}</span>

                            <ArrowRight className="h-5 w-5 transition-transform duration-300 group-hover/primary:translate-x-1" />
                          </Link>

                          {/* Secondary */}
                          <Link
                            to={slide.secondaryUrl}
                            className="inline-flex h-14 items-center justify-center rounded-2xl border border-white/30 bg-white/10 px-7 font-semibold text-white backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:bg-white/20"
                          >
                            {slide.secondaryButtonText}
                          </Link>
                        </div>
                      </div>

                      {/* Visual */}
                      <div
                        className={`hidden items-center justify-center lg:flex ${
                          index % 2 === 0 ? "lg:order-2" : "lg:order-1"
                        } animate-[0.9s_ease-out_0.35s_both_fadeInUp]`}
                      >
                        <div className="relative">
                          {/* Glow */}
                          <div className="absolute inset-0 scale-75 rounded-full bg-white/20 blur-3xl" />

                          {/* Main Circle */}
                          <div className="relative flex h-64 w-64 items-center justify-center rounded-full border border-white/20 bg-white/10 shadow-2xl backdrop-blur-md transition-transform duration-700 group-hover:scale-105 xl:h-80 xl:w-80">
                            <span className="text-[8rem] drop-shadow-2xl transition-transform duration-700 group-hover:scale-110 xl:text-[10rem]">
                              {slide.image}
                            </span>
                          </div>

                          {/* Floating Badge */}
                          <div className="absolute -right-4 top-8 flex h-14 w-14 animate-[float_4s_ease-in-out_infinite] items-center justify-center rounded-2xl bg-white shadow-xl">
                            <span className="text-2xl">✨</span>
                          </div>

                          {/* Floating Badge */}
                          <div className="absolute -bottom-4 -left-5 flex h-12 w-12 animate-[float-reverse_5s_ease-in-out_infinite] items-center justify-center rounded-2xl bg-accent shadow-xl">
                            <span className="text-xl">✓</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </SwiperSlide>
          ))}
        </Swiper>

        {/* Previous */}
        <button
          type="button"
          aria-label="Previous slide"
          onClick={() => swiperRef.current?.slidePrev()}
          className="absolute left-4 top-1/2 z-20 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-2xl border border-white/20 bg-black/10 text-white opacity-0 backdrop-blur-md transition-all duration-300 hover:scale-105 hover:bg-black/20 group-hover:opacity-100 sm:left-6"
        >
          <ChevronLeft className="h-6 w-6" />
        </button>

        {/* Next */}
        <button
          type="button"
          aria-label="Next slide"
          onClick={() => swiperRef.current?.slideNext()}
          className="absolute right-4 top-1/2 z-20 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-2xl border border-white/20 bg-black/10 text-white opacity-0 backdrop-blur-md transition-all duration-300 hover:scale-105 hover:bg-black/20 group-hover:opacity-100 sm:right-6"
        >
          <ChevronRight className="h-6 w-6" />
        </button>

        {/* Bottom Gradient */}
        <div className="pointer-events-none absolute inset-x-0 bottom-0 z-10 h-32 bg-linear-to-t from-black/20 to-transparent" />
      </div>
    </section>
  );
};

export default HeroSlider;
