import type { ReactNode } from "react";

import ProtectedRoute from "./ProtectRoute";
import { useAuthStore } from "../../store/useAuth";
import LoadingPage from "../ui/loadingPage";

interface IProps {
  children: ReactNode;
}

const RequireAdmin = ({ children }: IProps) => {
  const { user, isLoadingCheck } = useAuthStore();

  if (isLoadingCheck) {
    return <LoadingPage />;
  }

  const isAdmin = user?.role?.toLowerCase() === "admin";

  return (
    <ProtectedRoute redirectPath="/" isAllowed={isAdmin}>
      {children}
    </ProtectedRoute>
  );
};

export default RequireAdmin;
