import { useEffect, useState } from "react";
import { useForm, type SubmitHandler } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import toast from "react-hot-toast";
import { Check, ImageIcon, X } from "lucide-react";

import FormField from "../../../components/ui/FormField";
import Model from "../../../components/ui/Model";
import SectionTitle from "../../../components/ui/SectionTitle";
import Button from "../../../components/ui/Button";

import {
  userFormSchema,
  type UserFormInput,
  type UserFormValues,
} from "../../../schema/Admin/admin.user.schema";

import { adminUserAPI } from "../../../api/modules/Admin/user.api";
import type { IUser } from "../../../interface";
import { inputClass } from "../../../data/input";

/* -------------------------------------------------------------------------- */
/* Default Values                                                             */
/* -------------------------------------------------------------------------- */

const emptyForm: UserFormValues = {
  name: "",
  email: "",
  phone: "",
  password: "",
  role: "customer",
};

/* -------------------------------------------------------------------------- */
/* Props                                                                      */
/* -------------------------------------------------------------------------- */

interface IProps {
  editingUser: IUser | null;
  closeModal: () => void;
  onSuccess: () => Promise<void>;
}

/* -------------------------------------------------------------------------- */
/* Component                                                                  */
/* -------------------------------------------------------------------------- */

const CreateOrEdit = ({ editingUser, closeModal, onSuccess }: IProps) => {
  const [selectedFile, setSelectedFile] = useState<File | null>(null);

  const [avatarPreview, setAvatarPreview] = useState<string | null>(null);

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors, isSubmitting },
  } = useForm<UserFormInput, any, UserFormValues>({
    resolver: zodResolver(userFormSchema),
    defaultValues: emptyForm,
  });

  /* ------------------------------------------------------------------------ */
  /* Populate Form                                                            */
  /* ------------------------------------------------------------------------ */

  useEffect(() => {
    if (editingUser) {
      reset({
        name: editingUser.name,
        email: editingUser.email,
        phone: editingUser.phone ?? "",
        password: "",
        role: editingUser.role as UserFormInput["role"],
      });

      setSelectedFile(null);

      setAvatarPreview(editingUser.avatar_url ?? null);
    } else {
      reset(emptyForm);

      setSelectedFile(null);
      setAvatarPreview(null);
    }
  }, [editingUser, reset]);

  /* ------------------------------------------------------------------------ */
  /* Avatar Selection                                                         */
  /* ------------------------------------------------------------------------ */

  const handleAvatarChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];

    if (!file) return;

    if (!file.type.startsWith("image/")) {
      toast.error("Only image files are allowed");
      return;
    }

    if (file.size > 10 * 1024 * 1024) {
      toast.error("Avatar size must not exceed 10MB");
      return;
    }

    setSelectedFile(file);

    const preview = URL.createObjectURL(file);

    setAvatarPreview(preview);
  };

  /* ------------------------------------------------------------------------ */
  /* Submit                                                                   */
  /* ------------------------------------------------------------------------ */

  const onSubmit: SubmitHandler<UserFormValues> = async (values) => {
    try {
      const formData = new FormData();

      formData.append("name", values.name);
      formData.append("email", values.email);

      if (values.phone) {
        formData.append("phone", values.phone);
      }

      /*
       * Password:
       *
       * Create -> required.
       * Edit   -> only send if the admin entered a new password.
       */
      if (values.password) {
        formData.append("password", values.password);
      }

      formData.append("role", values.role);

      if (selectedFile) {
        formData.append("avatar", selectedFile);
      }

      if (editingUser) {
        formData.append("_method", "put");
        await adminUserAPI.update(editingUser.id, formData);

        toast.success("User updated successfully");
      } else {
        await adminUserAPI.create(formData);

        toast.success("User created successfully");
      }

      await onSuccess();

      closeModal();
    } catch (error) {
      console.error(error);

      toast.error(
        editingUser ? "Failed to update user" : "Failed to create user",
      );
    }
  };

  /* ------------------------------------------------------------------------ */
  /* Remove Avatar                                                            */
  /* ------------------------------------------------------------------------ */

  const handleRemoveAvatar = () => {
    setSelectedFile(null);

    setAvatarPreview(editingUser?.avatar_url ?? null);
  };

  /* ------------------------------------------------------------------------ */
  /* Render                                                                   */
  /* ------------------------------------------------------------------------ */

  return (
    <Model
      title={editingUser ? "Edit User" : "Create User"}
      subTitle={
        editingUser
          ? "Update user information and account settings."
          : "Add a new customer or administrator."
      }
      isDisabled={isSubmitting}
      onClose={closeModal}
    >
      <form onSubmit={handleSubmit(onSubmit)} className="space-y-6 p-5 sm:p-6">
        {/* ================================================================== */}
        {/* Basic Information                                                  */}
        {/* ================================================================== */}

        <div>
          <SectionTitle
            title="Basic Information"
            description="User information and account credentials."
          />

          <div className="mt-4 grid gap-4 sm:grid-cols-2">
            {/* Name */}

            <FormField
              htmlFor="name"
              label="Full name"
              error={errors.name?.message}
              required
            >
              <input
                id="name"
                type="text"
                {...register("name")}
                placeholder="Enter full name"
                className={inputClass}
              />
            </FormField>

            {/* Email */}

            <FormField
              htmlFor="email"
              label="Email"
              error={errors.email?.message}
              required
            >
              <input
                id="email"
                type="email"
                {...register("email")}
                placeholder="user@example.com"
                className={inputClass}
              />
            </FormField>

            {/* Phone */}

            <FormField
              htmlFor="phone"
              label="Phone"
              error={errors.phone?.message}
            >
              <input
                id="phone"
                type="text"
                {...register("phone")}
                placeholder="Enter phone number"
                className={inputClass}
              />
            </FormField>

            {/* Role */}

            <FormField
              htmlFor="role"
              label="Role"
              error={errors.role?.message}
              required
            >
              <select id="role" {...register("role")} className={inputClass}>
                <option value="customer">Customer</option>
                <option value="admin">Administrator</option>
              </select>
            </FormField>

            {/* Password */}

            <div className="sm:col-span-2">
              <FormField
                htmlFor="password"
                label="Password"
                error={errors.password?.message}
                required={!editingUser}
              >
                <input
                  id="password"
                  type="password"
                  {...register("password")}
                  placeholder={
                    editingUser
                      ? "Leave empty to keep current password"
                      : "Enter password"
                  }
                  className={inputClass}
                />
              </FormField>

              {editingUser && (
                <p className="mt-1.5 text-xs text-text-tertiary">
                  Leave this field empty if you don't want to change the
                  password.
                </p>
              )}
            </div>
          </div>
        </div>

        {/* ================================================================== */}
        {/* Avatar                                                             */}
        {/* ================================================================== */}

        <div>
          <SectionTitle
            title="Profile Avatar"
            description="Upload a profile image for this user."
          />

          <label className="mt-4 flex min-h-32 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-border bg-bg-secondary p-5 text-center transition hover:border-primary hover:bg-primary-50">
            <ImageIcon className="h-7 w-7 text-text-tertiary" />

            <span className="mt-2 text-sm font-medium text-text-primary">
              Choose avatar
            </span>

            <span className="mt-1 text-xs text-text-tertiary">
              JPG, PNG, WEBP and other supported image formats
            </span>

            <span className="mt-1 text-xs text-text-tertiary">
              Maximum size: 10MB
            </span>

            <input
              type="file"
              accept="image/*"
              onChange={handleAvatarChange}
              className="hidden"
            />
          </label>

          {/* Preview */}

          {avatarPreview && (
            <div className="mt-4">
              <div className="relative h-32 w-32 overflow-hidden rounded-full border border-border bg-bg-secondary">
                <img
                  src={avatarPreview}
                  alt="User avatar preview"
                  className="h-full w-full object-cover"
                />

                <button
                  type="button"
                  onClick={handleRemoveAvatar}
                  disabled={isSubmitting}
                  className="absolute right-1 top-1 rounded-lg bg-black/60 p-1.5 text-white transition hover:bg-black/80 disabled:cursor-not-allowed disabled:opacity-50"
                  title="Remove selected avatar"
                >
                  <X size={15} />
                </button>
              </div>

              {selectedFile && (
                <p className="mt-2 text-xs text-text-tertiary">
                  {selectedFile.name}
                </p>
              )}
            </div>
          )}
        </div>

        {/* ================================================================== */}
        {/* Footer                                                             */}
        {/* ================================================================== */}

        <div className="flex flex-col-reverse gap-2 border-t border-border pt-5 sm:flex-row sm:justify-end">
          <Button
            type="button"
            variant="outline"
            onClick={closeModal}
            disabled={isSubmitting}
          >
            Cancel
          </Button>

          <Button
            type="submit"
            disabled={isSubmitting}
            loading={isSubmitting}
            className="inline-flex items-center justify-center gap-2"
          >
            <Check size={17} />

            {isSubmitting
              ? "Saving..."
              : editingUser
                ? "Update User"
                : "Create User"}
          </Button>
        </div>
      </form>
    </Model>
  );
};

export default CreateOrEdit;
