@extends('layout.auth')
@section("title","Sign In")
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
                    <h2 class="mt-8 text-3xl font-bold text-gray-900">Sign in to your account</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Get access to your account and manage your store
                    </p>
                </div>

                <div class="mt-8">
                    <!-- Social Login Buttons -->
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
                            <span class="px-2 bg-gray-50 text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <!-- Login Form -->
                    <form class="mt-6 space-y-6" action="/login" method="POST">
                        @csrf
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

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-1 relative">
                                <input id="password" name="password" type="password" autocomplete="current-password" required 
                                    class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 transition duration-150"
                                    placeholder="Enter your password">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember_me" type="checkbox" 
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                                    Remember me
                                </label>
                            </div>

                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-primary-600 hover:text-primary-500 transition duration-150">
                                    Forgot your password?
                                </a>
                            </div>
                        </div>

                        <div>
                            <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150">
                                Sign in
                            </button>
                        </div>

                        <div class="text-center">
                            <span class="text-sm text-gray-600">
                                Don't have an account? 
                                <a href="{{ route("register") }}" class="font-medium text-primary-600 hover:text-primary-500 transition duration-150">
                                    Create an account
                                </a>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign In</title>
     @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        }
        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }
    </style>
</head>
<body class="bg-gray-50">
        @include("components.toast")
    <div class="min-h-screen flex">
        <!-- Left Side - Login Form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-bag text-white text-xl"></i>
                        </div>
                        <span class="ml-3 text-2xl font-bold text-gray-900">E-Commerce</span>
                    </div>
                    <h2 class="mt-8 text-3xl font-bold text-gray-900">Sign in to your account</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Get access to your account and manage your store
                    </p>
                </div>

                <div class="mt-8">
                    <!-- Social Login Buttons -->
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
                            <span class="px-2 bg-gray-50 text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <!-- Login Form -->
                    <form class="mt-6 space-y-6" action="/login" method="POST">
                        @csrf
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

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-1 relative">
                                <input id="password" name="password" type="password" autocomplete="current-password" required 
                                    class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 transition duration-150"
                                    placeholder="Enter your password">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember_me" type="checkbox" 
                                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                                    Remember me
                                </label>
                            </div>

                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-primary-600 hover:text-primary-500 transition duration-150">
                                    Forgot your password?
                                </a>
                            </div>
                        </div>

                        <div>
                            <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150">
                                Sign in
                            </button>
                        </div>

                        <div class="text-center">
                            <span class="text-sm text-gray-600">
                                Don't have an account? 
                                <a href="{{ route("register") }}" class="font-medium text-primary-600 hover:text-primary-500 transition duration-150">
                                    Create an account
                                </a>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side - Branding/Image -->
        <div class="hidden lg:block relative w-0 flex-1">
            <div class="bg-gradient-primary absolute inset-0 h-full w-full flex items-center justify-center">
                <div class="max-w-md text-center text-white px-8">
                    <i class="fas fa-chart-line text-white text-6xl mb-8 opacity-90"></i>
                    <h3 class="text-3xl font-bold mb-4">E-Commerce Analytics</h3>
                    <p class="text-xl opacity-90 mb-6">Powerful insights for your online store</p>
                    <div class="space-y-4 text-left">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-white opacity-90 mr-3"></i>
                            <span>Real-time sales analytics</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-white opacity-90 mr-3"></i>
                            <span>Customer behavior insights</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-white opacity-90 mr-3"></i>
                            <span>Inventory management</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-white opacity-90 mr-3"></i>
                            <span>Revenue tracking</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> --}}