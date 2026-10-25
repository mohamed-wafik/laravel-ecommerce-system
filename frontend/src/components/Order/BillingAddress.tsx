import { MapPin } from "lucide-react";

import type { IOrder } from "../../interface/order";
import SectionCard from "../../pages/Admin/ProductDetails/SectionCard";
import InfoItem from "../ui/InfoItem";

interface BillingAddressProps {
  order: IOrder;
}

const BillingAddress = ({ order }: BillingAddressProps) => {
  const address = order.billing_address;

  if (!address) {
    return null;
  }

  return (
    <SectionCard>
      <div className="flex items-center gap-2 border-b border-border px-4 py-4 sm:px-5">
        <MapPin size={18} className="text-primary" />

        <h2 className="text-sm font-semibold text-text-primary">
          Billing Address
        </h2>
      </div>

      <div className="grid gap-4 p-4 sm:grid-cols-2 sm:p-5">
        <InfoItem label="Full Name" value={address.full_name} />

        <InfoItem label="Phone" value={address.phone} />

        <InfoItem label="Street" value={address.street} />

        <InfoItem label="City" value={address.city} />

        <InfoItem label="Postal Code" value={address.postal_code} />

        <InfoItem label="Country" value={address.country} />
      </div>
    </SectionCard>
  );
};

export default BillingAddress;
