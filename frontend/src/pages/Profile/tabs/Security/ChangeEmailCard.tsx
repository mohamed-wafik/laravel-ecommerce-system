import { useState } from "react";
import {
  changeEmailSchema,
  type ChangeEmailInput,
} from "../../../../schema/profile.schema";
import { useAuthStore } from "../../../../store/useAuth";
import { profileAPI } from "../../../../api/modules/Customer/profile.api";
import toast from "react-hot-toast";
import { getErrorMessage } from "../../../../lib/utils";
import SectionCard from "../../SectionCard";
import { Mail } from "lucide-react";
import PasswordInput from "../../../../components/auth/PasswordInput";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import ModelVerifyEmail from "./ModelVerifyEmail";

const ChangeEmailCard = () => {
  const { user } = useAuthStore();
  const [success, setSuccess] = useState(false);

  const [email, setEmail] = useState("");
  const {
    register,
    handleSubmit,
    reset,
    formState: { errors, isSubmitting },
  } = useForm<ChangeEmailInput>({
    resolver: zodResolver(changeEmailSchema),
    defaultValues: { new_email: "", password: "" },
  });

  const onSubmit = async (data: ChangeEmailInput) => {
    try {
      await profileAPI.changeEmail(data);
      toast.success("Verification code sent to your new email 📬");
      setEmail(data.new_email);
      setSuccess(true);
      reset();
    } catch (err) {
      toast.error(getErrorMessage(err));
    }
  };

  return (
    <SectionCard
      icon={Mail}
      title="Change Email"
      description="All sessions will be logged out after changing your email"
    >
      <ModelVerifyEmail email={email} isOpen={success} setIsOpen={setSuccess} />

      <form onSubmit={handleSubmit(onSubmit)} noValidate className="space-y-4">
        {/* Current email — read-only display */}
        <div className="flex flex-col gap-1.5">
          <label className="text-xs font-semibold text-dark uppercase tracking-wider">
            Current email
          </label>
          <div className="px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 text-sm text-gray-400">
            {user?.email}
          </div>
        </div>

        {/* New email */}
        <div className="flex flex-col gap-1.5">
          <label
            htmlFor="new_email"
            className="text-xs font-semibold text-dark uppercase tracking-wider"
          >
            New email address
          </label>
          <input
            id="new_email"
            type="email"
            placeholder="new@example.com"
            autoComplete="email"
            className={`w-full px-4 py-3 rounded-xl border text-sm text-dark placeholder:text-gray-400 bg-white outline-none transition-colors
                ${
                  errors.new_email
                    ? "border-red-400 focus:ring-2 focus:ring-red-100"
                    : "border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/10"
                }`}
            {...register("new_email")}
          />
          {errors.new_email && (
            <p className="text-xs text-red-500">{errors.new_email.message}</p>
          )}
        </div>

        {/* Password confirmation */}
        <PasswordInput
          id="confirm_password_email"
          label="Confirm with your password"
          placeholder="Your current password"
          autoComplete="current-password"
          error={errors.password?.message}
          {...register("password")}
        />

        <div className="flex justify-end pt-1">
          <button
            type="submit"
            disabled={isSubmitting}
            className="inline-flex items-center gap-2 bg-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:opacity-90 disabled:opacity-40 transition-all active:scale-[0.98]"
          >
            {isSubmitting ? (
              <span className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
            ) : (
              <Mail className="w-4 h-4" />
            )}
            {isSubmitting ? "Sending code…" : "Change email"}
          </button>
        </div>
      </form>
    </SectionCard>
  );
};
export default ChangeEmailCard;
