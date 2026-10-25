import { Link, useNavigate } from "react-router-dom";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { KeyRound, ArrowLeft } from "lucide-react";
import {
  forgotPasswordSchema,
  type ForgotPasswordInput,
} from "../../schema/FrogotPassword.schema";
import FormField from "../ui/FormField";
import SubmitButton from "../../components/auth/SubmitButton";
import { passwordAPI } from "../../api/modules/Auth/password.api";
import { useState } from "react";
import Input from "../ui/Input";

const ForgotPasswordForm = () => {
  const navigate = useNavigate();
  const [isLoading, setIsLoading] = useState<boolean>(false);
  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitSuccessful },
  } = useForm<ForgotPasswordInput>({
    resolver: zodResolver(forgotPasswordSchema),
    defaultValues: { email: "" },
  });

  const handleVerifySuccess = (email: string) => {
    navigate("/verify-password-otp", {
      state: { email },
      replace: true,
    });
  };

  const onSubmit = async ({ email }: ForgotPasswordInput) => {
    setIsLoading(true);
    try {
      await passwordAPI.sendOtp(email);
      handleVerifySuccess(email);
    } catch (error) {
    } finally {
      setIsLoading(false);
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

        <div className="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mb-5">
          <KeyRound className="w-7 h-7 text-primary" />
        </div>

        <h1 className="text-2xl font-extrabold text-dark mb-1">
          Forgot password?
        </h1>
        <p className="text-sm text-gray-500 max-w-xs">
          No worries — enter your email and we'll send you a reset code.
        </p>
      </div>

      {/* Success banner (shown after submit, before redirect) */}
      {isSubmitSuccessful && (
        <div className="flex items-start gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-6 text-sm">
          <span className="text-base leading-none mt-0.5">📬</span>
          <p>
            If an account with that email exists, you'll receive a reset code
            shortly. Check your spam folder too.
          </p>
        </div>
      )}

      {/* Form */}
      <form onSubmit={handleSubmit(onSubmit)} noValidate className="space-y-5">
        <FormField
          htmlFor="email"
          label="Email address"
          error={errors.email?.message}
          required
        >
          <Input
            id="email"
            type="email"
            placeholder="you@example.com"
            autoComplete="email"
            variant={!errors.email ? "default" : "error"}
            {...register("email")}
          />
        </FormField>

        <SubmitButton loading={isLoading} loadingText="Sending code…">
          <KeyRound className="w-4 h-4" />
          Send reset code
        </SubmitButton>
      </form>

      {/* Back to login */}
      <div className="mt-6 text-center">
        <Link
          to="/login"
          className="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-primary transition-colors font-medium"
        >
          <ArrowLeft className="w-4 h-4" />
          Back to sign in
        </Link>
      </div>
    </div>
  );
};

export default ForgotPasswordForm;
