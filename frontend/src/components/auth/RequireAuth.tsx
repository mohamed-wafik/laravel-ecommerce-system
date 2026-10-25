import type { ReactNode } from "react";

import ProtectedRoute from "./ProtectRoute";
import { useAuthStore } from "../../store/useAuth";

import LoadingPage from "../ui/loadingPage";

interface IProps {
  children: ReactNode;
}

const RequireAuth = ({ children }: IProps) => {
  const { user, isLoadingCheck } = useAuthStore();

  if (isLoadingCheck) {
    return <LoadingPage />;
  }

  return (
    <ProtectedRoute redirectPath="/login" isAllowed={Boolean(user)}>
      {children}
    </ProtectedRoute>
  );
};

export default RequireAuth;
