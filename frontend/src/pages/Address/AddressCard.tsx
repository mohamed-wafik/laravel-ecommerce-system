import {
  Building2,
  Home,
  MapPin,
  Pencil,
  Phone,
  Trash2,
  User,
} from "lucide-react";
import type { IAddress } from "../../interface";
import Alert from "../../components/ui/Alert";
import { useState } from "react";
import Button from "../../components/ui/Button";

interface IProps {
  isLoading: boolean;
  address: IAddress;
  onEdit: () => void;
  onDelete: () => void;
}

const AddressCard = ({ isLoading, address, onEdit, onDelete }: IProps) => {
  const [isShow, setIsShow] = useState<boolean>(false);
  const getLabelIcon = () => {
    const label = address.label.toLowerCase();

    if (label === "home") {
      return <Home size={18} />;
    }

    if (label === "work" || label === "office") {
      return <Building2 size={18} />;
    }

    return <MapPin size={18} />;
  };

  return (
    <>
      <article
        className={`relative overflow-hidden rounded-2xl border bg-bg-primary p-5 shadow-sm transition hover:shadow-md ${
          address.is_default
            ? "border-primary/40 ring-1 ring-primary/10"
            : "border-border"
        }`}
      >
        {/* Default indicator */}
        {address.is_default && (
          <div className="absolute right-0 top-0 rounded-bl-xl bg-primary px-3 py-1.5 text-xs font-semibold text-white">
            Default
          </div>
        )}

        {/* Card Header */}
        <div className="flex items-start justify-between gap-4">
          <div className="flex items-center gap-3">
            <div
              className={`flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ${
                address.is_default
                  ? "bg-primary-50 text-primary"
                  : "bg-light-secondary text-text-secondary"
              }`}
            >
              {getLabelIcon()}
            </div>

            <div>
              <h2 className="font-semibold capitalize text-text-primary">
                {address.label}
              </h2>

              {address.is_default && (
                <span className="mt-0.5 block text-xs text-primary">
                  Default delivery address
                </span>
              )}
            </div>
          </div>
        </div>

        {/* Details */}
        <div className="mt-6 space-y-3">
          <div className="flex gap-3">
            <User size={17} className="mt-0.5 shrink-0 text-text-tertiary" />

            <span className="text-sm font-medium text-text-primary">
              {address.full_name}
            </span>
          </div>

          <div className="flex gap-3">
            <Phone size={17} className="mt-0.5 shrink-0 text-text-tertiary" />

            <span className="text-sm text-text-secondary">{address.phone}</span>
          </div>

          <div className="flex gap-3">
            <MapPin size={17} className="mt-0.5 shrink-0 text-text-tertiary" />

            <div className="text-sm leading-6 text-text-secondary">
              <p>{address.street}</p>
              <p>
                {address.city}, {address.country}
              </p>
              <p>{address.postal_code}</p>
            </div>
          </div>
        </div>

        {/* Actions */}
        <div className="mt-6 flex items-center gap-3 border-t border-border-light pt-4">
          <button
            type="button"
            onClick={onEdit}
            className="inline-flex flex-1 items-center justify-center gap-2 rounded-lg border border-border px-4 py-2.5 text-sm font-medium text-text-primary transition hover:border-primary hover:text-primary"
          >
            <Pencil size={16} />
            Edit
          </button>

          <button
            type="button"
            onClick={() => setIsShow(true)}
            className="inline-flex items-center justify-center gap-2 rounded-lg border border-error-100 px-4 py-2.5 text-sm font-medium text-error transition hover:bg-error-50"
          >
            <Trash2 size={16} />
            <span className="hidden sm:inline">Delete</span>
          </button>
        </div>
      </article>
      {isShow && (
        <Alert
          title="Delete Address?"
          subTitle="Are you sure you want to delete this address? This action cannot be undone."
          onClose={() => setIsShow(false)}
        >
          <Button
            onClick={() => setIsShow(false)}
            disabled={isLoading}
            variant="light"
            width="full"
          >
            Cancel
          </Button>

          <Button
            variant="destructive"
            loading={isLoading}
            width="full"
            onClick={onDelete}
          >
            {!isLoading && <Trash2 className="mr-1.5 h-3.5 w-3.5" />}
            Delete
          </Button>
        </Alert>
      )}
    </>
  );
};
export default AddressCard;
