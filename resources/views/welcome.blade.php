<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT LICENSE */
                /* ... your existing Tailwind styles ... */
            </style>
        @endif
    </head>
    <body class="bg-light">
        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-sm border-b border-gray-200 ">
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-primary-500 rounded-lg"></div>
                        <span class="text-xl font-bold text-primary-600">{{ config('app.name', 'Shop') }}</span>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors font-medium">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-dark  hover:text-primary-600  transition-colors font-medium">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors font-medium">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex items-center justify-center pt-25 pb-10">
            <div class="container mx-auto px-6">
                <div class="max-w-4xl mx-auto text-center">
                    <!-- Hero Section -->
                    <div class="mb-12 animate-fade-in">
                        <h1 class="text-5xl lg:text-6xl font-bold mb-6 text-dark ">
                            Welcome to
                            <span class="text-primary-600 ">{{ config('app.name', 'Our Store') }}</span>
                        </h1>
                        <p class="text-xl text-gray-600  mb-8 max-w-2xl mx-auto leading-relaxed">
                            Discover amazing products at unbeatable prices. Shop with confidence and enjoy fast delivery right to your doorstep.
                        </p>
                    </div>

                    <!-- Features Grid -->
                    <div class="grid md:grid-cols-3 gap-8 mb-12">
                        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 ">
                            <div class="w-12 h-12 bg-primary-100  rounded-lg flex items-center justify-center mb-4 mx-auto">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2 text-dark ">Fast Shipping</h3>
                            <p class="text-gray-600 ">Free delivery on orders over $50</p>
                        </div>

                        <div class="bg-white  p-6 rounded-xl shadow-lg border border-gray-100 ">
                            <div class="w-12 h-12 bg-success/20 rounded-lg flex items-center justify-center mb-4 mx-auto">
                                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2 text-dark ">Secure Payment</h3>
                            <p class="text-gray-600 ">100% secure payment processing</p>
                        </div>

                        <div class="bg-white  p-6 rounded-xl shadow-lg border border-gray-100 ">
                            <div class="w-12 h-12 bg-warning/20 rounded-lg flex items-center justify-center mb-4 mx-auto">
                                <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2 text-dark ">24/7 Support</h3>
                            <p class="text-gray-600 ">Round-the-clock customer service</p>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div class="bg-primary-50  rounded-2xl p-8 lg:p-12 border border-primary-100">
                        <h2 class="text-3xl font-bold mb-4 text-dark">Ready to Start Shopping?</h2>
                        <p class="text-lg text-gray-600  mb-8">Join thousands of satisfied customers today.</p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors shadow-lg">
                                    Go to Dashboard
                                </a>
                                <a href="{{ url('/products') }}" class="border-2 border-primary-600 text-primary-600  hover:bg-primary-600 hover:text-white   px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                                    Browse Products
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors shadow-lg">
                                    Create Account
                                </a>
                                <a href="{{ route('login') }}" class="border-2 border-primary-600 text-primary-600   hover:bg-primary-600 hover:text-white  px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                                    Sign In
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white  border-t border-gray-200  py-8">
            <div class="container mx-auto px-6">
                <div class="text-center text-gray-600 ">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'Our Store') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>