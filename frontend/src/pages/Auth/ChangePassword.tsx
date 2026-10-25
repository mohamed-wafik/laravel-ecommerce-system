import AuthLayout from "../../components/auth/AuthLayout";
import AuthLeftPanel from "../../components/auth/AuthLeftPanel";
import ResetPasswordForm from "../../components/auth/ResetPasswordForm";
import { AUTH_CONTENT } from "../../data/auth-content";

const ResetPasswordPage = () => {
  const content = AUTH_CONTENT.resetPassword;
  return (
    <AuthLayout
      left={<AuthLeftPanel {...content} />}
      right={<ResetPasswordForm />}
    />
  );
};

export default ResetPasswordPage;
