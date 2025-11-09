@extends('layout.auth')
@section("title","Create Accunt")
@section('content')
    <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24">
        <div class="mx-auto w-full max-w-sm lg:w-96">
            <div>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-white text-xl"></i>
                    </div>
                    <span class="ml-3 text-2xl font-bold text-gray-900">E-Commerce</span>
                </div>
                <h2 class="mt-8 text-3xl font-bold text-gray-900">Create your account</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Join thousands of store owners managing their business with us
                </p>
            </div>

            <div class="mt-8">
                <!-- Social Registration Buttons -->
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('auth.google') }}" class="w-full inline-flex justify-center items-center py-3 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition duration-150">
                        <i class="fab fa-google text-red-500 mr-2"></i>
                        Google
                    </a>
                    <a href="{{ route('auth.facebook') }}" class="w-full inline-flex justify-center items-center py-3 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition duration-150">
                        <i class="fab fa-facebook text-blue-600 mr-2"></i>
                        Facebook
                    </a>
                </div>

                <div class="mt-6 relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gray-50 text-gray-500">Or register with email</span>
                    </div>
                </div>

                <!-- Registration Form -->
                <form class="mt-6 space-y-5" action="/register" method="POST">
                    @csrf
                    <!-- Name Fields -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">First name</label>
                            <div class="mt-1 relative">
                                <input id="name" name="name" type="text" autocomplete="given-name" required 
                                    class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 transition duration-150"
                                    placeholder="mohamed">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <div class="mt-1 relative">
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 transition duration-150"
                                placeholder="name@example.com">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Password Fields -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-1 relative">
                                <input id="password" name="password" type="password" autocomplete="new-password" required 
                                    class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 transition duration-150"
                                    placeholder="••••••••">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150">
                            Create Account
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <span class="text-sm text-gray-600">
                            Already have an account? 
                            <a href="{{route("login")}}" class="font-medium text-primary-600 hover:text-primary-500 transition duration-150">
                                Sign in here
                            </a>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection