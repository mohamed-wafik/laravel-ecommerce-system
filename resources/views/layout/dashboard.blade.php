<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard')</title>
        <style> 
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }

            .animate-shake {
                animation: shake 0.3s ease-in-out;
            }

            #image {
                position: absolute;
                opacity: 0;
                pointer-events: none;
            }
    </style>
    @vite('resources/css/app.css')
    @stack('style')
</head>
<body >
    @include('components.sidebar')
    
        
    <div class="lg:ml-80 min-h-screen">
        @include("components.header")
        <main class="p-6">
            @yield('content')
        </main>
    </div>


    @include("components.toast")
    @vite('resources/js/app.js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const links = document.querySelectorAll("#sidebar .sidebar-item");
        const pathname = location.origin + location.pathname;
        links.forEach(link => {
            if(link.href === pathname) {
                link.classList.add("active");
            } else {
                link.classList.remove("active");
            }
        })
    </script>
    @stack('script')
</body>
</html>
