import { Outlet } from "react-router-dom";
import Navbar from "../components/layout/Navbar";
import Footer from "../components/layout/Footer";

const RootLayout = () => {
  return (
    <div className="min-h-screen flex flex-col bg-white">
      <Navbar />

      {/* <main className="flex-1 relative"> */}
      <Outlet />
      {/* </main> */}
      <Footer />
    </div>
  );
};

export default RootLayout;
