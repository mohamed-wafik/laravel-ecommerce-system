import React from "react";
import { Link } from "react-router-dom";
import { XCircle, Home, RefreshCw, AlertTriangle } from "lucide-react";

interface ErrorPageProps {
  message?: string;
  errorCode?: string;
  showRetry?: boolean;
}

const ErrorPage: React.FC<ErrorPageProps> = ({
  message = "Something went wrong. Please try again later.",
  errorCode = "500",
  showRetry = false,
}) => {
  const handleRetry = () => {
    window.location.reload();
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-red-50 to-orange-50 px-4 text-center relative">
      {/* Animated Error Code */}
      <div className="relative mb-8">
        <div className="text-8xl font-black text-red-600 [animation:1s_ease-out_0.2s_both_fadeInUp]">
          {errorCode.split("").map((char, index) => (
            <span
              key={index}
              className="inline-block [animation:1.5s_ease-in-out_infinite_bounce-subtle]"
              style={{ animationDelay: `${index * 0.1}s` }}
            >
              {char}
            </span>
          ))}
        </div>

        {/* Floating Icons */}
        <AlertTriangle className="absolute -top-2 -left-4 size-10 text-red-400 [animation:3s_ease-in-out_infinite_float]" />
        <XCircle className="absolute -top-2 -right-4 size-10 text-orange-400 [animation:3s_ease-in-out_infinite_float-reverse]" />
        <AlertTriangle className="absolute -bottom-4 left-4 size-8 text-yellow-400 [animation:4s_ease-in-out_infinite_float-slow]" />
      </div>

      {/* Main Error Icon */}
      <div className="relative mb-6">
        <div className="size-28 bg-red-100 rounded-full flex items-center justify-center border-4 border-red-200 [animation:2s_ease-in-out_infinite_pulse-gentle]">
          <XCircle className="size-14 text-red-500 [animation:2s_ease-in-out_infinite_wiggle]" />
        </div>

        {/* Pulsing Rings */}
        <div className="absolute inset-0 border-4 border-red-200 rounded-full [animation:2s_ease-out_infinite_pulse-ring]"></div>
        <div className="absolute inset-0 border-4 border-red-200 rounded-full [animation:2s_ease-out_0.5s_infinite_pulse-ring]"></div>
      </div>

      {/* Text Content */}
      <div className="space-y-4 mb-8 max-w-md">
        <h1 className="text-3xl font-bold text-gray-800 [animation:0.8s_ease-out_0.4s_both_fadeInUp]">
          Oops! Something Went Wrong
        </h1>
        <p className="text-lg text-gray-600 leading-relaxed [animation:0.8s_ease-out_0.6s_both_fadeInUp]">
          {message}
        </p>
        <p className="text-sm text-gray-500 [animation:0.8s_ease-out_0.7s_both_fadeInUp]">
          We apologize for the inconvenience. Please try again or contact
          support if the problem persists.
        </p>
      </div>

      {/* Action Buttons */}
      <div className="flex flex-col sm:flex-row gap-4 [animation:0.8s_ease-out_0.8s_both_fadeInUp]">
        <Link
          to="/"
          className="flex items-center justify-center gap-3 px-8 py-4 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl font-medium"
        >
          <Home className="size-5" />
          Go Home
        </Link>

        {showRetry && (
          <button
            onClick={handleRetry}
            className="flex items-center justify-center gap-3 px-8 py-4 bg-white text-red-600 rounded-xl hover:bg-gray-50 transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl border border-red-200 font-medium"
          >
            <RefreshCw className="size-5" />
            Try Again
          </button>
        )}

        <button
          onClick={() => window.history.back()}
          className="flex items-center justify-center gap-3 px-8 py-4 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300 hover:scale-105 border border-gray-200 font-medium"
        >
          ← Go Back
        </button>
      </div>

      {/* Technical Details */}
      <details className="mt-8 text-left max-w-md [animation:0.8s_ease-out_1s_both_fadeInUp]">
        <summary className="cursor-pointer text-sm text-gray-500 hover:text-gray-700 transition-colors">
          Technical Details
        </summary>
        <div className="mt-2 p-4 bg-white rounded-lg border border-gray-200 text-xs text-gray-600">
          <p>Error Code: {errorCode}</p>
          <p>Timestamp: {new Date().toLocaleString()}</p>
          <p>User Agent: {navigator.userAgent}</p>
        </div>
      </details>

      {/* Decorative Elements */}
      <div className="absolute bottom-10 left-10 size-6 bg-red-300 rounded-full opacity-40 [animation:4s_ease-in-out_infinite_float]"></div>
      <div className="absolute top-20 right-16 size-4 bg-orange-300 rounded-full opacity-40 [animation:3s_ease-in-out_infinite_float-reverse]"></div>
      <div className="absolute top-1/3 left-16 size-3 bg-yellow-300 rounded-full opacity-40 [animation:5s_ease-in-out_infinite_float-slow]"></div>
      <div className="absolute bottom-20 right-20 size-5 bg-red-200 rounded-full opacity-30 [animation:6s_ease-in-out_infinite_float]"></div>
    </div>
  );
};

export default ErrorPage;
