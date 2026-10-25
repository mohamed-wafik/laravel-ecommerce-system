import AuthLayout from "../../components/auth/AuthLayout";
import AuthLeftPanel from "../../components/auth/AuthLeftPanel";
import LoginForm from "../../components/auth/LoginForm";
import { AUTH_CONTENT } from "../../data/auth-content";

const LoginPage = () => {
  const content = AUTH_CONTENT.login;

  return (
    <AuthLayout left={<AuthLeftPanel {...content} />} right={<LoginForm />} />
  );
};

export default LoginPage;
