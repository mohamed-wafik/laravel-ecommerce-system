import AuthLayout from "../../components/auth/AuthLayout";
import AuthLeftPanel from "../../components/auth/AuthLeftPanel";
import VerifyOtpForm from "../../components/auth/VerifyOtpForm";
import { useAuthStore } from "../../store/useAuth";
import { AUTH_CONTENT } from "../../data/auth-content";
import { useEffect } from "react";
import toast from "react-hot-toast";
import { useLocation, useNavigate } from "react-router-dom";

const VerifyOtpPage = () => {
  const navigate = useNavigate();
  const location = useLocation();
  const { verifyOtpRegister, isLoadingRegister } = useAuthStore();
  const content = AUTH_CONTENT.verifyOtpReg;
  const { email, type } = (location.state ?? {}) as {
    email?: string;
    type?: string;
  };

  useEffect(() => {
    if (!email || !type) {
      toast.error("Please verify your code before resetting your password.");
      navigate("/register");
    }
  }, [email, type, navigate]);

  return (
    <AuthLayout
      left={<AuthLeftPanel {...content} />}
      right={
        <VerifyOtpForm
          email={email as string}
          type={type as string}
          isLoadingRegister={isLoadingRegister}
          verifyOtpRegister={verifyOtpRegister}
        />
      }
    />
  );
};

export default VerifyOtpPage;
