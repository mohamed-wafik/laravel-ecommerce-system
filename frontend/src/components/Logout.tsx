import Button from "./ui/Button";
import { useAuthStore } from "../store/useAuth";
import { LogOut } from "lucide-react";

const Logout = () => {
  const { isLoadingLogout, logout } = useAuthStore();
  return (
    <Button
      variant={"destructive"}
      loading={isLoadingLogout}
      width={"full"}
      className="space-x-2"
      onClick={logout}
    >
      <LogOut className="w-4 h-4" />
      <span className="font-medium">Logout</span>
    </Button>
  );
};

export default Logout;
