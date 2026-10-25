import AuthLayout from "../../components/auth/AuthLayout";
import AuthLeftPanel from "../../components/auth/AuthLeftPanel";
import { useLocation, useNavigate } from "react-router-dom";
import VerifyOtpForm from "../../components/auth/VerifyOtpForm";
import { passwordAPI } from "../../api/modules/Auth/password.api";
import { useEffect, useState } from "react";
import { AUTH_CONTENT } from "../../data/auth-content";
import type { IVerfiyOpt } from "../../interface";

const ForgotPasswordPage = () => {
  const navigate = useNavigate();
  const location = useLocation();
  const [isLoading, setIsLoading] = useState<boolean>(false);

  const content = AUTH_CONTENT.verifyOtp;

  const { email } = (location.state ?? {}) as { email?: string };

  useEffect(() => {
    if (!email) {
      navigate("/register", { replace: true });
    }
  }, [email, navigate]);

  const handleVerifySuccess = () => {
    navigate("/reset-password", {
      state: { email },
      replace: true,
    });
  };

  const handleVerify = async (data: IVerfiyOpt) => {
    setIsLoading(true);
    try {
      await passwordAPI.verifyOtp(data);
      handleVerifySuccess();
    } catch (err) {
    } finally {
      setIsLoading(isLoading);
    }
  };

  return (
    <AuthLayout
      left={<AuthLeftPanel {...content} />}
      right={
        <VerifyOtpForm
          email={email as string}
          isLoadingRegister={isLoading}
          verifyOtpRegister={handleVerify}
        />
      }
    />
  );
};
export default ForgotPasswordPage;
