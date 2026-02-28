<!-- Mobile Menu Button -->
<div class="lg:hidden fixed top-4 left-4 z-50">
    <button id="mobileMenuButton" class="p-3 rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 text-white shadow-2xl hover:shadow-primary-500/50 transition-all duration-300 hover:scale-110 active:scale-95">
        <i id="menuIcon" class="fas fa-bars text-lg transition-transform duration-300"></i>
    </button>
</div>

<!-- Sidebar Overlay (Mobile) -->
<div id="sidebarOverlay" class="lg:hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-30 hidden opacity-0 transition-opacity duration-300"></div>

<!-- Sidebar -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-[1000] w-80 bg-gradient-to-b from-white to-gray-50 shadow-2xl transform lg:transform-none transition-all duration-300 -translate-x-full lg:translate-x-0">
    <div class="flex flex-col h-full">
        <!-- Logo/Brand Section -->
        <div class="relative h-20 px-6 border-b border-gray-200 bg-white">
            <div class="flex items-center justify-between h-full">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-600 to-primary-700 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30 transform hover:rotate-6 transition-transform duration-300">
                        <i class="fas fa-chart-pie text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
                        <p class="text-xs text-gray-500 font-medium">Admin Panel</p>
                    </div>
                </div>
                <!-- Close button for mobile -->
                <button id="closeSidebarButton" class="lg:hidden p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Navigation Section -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
            <!-- Main Menu Label -->
            <div class="px-3 mb-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Main Menu</span>
            </div>

            <a href="{{ route('dashboard.index') }}" class="sidebar-item group active">
                <div class="flex items-center flex-1">
                    <div class="w-10 h-10 rounded-lg bg-primary-100 group-hover:bg-primary-200 flex items-center justify-center transition-all duration-200 group-[.active]:bg-primary-600">
                        <i class="fas fa-tachometer-alt text-lg text-primary-600 group-[.active]:text-white transition-colors"></i>
                    </div>
                    <span class="ml-3 font-semibold text-gray-700 group-hover:text-gray-900 group-[.active]:text-primary-600">Dashboard</span>
                </div>
                <i class="fas fa-chevron-right text-xs text-gray-400 opacity-0 group-hover:opacity-100 group-[.active]:opacity-100 transition-all"></i>
            </a>

            <a href="{{ route('dashboard.users') }}" class="sidebar-item group">
                <div class="flex items-center flex-1">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center transition-all duration-200 group-[.active]:bg-blue-600">
                        <i class="fas fa-users text-lg text-blue-600 group-[.active]:text-white transition-colors"></i>
                    </div>
                    <span class="ml-3 font-semibold text-gray-700 group-hover:text-gray-900 group-[.active]:text-blue-600">Users</span>
                </div>
                <div class="flex items-center gap-2">
                    {{-- <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs font-bold">{{ $countUsers }}</span> --}}
                    <i class="fas fa-chevron-right text-xs text-gray-400 opacity-0 group-hover:opacity-100 group-[.active]:opacity-100 transition-all"></i>
                </div>
            </a>

            <a href="{{ route('products.index') }}" class="sidebar-item group">
                <div class="flex items-center flex-1">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 group-hover:bg-purple-200 flex items-center justify-center transition-all duration-200 group-[.active]:bg-purple-600">
                        <i class="fas fa-box text-lg text-purple-600 group-[.active]:text-white transition-colors"></i>
                    </div>
                    <span class="ml-3 font-semibold text-gray-700 group-hover:text-gray-900 group-[.active]:text-purple-600">Products</span>
                </div>
                <div class="flex items-center gap-2">
                    {{-- <span class="bg-purple-100 text-purple-700 px-2.5 py-1 rounded-full text-xs font-bold">{{ $countProducts }}</span> --}}
                    <i class="fas fa-chevron-right text-xs text-gray-400 opacity-0 group-hover:opacity-100 group-[.active]:opacity-100 transition-all"></i>
                </div>
            </a>

            <a href="{{ route('categories.index') }}" class="sidebar-item group">
                <div class="flex items-center flex-1">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center transition-all duration-200 group-[.active]:bg-amber-600">
                        <i class="fas fa-tags text-lg text-amber-600 group-[.active]:text-white transition-colors"></i>
                    </div>
                    <span class="ml-3 font-semibold text-gray-700 group-hover:text-gray-900 group-[.active]:text-amber-600">Categories</span>
                </div>
                <div class="flex items-center gap-2">
                    {{-- <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full text-xs font-bold">{{ $countCategories }}</span> --}}
                    <i class="fas fa-chevron-right text-xs text-gray-400 opacity-0 group-hover:opacity-100 group-[.active]:opacity-100 transition-all"></i>
                </div>
            </a>

            <a href="{{ route('dashboard.orders') }}" class="sidebar-item group">
                <div class="flex items-center flex-1">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 group-hover:bg-emerald-200 flex items-center justify-center transition-all duration-200 group-[.active]:bg-emerald-600">
                        <i class="fas fa-shopping-cart text-lg text-emerald-600 group-[.active]:text-white transition-colors"></i>
                    </div>
                    <span class="ml-3 font-semibold text-gray-700 group-hover:text-gray-900 group-[.active]:text-emerald-600">Orders</span>
                </div>
                <div class="flex items-center gap-2">
                    {{-- <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-xs font-bold">{{ $countOrders }}</span> --}}
                    <i class="fas fa-chevron-right text-xs text-gray-400 opacity-0 group-hover:opacity-100 group-[.active]:opacity-100 transition-all"></i>
                </div>
            </a>

            <!-- Divider -->
            <div class="py-3">
                <div class="border-t border-gray-200"></div>
            </div>

            <!-- Settings Section Label -->
            <div class="px-3 mb-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Settings</span>
            </div>

            <a href="{{ route('dashboard.settings') }}" class="sidebar-item group">
                <div class="flex items-center flex-1">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 group-hover:bg-gray-200 flex items-center justify-center transition-all duration-200 group-[.active]:bg-gray-600">
                        <i class="fas fa-cog text-lg text-gray-600 group-[.active]:text-white transition-colors"></i>
                    </div>
                    <span class="ml-3 font-semibold text-gray-700 group-hover:text-gray-900 group-[.active]:text-gray-600">Settings</span>
                </div>
                <i class="fas fa-chevron-right text-xs text-gray-400 opacity-0 group-hover:opacity-100 group-[.active]:opacity-100 transition-all"></i>
            </a>
        </nav>

        <!-- User Info Section (Optional) -->
        <div class="px-4 py-3 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200 shadow-sm">
                <img class="h-10 w-10 rounded-full border-2 border-primary-200 object-cover" 
                     src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" 
                     alt="{{ Auth::user()->name }}"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random'"
                >
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                </div>
                <div class="h-2 w-2 bg-emerald-500 rounded-full"></div>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="p-4 border-t border-gray-200 bg-white">
            <a href="{{ route('logout') }}">
                <button class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 shadow-lg shadow-red-500/30 transition-all duration-200 hover:shadow-xl hover:shadow-red-500/40 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                    <i class="fa-solid fa-sign-out-alt"></i>
                    Sign Out
                </button>
            </a>
        </div>
    </div>
</div>

<style>
.sidebar-item {
    @apply flex items-center justify-between px-3 py-3 rounded-xl text-gray-700 hover:bg-white hover:shadow-md transition-all duration-200 cursor-pointer relative overflow-hidden;
}

.sidebar-item::before {
    content: '';
    @apply absolute left-0 top-0 bottom-0 w-1 bg-primary-600 transform -translate-x-full transition-transform duration-200;
}

.sidebar-item.active::before,
.sidebar-item:hover::before {
    @apply translate-x-0;
}

.sidebar-item.active {
    @apply bg-white shadow-md;
}

/* Custom Scrollbar */
.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    @apply bg-gray-300 rounded-full;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    @apply bg-gray-400;
}
</style>

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const closeSidebarButton = document.getElementById('closeSidebarButton');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const menuIcon = document.getElementById('menuIcon');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            sidebarOverlay.classList.remove('hidden');
            setTimeout(() => {
                sidebarOverlay.classList.remove('opacity-0');
                sidebarOverlay.classList.add('opacity-100');
            }, 10);
            
            // Animate menu icon
            if (menuIcon) {
                menuIcon.classList.remove('fa-bars');
                menuIcon.classList.add('fa-times');
                menuIcon.style.transform = 'rotate(180deg)';
            }
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.remove('opacity-100');
            sidebarOverlay.classList.add('opacity-0');
            setTimeout(() => {
                sidebarOverlay.classList.add('hidden');
            }, 300);
            
            // Reset menu icon
            if (menuIcon) {
                menuIcon.classList.remove('fa-times');
                menuIcon.classList.add('fa-bars');
                menuIcon.style.transform = 'rotate(0deg)';
            }
            
            // Restore body scroll
            document.body.style.overflow = '';
        }

        // Toggle sidebar on mobile menu button click
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                const isOpen = !sidebar.classList.contains('-translate-x-full');
                
                if (isOpen) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }

        // Close sidebar button
        if (closeSidebarButton) {
            closeSidebarButton.addEventListener('click', function() {
                closeSidebar();
            });
        }

        // Close sidebar when clicking overlay
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                closeSidebar();
            });
        }

        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                closeSidebar();
            }
        });

        // Set active menu item based on current URL
        const currentPath = window.location.pathname;
        const sidebarItems = document.querySelectorAll('.sidebar-item');
        
        sidebarItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href && currentPath.includes(href)) {
                // Remove active class from all items
                sidebarItems.forEach(i => i.classList.remove('active'));
                // Add active class to current item
                item.classList.add('active');
            }
        });
    });
</script>
@endpush