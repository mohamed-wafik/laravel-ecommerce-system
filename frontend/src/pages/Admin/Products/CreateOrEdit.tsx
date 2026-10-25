import { zodResolver } from "@hookform/resolvers/zod";
import { Check, ImageIcon, Package, Plus, X } from "lucide-react";
import { useEffect, useState, type ChangeEvent } from "react";
import { useFieldArray, useForm, type SubmitHandler } from "react-hook-form";
import toast from "react-hot-toast";

import type { IBrand, ICategory, IProduct } from "../../../interface";
import { adminProductAPI } from "../../../api/modules/Admin/product.api";
import Button from "../../../components/ui/Button";
import FormField from "../../../components/ui/FormField";
import Model from "../../../components/ui/Model";
import SectionTitle from "../../../components/ui/SectionTitle";
import { inputClass } from "../../../data/input";
import {
  productFormSchema,
  type ProductFormInput,
  type ProductFormValues,
} from "../../../schema/Admin/admin.product.schema";

interface CreateOrEditProps {
  categories: ICategory[] | null;
  brands: IBrand[] | null;
  editingProduct: IProduct | null;
  closeModal: () => void;
  onSuccess: () => Promise<void>;
}

const EMPTY_FORM: ProductFormValues = {
  category_id: "",
  brand_id: "",
  name: "",
  slug: "",
  description: "",
  price: 0,
  compare_price: "",
  sku: "",
  stock_quantity: 0,
  is_active: true,
  variants: [],
};

const EMPTY_VARIANT = {
  color: "",
  size: "",
  sku: "",
  price_override: "",
  stock_quantity: 0,
};

const MAX_IMAGES = 8;

const mapProductToForm = (product: IProduct): ProductFormValues => ({
  category_id: product.category_id,
  brand_id: product.brand_id ?? "",
  name: product.name,
  slug: product.slug ?? "",
  description: product.description ?? "",
  price: Number(product.price),
  compare_price:
    product.compare_price === null
      ? ""
      : Number(product.compare_price),
  sku: product.sku,
  stock_quantity: product.stock_quantity ?? 0,
  is_active: product.is_active,
  variants:
    product.variants?.map((variant) => ({
      color: variant.color ?? "",
      size: variant.size ?? "",
      sku: variant.sku,
      price_override:
        variant.price_override === null
          ? ""
          : Number(variant.price_override),
      stock_quantity: variant.stock_quantity ?? 0,
    })) ?? [],
});

const validateImages = (files: File[]): boolean => {
  if (files.length > MAX_IMAGES) {
    toast.error(`You can upload a maximum of ${MAX_IMAGES} images`);
    return false;
  }

  const hasInvalidFile = files.some(
    (file) => !file.type.startsWith("image/"),
  );

  if (hasInvalidFile) {
    toast.error("Only image files are allowed");
    return false;
  }

  return true;
};

const buildFormData = (
  values: ProductFormValues,
  files: File[],
): FormData => {
  const formData = new FormData();

  formData.append("category_id", values.category_id);
  formData.append("name", values.name);
  formData.append("price", String(values.price));
  formData.append("sku", values.sku);
  formData.append(
    "stock_quantity",
    String(values.stock_quantity ?? 0),
  );
  formData.append("is_active", values.is_active ? "1" : "0");

  if (values.brand_id) {
    formData.append("brand_id", values.brand_id);
  }

  if (values.slug) {
    formData.append("slug", values.slug);
  }

  if (values.description) {
    formData.append("description", values.description);
  }

  if (values.compare_price !== "" && values.compare_price !== undefined) {
    formData.append(
      "compare_price",
      String(values.compare_price),
    );
  }

  files.forEach((file) => {
    formData.append("images[]", file);
  });

  values.variants?.forEach((variant, index) => {
    const prefix = `variants[${index}]`;

    if (variant.color) {
      formData.append(`${prefix}[color]`, variant.color);
    }

    if (variant.size) {
      formData.append(`${prefix}[size]`, variant.size);
    }

    formData.append(`${prefix}[sku]`, variant.sku);

    if (
      variant.price_override !== "" &&
      variant.price_override !== undefined
    ) {
      formData.append(
        `${prefix}[price_override]`,
        String(variant.price_override),
      );
    }

    formData.append(
      `${prefix}[stock_quantity]`,
      String(variant.stock_quantity ?? 0),
    );
  });

  return formData;
};

