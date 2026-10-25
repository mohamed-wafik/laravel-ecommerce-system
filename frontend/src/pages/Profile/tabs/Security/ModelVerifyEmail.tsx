import VerifyOtpForm from "../../../../components/auth/VerifyOtpForm";
import type { IVerfiyOpt } from "../../../../interface";
import { useState } from "react";
import { profileAPI } from "../../../../api/modules/Customer/profile.api";
import toast from "react-hot-toast";
import { useAuthStore } from "../../../../store/useAuth";
import Button from "../../../../components/ui/Button";

interface IProps {
  email: string;
  isOpen: boolean;
  setIsOpen: (isOpen: boolean) => void;
}

const ModelVerifyEmail = ({ email, isOpen, setIsOpen }: IProps) => {
  const [isLoading, setIsLoading] = useState(false);
  const onSubmit = async (data: IVerfiyOpt) => {
    try {
      const res = await profileAPI.verifyEmailChange(data);
      console.log(res.data);
      useAuthStore.setState({ userIsLoggedIn: true, user: res.data.user });
      setIsOpen(false);
      toast.success("Email change verified successfully.");
    } catch (error) {
      toast.error("Failed to verify email change. Please try again.");
    } finally {
      setIsLoading(false);
    }
  };
  return isOpen ? (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 drop-shadow-2xl px-4">
      <div className="w-full max-w-md rounded-lg bg-white p-6 shadow-lg space-y-4">
        <VerifyOtpForm
          isLoadingRegister={isLoading}
          verifyOtpRegister={onSubmit}
          email={email}
        />
        <div className="flex justify-end">
          <Button onClick={() => setIsOpen(false)} variant="light">
            Cancel
          </Button>
        </div>
      </div>
    </div>
  ) : null;
};

export default ModelVerifyEmail;
