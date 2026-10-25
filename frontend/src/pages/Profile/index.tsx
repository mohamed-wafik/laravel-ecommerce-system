import { useState } from "react";
import type { ProfileTab } from "./ProfileSidebar";
import Breadcrumb from "../../components/ui/Breadcrumb";
import ProfileSidebar from "./ProfileSidebar";
import SecurityTab from "./tabs/Security";
import OverviewTab from "./tabs/OverviewTab";

const TAB_LABELS: Record<ProfileTab, string> = {
  overview: "Profile",
  security: "Security",
};

const ProfilePage = () => {
  const [activeTab, setActiveTab] = useState<ProfileTab>("overview");

  return (
    <section className="py-10 bg-gray-50 min-h-screen">
      <div className="container mx-auto px-4 max-w-5xl">
        {/* Breadcrumb */}
        <Breadcrumb
          items={[
            { label: "Home", href: "/" },

            { label: TAB_LABELS[activeTab] },
          ]}
        />

        <div className="flex items-center gap-3 mb-8">
          <div className="w-1 h-7 bg-primary rounded-full" />
          <h1 className="text-2xl font-extrabold text-dark">
            {TAB_LABELS[activeTab]}
          </h1>
        </div>

        <div className="flex flex-col lg:flex-row gap-6 lg:gap-10">
          <ProfileSidebar activeTab={activeTab} onTabChange={setActiveTab} />

          <div className="flex-1 min-w-0">
            {activeTab === "overview" && <OverviewTab />}
            {activeTab === "security" && <SecurityTab />}
          </div>
        </div>
      </div>
    </section>
  );
};

export default ProfilePage;
