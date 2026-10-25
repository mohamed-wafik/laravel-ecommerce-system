import type { IBrand } from "./index";

import FormField from "../../../components/ui/FormField";
import Model from "../../../components/ui/Model";
import SectionTitle from "../../../components/ui/SectionTitle";
import Button from "../../../components/ui/Button";

import { useEffect, useState } from "react";
import { useForm, type SubmitHandler } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import toast from "react-hot-toast";

import { Check, ImageIcon, X } from "lucide-react";

import {
  brandFormSchema,
  type BrandFormInput,
  type BrandFormValues,
} from "../../../schema/Admin/admin.brand.schema";

import { adminBrandAPI } from "../../../api/modules/Admin/brand.api";
import { inputClass } from "../../../data/input";

const emptyForm: BrandFormValues = {
  name: "",
  slug: "",
  is_active: true,
};

interface IProps {
  editingBrand: IBrand | null;
  closeModal: () => void;
  onSuccess: () => Promise<void>;
}

const CreateOrEdit = ({ editingBrand, closeModal, onSuccess }: IProps) => {
  const [selectedFile, setSelectedFile] = useState<File | null>(null);

  const [logoPreview, setLogoPreview] = useState<string | null>(null);

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors, isSubmitting },
  } = useForm<BrandFormInput, any, BrandFormValues>({
    resolver: zodResolver(brandFormSchema),
    defaultValues: emptyForm,
  });

  /*
   * Populate form when editing.
   */
  useEffect(() => {
    if (editingBrand) {
      reset({
        name: editingBrand.name,
        slug: editingBrand.slug ?? "",
        is_active: editingBrand.is_active,
      });

      setSelectedFile(null);

      setLogoPreview(editingBrand.logo_url ?? null);
    } else {
      reset(emptyForm);

      setSelectedFile(null);
      setLogoPreview(null);
    }
  }, [editingBrand, reset]);

  /*
   * Logo selection.
   */
  const handleLogoChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];

    if (!file) return;

    if (!file.type.startsWith("image/")) {
      toast.error("Only image files are allowed");
      return;
    }

    if (file.size > 10 * 1024 * 1024) {
      toast.error("Logo size must not exceed 10MB");
      return;
    }

    setSelectedFile(file);

    const preview = URL.createObjectURL(file);

    setLogoPreview(preview);
  };

  /*
   * Submit create/update.
   */
  const onSubmit: SubmitHandler<BrandFormValues> = async (values) => {
    try {
      const formData = new FormData();

      formData.append("name", values.name);
      if (values.slug) {
        formData.append("slug", values.slug);
      }

      formData.append("is_active", values.is_active ? "1" : "0");

      if (selectedFile) {
        formData.append("logo", selectedFile);
      }

      if (editingBrand) {
        await adminBrandAPI.update(editingBrand.id, formData);

        toast.success("Brand updated successfully");
      } else {
        await adminBrandAPI.create(formData);

        toast.success("Brand created successfully");
      }

      await onSuccess();

      closeModal();
    } catch (error) {
      console.error(error);

      toast.error(
        editingBrand ? "Failed to update brand" : "Failed to create brand",
      );
    }
  };

  /*
   * Remove selected logo.
   *
   * When editing, keep the existing server logo as preview.
   */
  const handleRemoveLogo = () => {
    setSelectedFile(null);

    setLogoPreview(editingBrand?.logo_url ?? null);
  };

  return (
    <Model
      title={editingBrand ? "Edit Brand" : "Create Brand"}
      subTitle={
        editingBrand
          ? "Update brand information and settings."
          : "Add a new brand to your store."
      }
      isDisabled={isSubmitting}
      onClose={closeModal}
    >
      <form onSubmit={handleSubmit(onSubmit)} className="space-y-6 p-5 sm:p-6">
        {/* Basic Information */}

        <div>
          <SectionTitle
            title="Basic Information"
            description="Brand information visible throughout your store."
          />

          <div className="mt-4 grid gap-4 sm:grid-cols-2">
            {/* Name */}

            <FormField
              htmlFor="name"
              label="Brand name"
              error={errors.name?.message}
              required
            >
              <input
                id="name"
                type="text"
                {...register("name")}
                placeholder="Enter brand name"
                className={inputClass}
              />
            </FormField>

            {/* Slug */}

            <FormField htmlFor="slug" label="Slug" error={errors.slug?.message}>
              <input
                id="slug"
                type="text"
                {...register("slug")}
                placeholder="brand-slug"
                className={inputClass}
              />
            </FormField>

            {/* Status */}

            <FormField
              htmlFor="status"
              label="Status"
              error={errors.is_active?.message}
            >
              <label className="flex h-11 cursor-pointer items-center gap-3 rounded-xl border border-border bg-bg-secondary px-3">
                <input
                  id="status"
                  type="checkbox"
                  {...register("is_active")}
                  className="h-4 w-4 rounded border-border text-primary focus:ring-primary"
                />

                <span className="text-sm text-text-primary">
                  Brand is active
                </span>
              </label>
            </FormField>
          </div>
        </div>

        {/* Logo */}

        <div>
          <SectionTitle
            title="Brand Logo"
            description="Upload a logo for this brand."
          />

          <label className="mt-4 flex min-h-32 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-border bg-bg-secondary p-5 text-center transition hover:border-primary hover:bg-primary-50">
            <ImageIcon className="h-7 w-7 text-text-tertiary" />

            <span className="mt-2 text-sm font-medium text-text-primary">
              Choose logo
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
              onChange={handleLogoChange}
              className="hidden"
            />
          </label>

          {/* Preview */}

          {logoPreview && (
            <div className="mt-4">
              <div className="relative h-40 w-40 overflow-hidden rounded-xl border border-border bg-bg-secondary">
                <img
                  src={logoPreview}
                  alt="Brand logo preview"
                  className="h-full w-full object-contain p-3"
                />

                <button
                  type="button"
                  onClick={handleRemoveLogo}
                  disabled={isSubmitting}
                  className="absolute right-2 top-2 rounded-lg bg-black/60 p-1.5 text-white transition hover:bg-black/80 disabled:cursor-not-allowed disabled:opacity-50"
                  title="Remove selected logo"
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

        {/* Footer */}

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
              : editingBrand
                ? "Update Brand"
                : "Create Brand"}
          </Button>
        </div>
      </form>
    </Model>
  );
};

export default CreateOrEdit;
