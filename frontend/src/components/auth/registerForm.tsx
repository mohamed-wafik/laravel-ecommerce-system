import { Link, useNavigate } from "react-router-dom";
import { UserPlus, Check } from "lucide-react";
import { useForm, useWatch } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { registerSchema, type RegisterInput } from "../../schema/auth.schema";
import { useAuthStore } from "../../store/useAuth";
import FormField from "../ui/FormField";
import PasswordInput from "../../components/auth/PasswordInput";
import SubmitButton from "../../components/auth/SubmitButton";
import PasswordStrength from "./PasswordStrength";
import Input from "../ui/Input";

// ── Component ─────────────────────────────────────────────────────────────────
const RegisterForm = () => {
  const { register: registerUser, isLoadingRegister } = useAuthStore();

  const {
    register,
    handleSubmit,
    control,
    formState: { errors },
  } = useForm<RegisterInput>({
    resolver: zodResolver(registerSchema),
    defaultValues: {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
    },
  });

  const navigate = useNavigate();

  // Live-watch both password fields to drive the strength bar and match icon
  // without triggering re-renders on every other field
  const password = useWatch({ control, name: "password" });
  const passwordConfirmation = useWatch({
    control,
    name: "password_confirmation",
  });
  const passwordsMatch =
    !!password && !!passwordConfirmation && password === passwordConfirmation;

  const onSubmit = async (data: RegisterInput) => {
    const email = await registerUser(data);
    if (email) {
      navigate("/verify-otp", {
        state: { email, type: "register" },
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
        <h1 className="text-2xl font-extrabold text-dark mb-1">
          Create your account
        </h1>
        <p className="text-sm text-gray-500">
          Join thousands of happy customers today
        </p>
      </div>

      {/* Form */}
      <form onSubmit={handleSubmit(onSubmit)} noValidate className="space-y-3">
        <FormField
          htmlFor="name"
          label="Full name"
          error={errors.name?.message}
        >
          <Input
            id="name"
            type="text"
            placeholder="John Doe"
            autoComplete="name"
            variant={errors.name?.message ? "error" : "default"}
            {...register("name")}
          />
        </FormField>

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
            variant={errors.email?.message ? "error" : "default"}
            {...register("email")}
          />
        </FormField>

        {/* Password + strength bar */}
        <div>
          <PasswordInput
            id="password"
            label="Password"
            placeholder="At least 8 characters"
            autoComplete="new-password"
            error={errors.password?.message}
            {...register("password")}
          />
          <PasswordStrength password={password ?? ""} />
        </div>

        {/* Confirm password + match icon */}
        <div className="relative">
          <PasswordInput
            id="password_confirmation"
            label="Confirm password"
            placeholder="Repeat your password"
            autoComplete="new-password"
            error={errors.password_confirmation?.message}
            {...register("password_confirmation")}
          />
          {passwordsMatch && (
            <div className="absolute right-3 bottom-[calc(1rem+1.375rem)]">
              <Check className="w-4 h-4 text-green-500" />
            </div>
          )}
        </div>

        {/* Terms */}
        <p className="text-xs text-gray-400 leading-relaxed pt-2 pb-1">
          By creating an account you agree to our{" "}
          <a href="/terms" className="text-primary hover:underline font-medium">
            Terms of Service
          </a>{" "}
          and{" "}
          <a
            href="/privacy"
            className="text-primary hover:underline font-medium"
          >
            Privacy Policy
          </a>
          .
        </p>

        <SubmitButton
          loading={isLoadingRegister}
          loadingText="Creating account…"
        >
          <UserPlus className="w-4 h-4" />
          Create account
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
        Already have an account?{" "}
        <Link
          to="/login"
          className="text-primary font-semibold hover:underline"
        >
          Sign in
        </Link>
      </p>
    </div>
  );
};

export default RegisterForm;
