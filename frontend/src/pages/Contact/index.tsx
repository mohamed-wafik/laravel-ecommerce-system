import FormContact from "./FormContact";
import ErrorSendEmail from "./ErrorSendEmail";
import { useState } from "react";
import SupportSoical from "./SupportSoical";
import ModelSuccess from "./ModelSuccess";

export default function ContactSupportPage() {
  const [sentEmail, setSentEmail] = useState<string | null>(null);
  const [isModelSuccessOpen, setIsModelSuccessOpen] = useState(false);

  if (sentEmail) {
    <ErrorSendEmail sentEmail={sentEmail} setSentEmail={setSentEmail} />;
  }

  return (
    <>
      <section className="mx-auto w-full max-w-6xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
        {/* Header */}
        <header className="mb-8 max-w-2xl sm:mb-10">
          <h1 className="text-3xl font-semibold tracking-tight text-text-primary sm:text-4xl">
            How can we help?
          </h1>

          <p className="mt-3 text-sm leading-6 text-text-secondary sm:text-base">
            Have a question about your order, payment, product, or anything
            else? Send us a message and our support team will get back to you.
          </p>
        </header>

        <div className="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_320px] lg:gap-8">
          {/* Form */}
          <FormContact
            setSentEmail={setSentEmail}
            openModelSuccess={setIsModelSuccessOpen}
          />

          {/* Support */}
          <SupportSoical />
        </div>
      </section>
      {isModelSuccessOpen && (
        <ModelSuccess onClose={() => setIsModelSuccessOpen(false)} />
      )}
    </>
  );
}
