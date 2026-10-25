import { Link, useNavigate } from "react-router-dom";
import { LogIn } from "lucide-react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { useAuthStore } from "../../store/useAuth";
import { loginSchema, type LoginInput } from "../../schema/auth.schema";
import FormField from "../ui/FormField";
import PasswordInput from "../../components/auth/PasswordInput";
import SubmitButton from "../../components/auth/SubmitButton";
import Input from "../ui/Input";
const LoginForm = () => {
  const { login, isLoadingLogin } = useAuthStore();
  const navigate = useNavigate();

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<LoginInput>({
    resolver: zodResolver(loginSchema),
    defaultValues: { email: "", password: "" },
  });

  const onSubmit = async (data: LoginInput) => {
    const email = await login(data);
    if (email) {
      navigate("/verify-otp", {
        state: { email, type: "login" },
        replace: true,
      });
    }
  };

  return (
    <div>
      {/* Header */}
      <div className="mb-8">
        <div className="flex items-center gap-2 mb-6 lg:hidden">
          <div className="w-7 h-7 bg-primary rounded-lg flex items-center justify-center">
            <span className="text-white font-black text-xs">S</span>
          </div>
          <span className="text-dark font-bold text-base tracking-tight">
            ShopApp
          </span>
        </div>
        <h1 className="text-2xl font-extrabold text-dark mb-1">Welcome back</h1>
        <p className="text-sm text-gray-500">
          Sign in to continue to your account
        </p>
      </div>

      {/* Form */}
      <form onSubmit={handleSubmit(onSubmit)} noValidate className="space-y-3">
        <FormField
          htmlFor="email"
          label="Email address"
          error={errors.email?.message}
        >
          <Input
            id="email"
            type="email"
            placeholder="you@example.com"
            autoComplete="email"
            variant={errors.email ? "error" : "default"}
            {...register("email")}
          />
        </FormField>

        <div>
          <PasswordInput
            id="password"
            label="Password"
            placeholder="••••••••"
            autoComplete="current-password"
            error={errors.password?.message}
            {...register("password")}
          />
          <div className="flex justify-end mt-2 mb-2">
            <Link
              to="/forget-password"
              className="text-xs text-primary hover:underline font-medium"
            >
              Forgot password?
            </Link>
          </div>
        </div>

        <SubmitButton loading={isLoadingLogin} loadingText="Signing in…">
          <LogIn className="w-4 h-4" />
          Sign in
        </SubmitButton>
      </form>

      {/* Divider */}
      <div className="flex items-center gap-3 my-6">
        <div className="flex-1 h-px bg-gray-200" />
        <span className="text-xs text-gray-400 font-medium">or</span>
        <div className="flex-1 h-px bg-gray-200" />
      </div>

      {/* Footer */}
      <p className="text-center text-sm text-gray-500">
        Don't have an account?{" "}
        <Link
          to="/register"
          className="text-primary font-semibold hover:underline"
        >
          Create one
        </Link>
      </p>
    </div>
  );
};

export default LoginForm;
