import { useEffect } from "react";
import { Toaster } from "react-hot-toast";
import { useAuthStore } from "./store/useAuth";
import { useCart } from "./store/useCart";
import AppRouter from "./Routes";
import { getAccessToken } from "./Service/authTokens";

function App() {
  const { check } = useAuthStore();
  const { fetchCart } = useCart();
  const accessToken = getAccessToken();

  useEffect(() => {
    const initializeApp = async () => {
      // Guest → no requests
      if (!accessToken) {
        return;
      }

      // Token exists → user is authenticated on client
      await Promise.all([check(), fetchCart()]);
    };

    initializeApp();
  }, [check, fetchCart, accessToken]);

  return (
    <>
      <AppRouter />
      <Toaster position="bottom-right" />
    </>
  );
}

export default App;
