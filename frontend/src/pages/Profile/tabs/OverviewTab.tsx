import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { User, Save } from "lucide-react";
import toast from "react-hot-toast";
import {
  updateProfileSchema,
  type UpdateProfileInput,
} from "../../../schema/profile.schema";
import { useAuthStore } from "../../../store/useAuth";
import { profileAPI } from "../../../api/modules/Customer/profile.api";
import { getErrorMessage } from "../../../lib/utils";
import SectionCard from "../../../pages/Profile/SectionCard";

const OverviewTab = () => {
  const { user, check } = useAuthStore();

  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting, isDirty },
  } = useForm<UpdateProfileInput>({
    resolver: zodResolver(updateProfileSchema),
    defaultValues: { name: user?.name ?? "" },
  });

  const onSubmit = async (values: UpdateProfileInput) => {
    if (!values.name?.trim()) return;

    const form = new FormData();
    form.append("name", values.name.trim());

    try {
      await profileAPI.updateProfile(form);
      await check(); // refresh store
      toast.success("Name updated ✅");
    } catch (err) {
      toast.error(getErrorMessage(err));
    }
  };

  return (
    <div className="space-y-5">
      <SectionCard
        icon={User}
        title="Personal Information"
        description="Update your display name"
      >
        <form
          onSubmit={handleSubmit(onSubmit)}
          noValidate
          className="space-y-5"
        >
          {/* Read-only email */}
          <div className="flex flex-col gap-1.5">
            <label className="text-xs font-semibold text-gray-500 uppercase tracking-wider">
              Email address
            </label>
            <div className="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 text-sm text-gray-400 select-none">
              {user?.email}
            </div>
            <p className="text-xs text-gray-400">
              To change your email go to the Security tab.
            </p>
          </div>

          {/* Name */}
          <div className="flex flex-col gap-1.5">
            <label
              htmlFor="name"
              className="text-xs font-semibold text-dark uppercase tracking-wider"
            >
              Display name
            </label>
            <input
              id="name"
              type="text"
              placeholder="Your full name"
              className={`w-full px-4 py-3 rounded-xl border text-sm text-dark placeholder:text-gray-400 bg-white outline-none transition-colors
                ${
                  errors.name
                    ? "border-red-400 focus:ring-2 focus:ring-red-100"
                    : "border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/10"
                }`}
              {...register("name")}
            />
            {errors.name && (
              <p className="text-xs text-red-500">{errors.name.message}</p>
            )}
          </div>

          <div className="flex justify-end">
            <button
              type="submit"
              disabled={isSubmitting || !isDirty}
              className="inline-flex items-center gap-2 bg-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:opacity-90 active:scale-[0.98] transition-all disabled:opacity-40"
            >
              {isSubmitting ? (
                <span className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
              ) : (
                <Save className="w-4 h-4" />
              )}
              {isSubmitting ? "Saving…" : "Save changes"}
            </button>
          </div>
        </form>
      </SectionCard>
    </div>
  );
};

export default OverviewTab;
