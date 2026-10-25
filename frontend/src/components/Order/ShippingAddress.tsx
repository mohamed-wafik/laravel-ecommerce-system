import { MapPin, Phone, User } from "lucide-react";

import type { IOrder } from "../../interface/order";
import SectionCard from "../../pages/Admin/ProductDetails/SectionCard";
import SectionHeader from "../../pages/Admin/ProductDetails/SectionHeader";
interface ShippingAddressProps {
  order: IOrder;
}

const ShippingAddress = ({ order }: ShippingAddressProps) => {
  const address = order.shipping_address;

  if (!address) {
    return null;
  }

  return (
    <SectionCard>
      <SectionHeader
        title="Shipping Address"
        subTotal="Delivery information"
        icon={<MapPin size={18} className="text-primary" />}
      />

      <div className="p-4 sm:p-5">
        <div className="rounded-xl bg-bg-secondary p-4">
          {/* Recipient */}
          <div className="flex items-center gap-2">
            <User size={16} className="shrink-0 text-text-tertiary" />

            <span className="font-semibold text-text-primary">
              {address.full_name}
            </span>
          </div>

          {/* Phone */}
          <div className="mt-3 flex items-center gap-2 text-sm text-text-secondary">
            <Phone size={15} className="shrink-0 text-text-tertiary" />

            <span>{address.phone}</span>
          </div>

          {/* Address */}
          <address className="mt-3 not-italic text-sm leading-6 text-text-secondary">
            {address.street}
            <br />
            {address.city}, {address.country}
            {address.postal_code && `, ${address.postal_code}`}
          </address>
        </div>
      </div>
    </SectionCard>
  );
};

export default ShippingAddress;
