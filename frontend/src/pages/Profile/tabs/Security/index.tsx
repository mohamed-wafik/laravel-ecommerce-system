import ChangeEmailCard from "./ChangeEmailCard";
import ChangePasswordCard from "./ChangePasswordCard";

const SecurityTab = () => (
  <div className="space-y-5">
    <ChangePasswordCard />
    <ChangeEmailCard />
  </div>
);

export default SecurityTab;
