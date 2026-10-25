import { useState } from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import toast from "react-hot-toast";
import { MapPin, Plus } from "lucide-react";

import { addressApi } from "../../api/modules/Customer/address.api";

import type { IAddress } from "../../interface";
import {
  addressSchema,
  type AddressFormValues,
} from "../../schema/shippingAddress.schema";
import AddressModal from "./AddressModel";
import AddressCard from "./AddressCard";
import EmptyState from "./EmptyState";
import AddressSkeleton from "./AddressSkeleton";
import Breadcrumb from "../../components/ui/Breadcrumb";

interface AddressResponse {
  success: boolean;
  message: string;
  data: IAddress[];
}

const emptyForm: AddressFormValues = {
  label: "home",
  full_name: "",
  phone: "",
  country: "Egypt",
  city: "",
  street: "",
  postal_code: "",
  is_default: false,
};

const AddressesPage = () => {
  const [isModalOpen, setIsModalOpen] = useState<boolean>(false);
  const [isDeleteAddress, setIsDeleteAddress] = useState<boolean>(false);
  const [editingAddress, setEditingAddress] = useState<IAddress | null>(null);

  const { data: response, isLoading, isError, refetch } = addressApi.list();

  const addresses: IAddress[] =
    (response as AddressResponse | undefined)?.data ?? [];

  const form = useForm<AddressFormValues>({
    resolver: zodResolver(addressSchema),
    defaultValues: emptyForm,
  });

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors, isSubmitting },
  } = form;

  const openCreateModal = () => {
    setEditingAddress(null);

    reset({
      ...emptyForm,
      is_default: addresses.length === 0,
    });

    setIsModalOpen(true);
  };

  const openEditModal = (address: IAddress) => {
    setEditingAddress(address);

    reset({
      label: address.label,
      full_name: address.full_name,
      phone: address.phone,
      country: address.country,
      city: address.city,
      street: address.street,
      postal_code: address.postal_code,
      is_default: address.is_default,
    });

    setIsModalOpen(true);
  };

  const closeModal = () => {
    if (isSubmitting) return;

    setIsModalOpen(false);
    setEditingAddress(null);
    reset(emptyForm);
  };

  const onSubmit = async (values: AddressFormValues) => {
    setIsDeleteAddress(true);
    try {
      if (editingAddress) {
        await addressApi.update(editingAddress.id, values);

        toast.success("Address updated successfully");
      } else {
        await addressApi.create(values);

        toast.success("Address added successfully");
      }

      closeModal();
      await refetch();
    } catch (error: any) {
      const message =
        error?.response?.data?.message ||
        "Something went wrong. Please try again.";

      toast.error(message);
    } finally {
      setIsDeleteAddress(false);
    }
  };

  const handleDelete = async (id: string) => {
    try {
      await addressApi.delete(id);

      toast.success("Address deleted successfully");

      await refetch();
    } catch (error: any) {
      const message =
        error?.response?.data?.message || "Unable to delete address.";

      toast.error(message);
    }
  };

  return (
    <section className="min-h-screen bg-bg-secondary px-4 py-8 sm:px-6 lg:px-8">
      <div className="mx-auto max-w-6xl">
        {/* Header */}
        <div className="mb-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <Breadcrumb
              items={[{ label: "Home", href: "/" }, { label: "Address" }]}
            />

            <div className="flex items-center gap-3 mb-8">
              <div className="w-1 h-7 bg-primary rounded-full" />
              <h1 className="text-2xl font-extrabold text-dark">Address</h1>
            </div>
            <p className="mt-2 max-w-xl text-sm leading-6 text-text-secondary">
              Manage your saved delivery addresses and choose your default
              shipping location.
            </p>
          </div>

          <button
            type="button"
            onClick={openCreateModal}
            className="inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-dark active:scale-[0.98]"
          >
            <Plus size={18} />
            Add Address
          </button>
        </div>

        {/* Loading */}
        {isLoading && <AddressSkeleton />}

        {/* Error */}
        {!isLoading && isError && (
          <div className="rounded-2xl border border-error-100 bg-error-50 p-8 text-center">
            <div className="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-error-100 text-error">
              <MapPin size={22} />
            </div>

            <h2 className="font-semibold text-error-dark">
              Unable to load addresses
            </h2>

            <p className="mt-1 text-sm text-error">Please try again.</p>

            <button
              type="button"
              onClick={() => refetch()}
              className="mt-5 rounded-lg bg-error px-4 py-2 text-sm font-semibold text-white transition hover:bg-error-dark"
            >
              Try Again
            </button>
          </div>
        )}

        {/* Empty */}
        {!isLoading && !isError && addresses.length === 0 && (
          <EmptyState onAdd={openCreateModal} />
        )}

        {/* Addresses */}
        {!isLoading && !isError && addresses.length > 0 && (
          <div className="grid gap-5 md:grid-cols-2">
            {addresses.map((address) => (
              <AddressCard
                key={address.id}
                address={address}
                isLoading={isDeleteAddress}
                onEdit={() => openEditModal(address)}
                onDelete={() => handleDelete(address.id)}
              />
            ))}
          </div>
        )}
      </div>

      {/* Modal */}
      {isModalOpen && (
        <AddressModal
          editingAddress={editingAddress}
          register={register}
          errors={errors}
          isSubmitting={isSubmitting}
          onSubmit={handleSubmit(onSubmit)}
          onClose={closeModal}
        />
      )}
    </section>
  );
};

export default AddressesPage;
