<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard')</title>
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
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        mobileMenuButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });
        
        const currentUrl = window.location.href; 

        document.querySelectorAll("#dashboard_side nav ul li a").forEach(a => {
            if (a.getAttribute("href") === currentUrl.split("?")[0]) {
                console.log(a.getAttribute("href"))
                a.parentElement.classList.add("active"); 
            } else {
                a.parentElement.classList.remove("active");
            }
        });
        document.getElementById("userMenuButton").addEventListener("click" , () => {
            document.querySelector("#userMenuButton + ul").classList.toggle("hidden")
        })
    </script>
    @stack('script')
</body>
</html>
