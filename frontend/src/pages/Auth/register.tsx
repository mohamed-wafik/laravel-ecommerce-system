import AuthLayout from "../../components/auth/AuthLayout";
import AuthLeftPanel from "../../components/auth/AuthLeftPanel";
import RegisterForm from "../../components/auth/registerForm";
import { AUTH_CONTENT } from "../../data/auth-content";

const RegisterPage = () => {
  const content = AUTH_CONTENT.register;
  return (
    <AuthLayout
      left={<AuthLeftPanel {...content} />}
      right={<RegisterForm />}
    />
  );
};

export default RegisterPage;
