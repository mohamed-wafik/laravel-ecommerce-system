import { Outlet } from "react-router-dom";

import { useState } from "react";
import AdminAside from "../components/layout/AdminAside";
import AdminHeader from "../components/layout/AdminHeader";

const AdminLayout = () => {
  const [sidebarOpen, setSidebarOpen] = useState(false);

  return (
    <div className="min-h-screen bg-bg-secondary">
      <div className="flex min-h-screen">
        <AdminAside open={sidebarOpen} onClose={() => setSidebarOpen(false)} />

        <div className="flex min-w-0 flex-1 flex-col ps-0 lg:ps-64">
          <AdminHeader onMenuClick={() => setSidebarOpen(true)} />

          <main className="flex-1 p-4 md:p-6 lg:p-8">
            <Outlet />
          </main>
        </div>
      </div>
    </div>
  );
};
export default AdminLayout;
