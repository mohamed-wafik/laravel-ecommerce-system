import { User } from "lucide-react";

// import type { IOrder } from "../../interface/order";
import SectionCard from "../../pages/Admin/ProductDetails/SectionCard";
import SectionHeader from "../../pages/Admin/ProductDetails/SectionHeader";
import InfoItem from "../ui/InfoItem";
import type { IUser } from "../../interface";

interface CustomerInformationProps {
  user: IUser;
}

const CustomerInformation = ({ user }: CustomerInformationProps) => {
  return (
    <SectionCard>
      <SectionHeader
        title="Customer"
        icon={<User size={18} className="text-primary" />}
      />

      <div className="grid gap-4 p-4 sm:grid-cols-2 sm:p-5">
        <InfoItem label="Name" value={user?.name ?? "Unknown customer"} />

        <InfoItem label="Email" value={user?.email ?? "No email"} />

        <InfoItem label="Phone" value={user?.phone ?? "No phone"} />

        <InfoItem
          label="User ID"
          value={<span className="break-all font-mono text-xs">{user.id}</span>}
        />
      </div>
    </SectionCard>
  );
};

export default CustomerInformation;
