import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { ShieldCheck, Lock, Check } from "lucide-react";
import { useAuthStore } from "../../../../store/useAuth";
import {
  changePasswordSchema,
  type ChangePasswordInput,
} from "../../../../schema/profile.schema";
import { profileAPI } from "../../../../api/modules/Customer/profile.api";
import { getErrorMessage } from "../../../../lib/utils";
import SectionCard from "../../SectionCard";
import PasswordInput from "../../../../components/auth/PasswordInput";
import toast from "react-hot-toast";
import cookieService from "../../../../Service/cookie.service";
import PasswordStrength from "../../../../components/auth/PasswordStrength";
const ChangePasswordCard = () => {
  const { check } = useAuthStore();

  const {
    register,
    handleSubmit,
    watch,
    reset,
    formState: { errors, isSubmitting },
  } = useForm<ChangePasswordInput>({
    resolver: zodResolver(changePasswordSchema),
    defaultValues: {
      current_password: "",
      new_password: "",
      new_password_confirmation: "",
    },
  });

  const newPw = watch("new_password") ?? "";
  const confirmPw = watch("new_password_confirmation") ?? "";
  const passwordsMatch = !!newPw && !!confirmPw && newPw === confirmPw;

  const onSubmit = async (data: ChangePasswordInput) => {
    try {
      const res = await profileAPI.changePassword(data);
      // Backend returns a new access_token after revoking old tokens
      const newToken = res?.data?.access_token;
      if (newToken) {
        cookieService.set("access_token", newToken, {});
      }
      await check();
      toast.success("Password changed ✅");
      reset();
    } catch (err) {
      toast.error(getErrorMessage(err));
    }
  };

  return (
    <SectionCard
      icon={Lock}
      title="Change Password"
      description="Use a strong password with uppercase letters, numbers and symbols"
    >
      <form onSubmit={handleSubmit(onSubmit)} noValidate className="space-y-4">
        <PasswordInput
          id="current_password"
          label="Current password"
          placeholder="Your current password"
          autoComplete="current-password"
          error={errors.current_password?.message}
          {...register("current_password")}
        />

        <div>
          <PasswordInput
            id="new_password"
            label="New password"
            placeholder="At least 8 characters"
            autoComplete="new-password"
            error={errors.new_password?.message}
            {...register("new_password")}
          />
          {newPw && <PasswordStrength password={newPw} />}
        </div>

        <div className="relative">
          <PasswordInput
            id="new_password_confirmation"
            label="Confirm new password"
            placeholder="Repeat your new password"
            autoComplete="new-password"
            error={errors.new_password_confirmation?.message}
            {...register("new_password_confirmation")}
          />
          {passwordsMatch && (
            <div className="absolute right-3 bottom-[calc(1rem+1.375rem)]">
              <Check className="w-4 h-4 text-green-500" />
            </div>
          )}
        </div>

        <div className="flex justify-end pt-1">
          <button
            type="submit"
            disabled={isSubmitting}
            className="inline-flex items-center gap-2 bg-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:opacity-90 disabled:opacity-40 transition-all active:scale-[0.98]"
          >
            {isSubmitting ? (
              <span className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
            ) : (
              <ShieldCheck className="w-4 h-4" />
            )}
            {isSubmitting ? "Updating…" : "Update password"}
          </button>
        </div>
      </form>
    </SectionCard>
  );
};

export default ChangePasswordCard;
