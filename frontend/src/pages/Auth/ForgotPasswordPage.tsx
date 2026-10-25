import AuthLayout from "../../components/auth/AuthLayout";
import AuthLeftPanel from "../../components/auth/AuthLeftPanel";
import ForgotPasswordForm from "../../components/auth/ForgotPasswordForm";
import { AUTH_CONTENT } from "../../data/auth-content";

const ForgotPasswordPage = () => {
  const content = AUTH_CONTENT.forgotPassword;
  return (
    <AuthLayout
      left={<AuthLeftPanel {...content} />}
      right={<ForgotPasswordForm />}
    />
  );
};

export default ForgotPasswordPage;
