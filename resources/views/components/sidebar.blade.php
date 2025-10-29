<div class="lg:hidden fixed top-4 left-4 z-50">
    <button id="mobileMenuButton" class="p-3 rounded-xl bg-primary-600 text-white shadow-lg cursor-pointer">
        <i class="fas fa-bars text-lg"></i>
    </button>
</div>

<div id="sidebarOverlay" class="lg:hidden fixed inset-0 bg-black/50 z-30 hidden"></div>

<div id="sidebar" class="fixed inset-y-0 left-0 z-40 w-80 bg-white shadow-xl transform lg:transform-none transition-transform duration-300 -translate-x-full lg:translate-x-0">
    <div class="flex flex-col h-full">
        <div class="flex items-center justify-center h-20 px-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-pie text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-sm text-gray-500">Admin Panel</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="{{ route("dashboard.index") }}" class="sidebar-item active">
                <i class="fas fa-tachometer-alt w-6 text-lg"></i>
                <span class="ml-3 font-medium">Dashboard</span>
            </a>
            <a href="{{ route("dashboard.users")}}" class="sidebar-item">
                <i class="fas fa-users w-6 text-lg"></i>
                <span class="ml-3 font-medium">Users</span>
                {{-- <span class="ml-auto bg-primary-100 text-primary-600 px-2 py-1 rounded-full text-xs">{{ $countUsers }}</span> --}}
            </a>
            <a href="{{ route("products.index")}}" class="sidebar-item">
                <i class="fas fa-box w-6 text-lg"></i>
                <span class="ml-3 font-medium">Products</span>
                {{-- <span class="ml-auto bg-primary-100 text-primary-600 px-2 py-1 rounded-full text-xs">{{ $countProducts }}</span> --}}
            </a>
            <a href="{{ route("categories.index")}}" class="sidebar-item">
                <i class="fas fa-tags w-6 text-lg"></i>
                <span class="ml-3 font-medium">Categories</span>
                {{-- <span class="ml-auto bg-primary-100 text-primary-600 px-2 py-1 rounded-full text-xs">{{ $countCategories }}</span> --}}
            </a>
            <a href="{{ route("dashboard.orders") }}" class="sidebar-item">
                <i class="fas fa-shopping-cart w-6 text-lg"></i>
                <span class="ml-3 font-medium">Orders</span>
                {{-- <span class="ml-auto bg-primary-100 text-primary-600 px-2 py-1 rounded-full text-xs">{{ $countOrders }}</span> --}}
            </a>
            
            <a href="{{ route("dashboard.settings") }}" class="sidebar-item">
                <i class="fas fa-cog w-6 text-lg"></i>
                <span class="ml-3 font-medium">Settings</span>
            </a>
        </nav>

        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0 h-10 w-10">
                    <img class="h-10 w-10 rounded-full border-2 border-gray-200 group-hover:border-primary-200 transition-colors" 
                            src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://via.placeholder.com/150/cccccc/969696?text=No+Image' }}" 
                            alt="{{ Auth::user()->name }}">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email}}</p>
                </div>
                <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg">
                    <i class="fas fa-chevron-down text-sm"></i>
                </button>
            </div>
        </div>
    </div>
</div>
