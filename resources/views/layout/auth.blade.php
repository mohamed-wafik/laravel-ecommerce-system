<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Sign In')</title>
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
        @yield('content')
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
</html>