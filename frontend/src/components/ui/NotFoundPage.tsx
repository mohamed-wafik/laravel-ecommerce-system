import React from "react";
import { Link } from "react-router-dom";
import { AlertCircle, Home, ArrowLeft, Search } from "lucide-react";

const NotFoundPage: React.FC = () => {
  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-slate-50 to-red-50 px-4 text-center relative">
      {/* Animated 404 Number */}
      <div className="relative mb-8">
        <div className="text-9xl font-black text-gray-800 [animation:fade-in-up_1s_ease-out_0.2s_both]">
          4
          <span className="inline-block [animation:bounce-subtle_1.5s_ease-in-out_infinite]">
            0
          </span>
          4
        </div>

        {/* Floating Icons */}
        <AlertCircle className="absolute -top-4 -left-4 size-12 text-red-400 [animation:float_3s_ease-in-out_infinite]" />
        <Search className="absolute -top-4 -right-4 size-10 text-blue-400 [animation:float-reverse_3s_ease-in-out_infinite]" />
        <AlertCircle className="absolute -bottom-4 -left-8 size-8 text-orange-400 [animation:float-slow_4s_ease-in-out_infinite]" />
      </div>

      {/* Main Alert Icon */}
      <div className="relative mb-6">
        <div className="size-24 bg-red-100 rounded-full flex items-center justify-center [animation:pulse-gentle_2s_ease-in-out_infinite]">
          <AlertCircle className="size-12 text-red-500 [animation:wiggle_2s_ease-in-out_infinite]" />
        </div>
      </div>

      {/* Text Content */}
      <div className="space-y-4 mb-8 max-w-md">
        <h1 className="text-4xl font-bold text-gray-800 [animation:fade-in-up_0.8s_ease-out_0.4s_both]">
          Page Not Found
        </h1>
        <p className="text-lg text-gray-600 [animation:fade-in-up_0.8s_ease-out_0.6s_both]">
          Oops! The page you're looking for seems to have wandered off into the
          digital void.
        </p>
      </div>

      {/* Action Buttons */}
      <div className="flex flex-col sm:flex-row gap-4 [animation:fade-in-up_0.8s_ease-out_0.8s_both]">
        <Link
          to="/"
          className="flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl"
        >
          <Home className="size-5" />
          Go Home
        </Link>

        <button
          onClick={() => window.history.back()}
          className="flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300 hover:scale-105 border border-gray-200"
        >
          <ArrowLeft className="size-5" />
          Go Back
        </button>
      </div>

      {/* Decorative Small Floating Elements */}
      <div className="absolute bottom-10 left-10 size-6 bg-red-300 rounded-full opacity-60 [animation:float_4s_ease-in-out_infinite]"></div>
      <div className="absolute top-20 right-16 size-4 bg-blue-300 rounded-full opacity-60 [animation:float-reverse_3s_ease-in-out_infinite]"></div>
      <div className="absolute top-1/3 left-16 size-3 bg-orange-300 rounded-full opacity-60 [animation:float-slow_5s_ease-in-out_infinite]"></div>
    </div>
  );
};

export default NotFoundPage;
