import { useRef } from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation } from "swiper/modules";
import type { Swiper as SwiperType } from "swiper";
import type { IProduct } from "../interface";

import "swiper/css";
import ProductCard from "./ProductCard";

interface IProps {
  products: IProduct[];
  Count: number;
}

const SliderProducts = ({ products, Count }: IProps) => {
  const swiperRef = useRef<SwiperType | null>(null);

  return (
    <div className="relative">
      <Swiper
        modules={[Navigation]}
        onSwiper={(swiper) => (swiperRef.current = swiper)}
        spaceBetween={0}
        breakpoints={{
          0: { slidesPerView: 1 },
          640: { slidesPerView: 2 },
          1024: { slidesPerView: Count },
        }}
        speed={500}
      >
        {products.map((product) => (
          <SwiperSlide key={product.id}>
            <ProductCard product={product} />
          </SwiperSlide>
        ))}
      </Swiper>

      {/* Prev Button */}
      <button
        onClick={() => swiperRef.current?.slidePrev()}
        className="absolute top-1/2 -left-3 -translate-y-1/2 z-10
          bg-white shadow-lg w-10 h-10 rounded-full
          flex justify-center items-center text-lg font-bold
          hover:bg-gray-200 transition"
      >
        ❮
      </button>

      {/* Next Button */}
      <button
        onClick={() => swiperRef.current?.slideNext()}
        className="absolute top-1/2 -right-3 -translate-y-1/2 z-10
          bg-white shadow-lg w-10 h-10 rounded-full
          flex justify-center items-center text-lg font-bold
          hover:bg-gray-200 transition"
      >
        ❯
      </button>
    </div>
  );
};

export default SliderProducts;