const CreateOrEdit = ({
  editingProduct,
  closeModal,
  brands,
  categories,
  onSuccess,
}: CreateOrEditProps) => {
  const [selectedFiles, setSelectedFiles] = useState<File[]>([]);
  const [imagePreviews, setImagePreviews] = useState<string[]>([]);

  const {
    register,
    control,
    handleSubmit,
    reset,
    formState: { errors, isSubmitting },
  } = useForm<ProductFormInput, undefined, ProductFormValues>({
    resolver: zodResolver(productFormSchema),
    defaultValues: EMPTY_FORM,
  });

  const { fields, append, remove } = useFieldArray({
    control,
    name: "variants",
  });

  useEffect(() => {
    if (!editingProduct) {
      reset(EMPTY_FORM);
      setSelectedFiles([]);
      setImagePreviews([]);
      return;
    }

    reset(mapProductToForm(editingProduct));

    setSelectedFiles([]);
    setImagePreviews(
      editingProduct.images?.map((image) => image.url) ?? [],
    );
  }, [editingProduct, reset]);

  useEffect(() => {
    return () => {
      imagePreviews.forEach((preview) => {
        if (preview.startsWith("blob:")) {
          URL.revokeObjectURL(preview);
        }
      });
    };
  }, [imagePreviews]);

  const handleImagesChange = (
    event: ChangeEvent<HTMLInputElement>,
  ) => {
    const files = Array.from(event.target.files ?? []);

    if (!files.length || !validateImages(files)) {
      return;
    }

    setSelectedFiles(files);
    setImagePreviews(
      files.map((file) => URL.createObjectURL(file)),
    );

    event.target.value = "";
  };

  const handleFormSubmit: SubmitHandler<ProductFormValues> = async (
    values,
  ) => {
    try {
      const formData = buildFormData(values, selectedFiles);

      if (editingProduct) {
        await adminProductAPI.update(editingProduct.id, formData);
        toast.success("Product updated successfully");
      } else {
        await adminProductAPI.create(formData);
        toast.success("Product created successfully");
      }

      await onSuccess();
      closeModal();
    } catch (error) {
      console.error("Product form submission failed:", error);

      toast.error(
        editingProduct
          ? "Failed to update product"
          : "Failed to create product",
      );
    }
  };

  const isEditing = Boolean(editingProduct);

  return (
    <Model
      title={isEditing ? "Edit Product" : "Create Product"}
      subTitle={
        isEditing
          ? "Update product information and inventory."
          : "Add a new product to your store."
      }
      isDisabled={isSubmitting}
      onClose={closeModal}
    >
      <form
        onSubmit={handleSubmit(handleFormSubmit)}
        className="space-y-6 p-5 sm:p-6"
      >
        {/* Basic Information */}
        <div>
          <SectionTitle
            title="Basic Information"
            description="Product details visible to customers."
          />

          <div className="mt-4 grid gap-4 sm:grid-cols-2">
            <FormField
              htmlFor="name"
              label="Product name"
              error={errors.name?.message}
              required
            >
              <input
                id="name"
                type="text"
                {...register("name")}
                placeholder="Enter product name"
                className={inputClass}
              />
            </FormField>

            <FormField
              htmlFor="sku"
              label="SKU"
              error={errors.sku?.message}
              required
            >
              <input
                id="sku"
                type="text"
                {...register("sku")}
                placeholder="e.g. PROD-001"
                className={inputClass}
              />
            </FormField>

            <FormField
              htmlFor="category"
              label="Category"
              error={errors.category_id?.message}
              required
            >
              <select
                id="category"
                {...register("category_id")}
                className={inputClass}
              >
                <option value="">Select category</option>

                {(categories ?? []).map((category) => (
                  <option key={category.id} value={category.id}>
                    {category.name}
                  </option>
                ))}
              </select>
            </FormField>

            <FormField
              htmlFor="brand"
              label="Brand"
              error={errors.brand_id?.message}
            >
              <select
                id="brand"
                {...register("brand_id")}
                className={inputClass}
              >
                <option value="">No brand</option>

                {(brands ?? []).map((brand) => (
                  <option key={brand.id} value={brand.id}>
                    {brand.name}
                  </option>
                ))}
              </select>
            </FormField>

            <FormField
              htmlFor="slug"
              label="Slug"
              error={errors.slug?.message}
            >
              <input
                id="slug"
                {...register("slug")}
                placeholder="product-slug"
                className={inputClass}
              />
            </FormField>

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
                  Product is active
                </span>
              </label>
            </FormField>
          </div>

          <div className="mt-4">
            <FormField
              htmlFor="description"
              label="Description"
              error={errors.description?.message}
            >
              <textarea
                id="description"
                {...register("description")}
                rows={4}
                placeholder="Describe the product..."
                className={`${inputClass} min-h-28 resize-y py-3`}
              />
            </FormField>
          </div>
        </div>

        {/* Pricing */}
        <div>
          <SectionTitle
            title="Pricing & Inventory"
            description="Set product pricing and available stock."
          />

          <div className="mt-4 grid gap-4 sm:grid-cols-3">
            <FormField
              htmlFor="price"
              label="Price"
              error={errors.price?.message}
              required
            >
              <input
                id="price"
                type="number"
                step="0.01"
                min="0"
                {...register("price")}
                className={inputClass}
              />
            </FormField>

            <FormField
              htmlFor="compare_price"
              label="Compare price"
              error={errors.compare_price?.message}
            >
              <input
                id="compare_price"
                type="number"
                step="0.01"
                min="0"
                {...register("compare_price")}
                placeholder="Optional"
                className={inputClass}
              />
            </FormField>

            <FormField
              htmlFor="stock_quantity"
              label="Stock quantity"
              error={errors.stock_quantity?.message}
            >
              <input
                id="stock_quantity"
                type="number"
                min="0"
                {...register("stock_quantity")}
                className={inputClass}
              />
            </FormField>
          </div>
        </div>

        {/* Images */}
        <div>
          <SectionTitle
            title="Product Images"
            description={`Upload up to ${MAX_IMAGES} product images.`}
          />

          <label className="mt-4 flex min-h-32 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-border bg-bg-secondary p-5 text-center transition hover:border-primary hover:bg-primary-50">
            <ImageIcon className="h-7 w-7 text-text-tertiary" />

            <span className="mt-2 text-sm font-medium text-text-primary">
              Choose images
            </span>

            <span className="mt-1 text-xs text-text-tertiary">
              JPG, PNG, WEBP and other supported image formats
            </span>

            <input
              type="file"
              accept="image/*"
              multiple
              onChange={handleImagesChange}
              className="hidden"
            />
          </label>

          {imagePreviews.length > 0 && (
            <div className="mt-4 grid grid-cols-3 gap-3 sm:grid-cols-5">
              {imagePreviews.map((src, index) => (
                <div
                  key={`${src}-${index}`}
                  className="relative aspect-square overflow-hidden rounded-xl border border-border bg-bg-secondary"
                >
                  <img
                    src={src}
                    alt={`Product ${index + 1}`}
                    className="h-full w-full object-cover"
                  />

                  {index === 0 && (
                    <span className="absolute left-1.5 top-1.5 rounded-md bg-primary px-1.5 py-1 text-[10px] font-semibold text-white">
                      Primary
                    </span>
                  )}
                </div>
              ))}
            </div>
          )}
        </div>

        {/* Variants */}
        <div>
          <div className="flex items-start justify-between gap-3">
            <SectionTitle
              title="Variants"
              description="Optional color, size and stock variations."
            />

            <button
              type="button"
              onClick={() => append(EMPTY_VARIANT)}
              className="inline-flex shrink-0 items-center gap-1.5 rounded-lg border border-border px-3 py-2 text-xs font-semibold text-primary transition hover:border-primary hover:bg-primary-50"
            >
              <Plus size={15} />
              Add Variant
            </button>
          </div>

          {fields.length > 0 ? (
            <div className="mt-4 space-y-3">
              {fields.map((field, index) => (
                <div
                  key={field.id}
                  className="rounded-xl border border-border bg-bg-secondary p-4"
                >
                  <div className="mb-3 flex items-center justify-between">
                    <span className="text-xs font-semibold uppercase tracking-wide text-text-secondary">
                      Variant {index + 1}
                    </span>

                    <button
                      type="button"
                      onClick={() => remove(index)}
                      className="rounded-lg p-1.5 text-error transition hover:bg-error-50"
                      aria-label={`Remove variant ${index + 1}`}
                    >
                      <X size={16} />
                    </button>
                  </div>

                  <div className="grid gap-3 sm:grid-cols-2">
                    <FormField
                      htmlFor={`variant-color-${index}`}
                      label="Color"
                      error={errors.variants?.[index]?.color?.message}
                    >
                      <input
                        id={`variant-color-${index}`}
                        {...register(`variants.${index}.color`)}
                        placeholder="e.g. Black"
                        className={inputClass}
                      />
                    </FormField>

                    <FormField
                      htmlFor={`variant-size-${index}`}
                      label="Size"
                      error={errors.variants?.[index]?.size?.message}
                    >
                      <input
                        id={`variant-size-${index}`}
                        {...register(`variants.${index}.size`)}
                        placeholder="e.g. M"
                        className={inputClass}
                      />
                    </FormField>

                    <FormField
                      htmlFor={`variant-sku-${index}`}
                      label="Variant SKU"
                      error={errors.variants?.[index]?.sku?.message}
                      required
                    >
                      <input
                        id={`variant-sku-${index}`}
                        {...register(`variants.${index}.sku`)}
                        placeholder="Variant SKU"
                        className={inputClass}
                      />
                    </FormField>

                    <FormField
                      htmlFor={`variant-price-${index}`}
                      label="Price override"
                      error={
                        errors.variants?.[index]?.price_override?.message
                      }
                    >
                      <input
                        id={`variant-price-${index}`}
                        type="number"
                        step="0.01"
                        min="0"
                        {...register(`variants.${index}.price_override`)}
                        placeholder="Optional"
                        className={inputClass}
                      />
                    </FormField>

                    <FormField
                      htmlFor={`variant-stock-${index}`}
                      label="Stock quantity"
                      error={
                        errors.variants?.[index]?.stock_quantity?.message
                      }
                    >
                      <input
                        id={`variant-stock-${index}`}
                        type="number"
                        min="0"
                        {...register(`variants.${index}.stock_quantity`)}
                        className={inputClass}
                      />
                    </FormField>
                  </div>
                </div>
              ))}
            </div>
          ) : (
            <div className="mt-4 rounded-xl border border-dashed border-border bg-bg-secondary p-6 text-center">
              <Package className="mx-auto h-7 w-7 text-text-tertiary" />

              <p className="mt-2 text-sm font-medium text-text-primary">
                No variants
              </p>

              <p className="mt-1 text-xs text-text-tertiary">
                Add variants if this product has different colors or sizes.
              </p>
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
              : isEditing
                ? "Update Product"
                : "Create Product"}
          </Button>
        </div>
      </form>
    </Model>
  );
};

export default CreateOrEdit;