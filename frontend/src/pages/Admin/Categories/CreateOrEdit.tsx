import type { ICategory } from "./index";
import FormField from "../../../components/ui/FormField";
import Model from "../../../components/ui/Model";

import { useEffect, useState } from "react";
import { useForm, type SubmitHandler } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import toast from "react-hot-toast";

import { Check, ImageIcon, X } from "lucide-react";

import Button from "../../../components/ui/Button";
import {
  categoryFormSchema,
  type CategoryFormInput,
  type CategoryFormValues,
} from "../../../schema/Admin/admin.category.schema";
import SectionTitle from "../../../components/ui/SectionTitle";
import { adminCategoryAPI } from "../../../api/modules/Admin/category.api";
import { inputClass } from "../../../data/input";

const emptyForm: CategoryFormValues = {
  parent_id: "",
  name: "",
  slug: "",
  is_active: true,
  sort_order: 0,
};

interface IProps {
  categories: ICategory[] | null;
  editingCategory: ICategory | null;
  closeModal: () => void;
  onSuccess: () => Promise<void>;
}

const CreateOrEdit = ({
  categories,
  editingCategory,
  closeModal,
  onSuccess,
}: IProps) => {
  const [selectedFile, setSelectedFile] = useState<File | null>(null);
  const [imagePreview, setImagePreview] = useState<string | null>(null);

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors, isSubmitting },
  } = useForm<CategoryFormInput, any, CategoryFormValues>({
    resolver: zodResolver(categoryFormSchema),
    defaultValues: emptyForm,
  });

  /*
   * Populate form when editing.
   */
  useEffect(() => {
    if (editingCategory) {
      reset({
        parent_id: editingCategory.parent_id ?? "",
        name: editingCategory.name,
        slug: editingCategory.slug ?? "",
        is_active: editingCategory.is_active,
        sort_order: editingCategory.sort_order ?? 0,
      });

      setSelectedFile(null);
      setImagePreview(editingCategory.image_url ?? null);
    } else {
      reset(emptyForm);
      setSelectedFile(null);
      setImagePreview(null);
    }
  }, [editingCategory, reset]);

  /*
   * Image selection.
   */
  const handleImageChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];

    if (!file) return;

    if (!file.type.startsWith("image/")) {
      toast.error("Only image files are allowed");
      return;
    }

    if (file.size > 10 * 1024 * 1024) {
      toast.error("Image size must not exceed 10MB");
      return;
    }

    setSelectedFile(file);

    const preview = URL.createObjectURL(file);
    setImagePreview(preview);
  };

  /*
   * Submit create/update.
   */
  const onSubmit: SubmitHandler<CategoryFormValues> = async (values) => {
    try {
      const formData = new FormData();

      /*
       * Parent category
       */
      if (values.parent_id) {
        formData.append("parent_id", values.parent_id);
      }

      /*
       * Basic information
       */
      formData.append("name", values.name);

      if (values.slug) {
        formData.append("slug", values.slug);
      }

      /*
       * Status
       */
      formData.append("is_active", values.is_active ? "1" : "0");

      /*
       * Sort order
       */
      formData.append("sort_order", String(values.sort_order ?? 0));

      /*
       * Image
       */
      if (selectedFile) {
        formData.append("image", selectedFile);
      }

      /*
       * Update
       */
      if (editingCategory) {
        await adminCategoryAPI.update(editingCategory.id, formData);

        toast.success("Category updated successfully");
      } else {
        /*
         * Create
         */
        await adminCategoryAPI.create(formData);

        toast.success("Category created successfully");
      }

      await onSuccess();

      closeModal();
    } catch (error) {
      console.error(error);

      toast.error(
        editingCategory
          ? "Failed to update category"
          : "Failed to create category",
      );
    }
  };

  /*
   * Do not allow the current category to be its own parent.
   */
  const availableParentCategories =
    categories?.filter((category) => category.id !== editingCategory?.id) ?? [];

  return (
    <Model
      title={editingCategory ? "Edit Category" : "Create Category"}
      subTitle={
        editingCategory
          ? "Update category information and settings."
          : "Add a new category to your store."
      }
      isDisabled={isSubmitting}
      onClose={closeModal}
    >
      <form onSubmit={handleSubmit(onSubmit)} className="space-y-6 p-5 sm:p-6">
        {/* Basic Information */}
        <div>
          <SectionTitle
            title="Basic Information"
            description="Category information visible throughout your store."
          />

          <div className="mt-4 grid gap-4 sm:grid-cols-2">
            {/* Name */}
            <FormField
              htmlFor="name"
              label="Category name"
              error={errors.name?.message}
              required
            >
              <input
                id="name"
                type="text"
                {...register("name")}
                placeholder="Enter category name"
                className={inputClass}
              />
            </FormField>

            {/* Slug */}
            <FormField htmlFor="slug" label="Slug" error={errors.slug?.message}>
              <input
                id="slug"
                type="text"
                {...register("slug")}
                placeholder="category-slug"
                className={inputClass}
              />
            </FormField>

            {/* Parent */}
            <FormField
              htmlFor="parent_id"
              label="Parent category"
              error={errors.parent_id?.message}
            >
              <select
                id="parent_id"
                {...register("parent_id")}
                className={inputClass}
              >
                <option value="">No parent (Root category)</option>

                {availableParentCategories.map((category) => (
                  <option key={category.id} value={category.id}>
                    {category.name}
                  </option>
                ))}
              </select>
            </FormField>

            {/* Sort order */}
            <FormField
              htmlFor="sort_order"
              label="Sort order"
              error={errors.sort_order?.message}
            >
              <input
                id="sort_order"
                type="number"
                min="0"
                {...register("sort_order")}
                className={inputClass}
                placeholder="0"
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
                  Category is active
                </span>
              </label>
            </FormField>
          </div>
        </div>

        {/* Image */}
        <div>
          <SectionTitle
            title="Category Image"
            description="Upload an image for this category."
          />

          <label className="mt-4 flex min-h-32 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-border bg-bg-secondary p-5 text-center transition hover:border-primary hover:bg-primary-50">
            <ImageIcon className="h-7 w-7 text-text-tertiary" />

            <span className="mt-2 text-sm font-medium text-text-primary">
              Choose image
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
              onChange={handleImageChange}
              className="hidden"
            />
          </label>

          {/* Preview */}
          {imagePreview && (
            <div className="mt-4">
              <div className="relative h-40 w-40 overflow-hidden rounded-xl border border-border bg-bg-secondary">
                <img
                  src={imagePreview}
                  alt="Category preview"
                  className="h-full w-full object-cover"
                />

                <button
                  type="button"
                  onClick={() => {
                    setSelectedFile(null);
                    setImagePreview(editingCategory?.image_url ?? null);
                  }}
                  className="absolute right-2 top-2 rounded-lg bg-black/60 p-1.5 text-white transition hover:bg-black/80"
                  title="Remove selected image"
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
              : editingCategory
                ? "Update Category"
                : "Create Category"}
          </Button>
        </div>
      </form>
    </Model>
  );
};

export default CreateOrEdit;
