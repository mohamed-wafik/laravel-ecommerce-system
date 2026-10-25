import { useEffect, useState } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { useForm, useWatch } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { LockKeyhole, Check, ArrowLeft } from "lucide-react";
import {
  resetPasswordSchema,
  type ResetPasswordInput,
} from "../../schema/FrogotPassword.schema";
import PasswordInput from "../../components/auth/PasswordInput";
import SubmitButton from "../../components/auth/SubmitButton";
import toast from "react-hot-toast";
import { passwordAPI } from "../../api/modules/Auth/password.api";
import PasswordStrength from "./PasswordStrength";

// ── Component ─────────────────────────────────────────────────────────────────
const ResetPasswordForm = () => {
  const navigate = useNavigate();
  const location = useLocation();
  const [isLoading, setIsLoading] = useState<boolean>(false);

  const { email } = (location.state ?? {}) as { email?: string };

  useEffect(() => {
    if (!email) {
      toast.error("Please verify your code before resetting your password.");
      navigate("/forgot-password", { replace: true });
    }
  }, [email, navigate]);

  const {
    control,
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<ResetPasswordInput>({
    resolver: zodResolver(resetPasswordSchema),
    defaultValues: { password: "", confirmPassword: "" },
  });

  const password = useWatch({ control, name: "password" });
  const passwordConfirmation = useWatch({ control, name: "confirmPassword" });
  const passwordsMatch =
    !!password && !!passwordConfirmation && password === passwordConfirmation;

  const onSubmit = async (data: ResetPasswordInput) => {
    if (!email) return;
    setIsLoading(true);
    try {
      await passwordAPI.resetPassword({
        password: data.password,
        password_confirmation: data.confirmPassword,
      });

      toast.success("Password reset successfully! Please log in.");
      navigate("/login", { replace: true });
    } catch (err) {
      const message =
        err instanceof Error ? err.message : "Failed to reset password.";
      toast.error(message);
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
          <LockKeyhole className="w-7 h-7 text-primary" />
        </div>

        <h1 className="text-2xl font-extrabold text-dark mb-1">
          Reset your password
        </h1>
        <p className="text-sm text-gray-500">
          Enter the new password.{" "}
          <span className="font-semibold text-dark">{email}</span>
        </p>
      </div>

      {/* Form */}
      <form onSubmit={handleSubmit(onSubmit)} noValidate className="space-y-5">
        {/* ── New password ── */}
        <div>
          <PasswordInput
            id="password"
            label="New password"
            placeholder="At least 8 characters"
            autoComplete="new-password"
            error={errors.password?.message}
            disabled={isLoading}
            {...register("password")}
          />
          <PasswordStrength password={password ?? ""} />
        </div>

        {/* ── Confirm password ── */}
        <div className="relative">
          <PasswordInput
            id="password_confirmation"
            label="Confirm new password"
            placeholder="Repeat your password"
            autoComplete="new-password"
            error={errors.confirmPassword?.message}
            disabled={isLoading}
            {...register("confirmPassword")}
          />
          {passwordsMatch && (
            <div className="absolute right-3 bottom-[calc(1rem+1.375rem)]">
              <Check className="w-4 h-4 text-green-500" />
            </div>
          )}
        </div>

        <SubmitButton loading={isLoading} loadingText="Resetting password…">
          <LockKeyhole className="w-4 h-4" />
          Reset password
        </SubmitButton>
      </form>

      {/* Back */}
      <div className="mt-6 text-center">
        <Link
          to="/forgot-password"
          className="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-primary transition-colors font-medium"
        >
          <ArrowLeft className="w-4 h-4" />
          Request a new code
        </Link>
      </div>
    </div>
  );
};

export default ResetPasswordForm;
