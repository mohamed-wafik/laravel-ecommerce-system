import type { ReactNode } from "react";

import ProtectedRoute from "./ProtectRoute";
import { useAuthStore } from "../../store/useAuth";
import LoadingPage from "../ui/loadingPage";

interface IProps {
  children: ReactNode;
}

const RequireGuest = ({ children }: IProps) => {
  const { user, isLoadingCheck } = useAuthStore();

  if (isLoadingCheck) {
    return <LoadingPage />;
  }

  return (
    <ProtectedRoute redirectPath="/" isAllowed={!user}>
      {children}
    </ProtectedRoute>
  );
};

export default RequireGuest;
