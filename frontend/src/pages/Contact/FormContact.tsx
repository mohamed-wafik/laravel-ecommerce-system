import { Loader2, Send } from "lucide-react";
import { getErrorMessage } from "../../lib/utils";

import { useEffect, useState } from "react";

import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";

import Input from "../../components/ui/Input";
import { useAuthStore } from "../../store/useAuth";

import {
  contactSupportSchema,
  type ContactSupportInput,
} from "../../schema/support.schema";
import { contactSupportAPI } from "../../api/modules/Customer/contactSupport.api";

import FormField from "../../components/ui/FormField";
import { SUPPORT_SUBJECTS } from "../../data/SuppotContact";
interface IProp {
  setSentEmail: (val: string | null) => void;
  openModelSuccess: (val: boolean) => void;
}
const FormContact = ({ setSentEmail, openModelSuccess }: IProp) => {
  const { user } = useAuthStore();

  const [submitError, setSubmitError] = useState<string | null>(null);

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors, isSubmitting },
  } = useForm<ContactSupportInput>({
    resolver: zodResolver(contactSupportSchema),
    defaultValues: {
      order_id: "",
      name: "",
      email: "",
      phone: "",
      subject: undefined,
      message: "",
    },
  });

  useEffect(() => {
    if (!user) return;

    reset((current) => ({
      ...current,
      name: current.name || user.name || "",
      email: current.email || user.email || "",
    }));
  }, [user, reset]);

  const onSubmit = async (values: ContactSupportInput) => {
    setSubmitError(null);

    try {
      const payload = {
        ...values,
        order_id: values.order_id || undefined,
        phone: values.phone || undefined,
      };

      await contactSupportAPI.create(payload);

      setSentEmail(values.email);
      openModelSuccess(true);

      reset({
        order_id: "",
        name: user?.name ?? "",
        email: user?.email ?? "",
        phone: "",
        subject: undefined,
        message: "",
      });
    } catch (error) {
      setSubmitError(getErrorMessage(error));
    }
  };

  return (
    <form
      onSubmit={handleSubmit(onSubmit)}
      noValidate
      className="rounded-3xl border border-border bg-bg-primary p-5 shadow-sm sm:p-8"
    >
      <div className="mb-7">
        <h2 className="text-lg font-semibold text-text-primary">
          Send us a message
        </h2>

        <p className="mt-1 text-sm text-text-tertiary">
          Fields marked with * are required.
        </p>
      </div>

      <div className="grid grid-cols-1 gap-5 sm:grid-cols-2">
        {/* Name */}
        <FormField
          label="Name"
          htmlFor="name"
          required
          error={errors.name?.message}
        >
          <Input
            id="name"
            type="text"
            placeholder="Jane Doe"
            autoComplete="name"
            variant={errors.name ? "error" : "default"}
            aria-invalid={!!errors.name}
            aria-describedby={errors.name ? "name-error" : undefined}
            {...register("name")}
          />
        </FormField>

        {/* Email */}
        <FormField
          label="Email"
          htmlFor="email"
          required
          error={errors.email?.message}
        >
          <Input
            id="email"
            type="email"
            placeholder="jane@example.com"
            autoComplete="email"
            variant={errors.email ? "error" : "default"}
            aria-invalid={!!errors.email}
            aria-describedby={errors.email ? "email-error" : undefined}
            {...register("email")}
          />
        </FormField>

        {/* Phone */}
        <FormField label="Phone" htmlFor="phone" error={errors.phone?.message}>
          <Input
            id="phone"
            type="tel"
            placeholder="+1 (800) 555-0134"
            autoComplete="tel"
            variant={errors.phone ? "error" : "default"}
            aria-invalid={!!errors.phone}
            aria-describedby={errors.phone ? "phone-error" : undefined}
            {...register("phone")}
          />
        </FormField>

        {/* Order ID */}
        <FormField
          label="Order ID"
          htmlFor="order_id"
          error={errors.order_id?.message}
        >
          <Input
            id="order_id"
            type="text"
            placeholder="Order UUID"
            variant={errors.order_id ? "error" : "default"}
            aria-invalid={!!errors.order_id}
            aria-describedby={errors.order_id ? "order_id-error" : undefined}
            {...register("order_id")}
          />
        </FormField>

        {/* Subject */}
        <FormField
          label="Subject"
          htmlFor="subject"
          required
          error={errors.subject?.message}
        >
          <select
            id="subject"
            className={`h-10 w-full rounded-xl border bg-bg-primary px-4 text-sm text-text-primary outline-none transition-all focus:ring-2 focus:ring-offset-1 ${
              errors.subject
                ? "border-error focus:border-error focus:ring-error-100"
                : "border-border-dark focus:border-primary focus:ring-primary-100"
            }`}
            defaultValue=""
            aria-invalid={!!errors.subject}
            aria-describedby={errors.subject ? "subject-error" : undefined}
            {...register("subject")}
          >
            <option value="" disabled>
              Choose a topic
            </option>

            {SUPPORT_SUBJECTS.map((subject) => (
              <option key={subject.value} value={subject.value}>
                {subject.label}
              </option>
            ))}
          </select>
        </FormField>

        {/* Message */}
        <div className="sm:col-span-2">
          <FormField
            label="Message"
            htmlFor="message"
            required
            error={errors.message?.message}
          >
            <textarea
              id="message"
              rows={6}
              placeholder="Tell us what's going on..."
              className={`w-full resize-none rounded-xl border bg-bg-primary px-4 py-3 text-sm text-text-primary placeholder:text-text-tertiary outline-none transition-all focus:ring-2 focus:ring-offset-1 ${
                errors.message
                  ? "border-error focus:border-error focus:ring-error-100"
                  : "border-border-dark focus:border-primary focus:ring-primary-100"
              }`}
              aria-invalid={!!errors.message}
              aria-describedby={errors.message ? "message-error" : undefined}
              {...register("message")}
            />
          </FormField>
        </div>
      </div>

      {/* API Error */}
      {submitError && (
        <div
          className="mt-5 rounded-xl border border-error-100 bg-error-50 px-4 py-3 text-sm text-error-dark"
          role="alert"
        >
          {submitError}
        </div>
      )}

      {/* Submit */}
      <div className="mt-7 flex justify-end">
        <button
          type="submit"
          disabled={isSubmitting}
          className="inline-flex h-11 items-center justify-center gap-2 rounded-full bg-primary px-6 text-sm font-medium text-text-light transition-all hover:bg-primary-dark disabled:cursor-not-allowed disabled:opacity-60"
        >
          {isSubmitting ? (
            <>
              <Loader2 className="h-4 w-4 animate-spin" />
              Sending...
            </>
          ) : (
            <>
              <Send className="h-4 w-4" />
              Send message
            </>
          )}
        </button>
      </div>
    </form>
  );
};

export default FormContact;
