import Categories from "./Categories";
import HeroSlider from "./HeroSlider";
import Services from "./ServicesSection";
import WhyShopSection from "./WhyShopSection";
import TopDealsToday from "./TopDealsToday";

const HomePage = () => {
  return (
    <div>
      <Categories />
      <HeroSlider />
      <Services />
      <TopDealsToday />
      <WhyShopSection />
    </div>
  );
};

export default HomePage;
