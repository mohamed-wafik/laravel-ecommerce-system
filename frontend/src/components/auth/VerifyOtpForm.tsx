import { useEffect, useRef, useState } from "react";
import { useForm, Controller } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { ShieldCheck, RotateCcw } from "lucide-react";
import type { OtpInputHandle } from "./OtpInput";
import SubmitButton from "./SubmitButton";
import { verifyOtpSchema, type VerifyOtpInput } from "../../schema/auth.schema";
import OtpInput from "./OtpInput";
import type { IVerfiyOpt } from "../../interface";

const RESEND_SECONDS = 60;

interface IProps {
  verifyOtpRegister: (data: IVerfiyOpt) => Promise<void>;
  isLoadingRegister: boolean;
  email: string;
  type?: string;
}
const VerifyOtpForm = ({
  type,
  email,
  isLoadingRegister,
  verifyOtpRegister,
}: IProps) => {
  // Countdown timer for resend button
  const [countdown, setCountdown] = useState(RESEND_SECONDS);
  const timerRef = useRef<ReturnType<typeof setInterval> | null>(null);
  const otpRef = useRef<OtpInputHandle>(null);

  useEffect(() => {
    timerRef.current = setInterval(() => {
      setCountdown((c) => {
        if (c <= 1) {
          clearInterval(timerRef.current!);
          return 0;
        }
        return c - 1;
      });
    }, 1000);
    return () => clearInterval(timerRef.current!);
  }, []);

  const handleResend = () => {
    if (!email) return;
    // Re-call register would normally be a dedicated resend endpoint
    // For now we just restart the timer
    setCountdown(RESEND_SECONDS);
    otpRef.current?.clear();
    clearInterval(timerRef.current!);
    timerRef.current = setInterval(() => {
      setCountdown((c) => {
        if (c <= 1) {
          clearInterval(timerRef.current!);
          return 0;
        }
        return c - 1;
      });
    }, 1000);
  };

  const {
    control,
    handleSubmit,
    formState: { errors },
  } = useForm<VerifyOtpInput>({
    resolver: zodResolver(verifyOtpSchema),
    defaultValues: { otp: "" },
  });

  const onSubmit = async ({ otp }: VerifyOtpInput) => {
    await verifyOtpRegister({
      code: otp,
      email: email,
      type,
    });
  };

  // Mask email for display: jo**@example.com
  const maskedEmail = email
    ? email.replace(/^(.{2})(.*)(@.*)$/, (_, a, _b, c) => `${a}**${c}`)
    : "your email";

  return (
    <div>
      {/* Header */}
      <div className="mb-8 text-center">
        <div className="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-5">
          <ShieldCheck className="w-8 h-8 text-primary" />
        </div>
        <h1 className="text-2xl font-extrabold text-dark mb-2">
          Verify your email
        </h1>
        <p className="text-sm text-gray-500 max-w-xs mx-auto">
          We sent a 6-digit code to{" "}
          <span className="font-semibold text-dark">{maskedEmail}</span>. Enter
          it below to activate your account.
        </p>
      </div>

      {/* Form */}
      <form onSubmit={handleSubmit(onSubmit)} noValidate>
        <div className="flex flex-col items-center gap-6">
          <Controller
            control={control}
            name="otp"
            render={({ field }) => (
              <OtpInput
                ref={otpRef}
                length={6}
                error={errors.otp?.message}
                disabled={isLoadingRegister}
                onChange={field.onChange}
              />
            )}
          />

          <SubmitButton loading={isLoadingRegister} loadingText="Verifying…">
            <ShieldCheck className="w-4 h-4" />
            Verify account
          </SubmitButton>
        </div>
      </form>

      {/* Resend */}
      <div className="mt-8 text-center">
        {countdown > 0 ? (
          <p className="text-sm text-gray-400">
            Resend code in
            <span className="font-semibold text-dark tabular-nums">
              0:{String(countdown).padStart(2, "0")}
            </span>
          </p>
        ) : (
          <button
            type="button"
            onClick={handleResend}
            className="inline-flex items-center gap-1.5 text-sm text-primary font-semibold hover:underline"
          >
            <RotateCcw className="w-3.5 h-3.5" />
            Resend code
          </button>
        )}
      </div>

      <p className="text-xs text-gray-400 text-center mt-4">
        Wrong email?{" "}
        <a
          href="/register"
          className="text-primary font-semibold hover:underline"
        >
          Go back
        </a>
      </p>
    </div>
  );
};

export default VerifyOtpForm;
