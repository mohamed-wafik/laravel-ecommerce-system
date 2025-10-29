<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex justify-between items-center pl-[65px] pr-3  lg:px-6 py-4">
        <div class="flex items-center space-x-4">
            <h1 class="text:xl lg:text-2xl font-bold text-gray-900">Dashboard Overview</h1>
            <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-500">
                <span>Admin</span>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-primary-600">Dashboard</span>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">
            <div class="relative hidden md:block">
                <input 
                    type="text" 
                    placeholder="Search..." 
                    class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                >
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            
            <button class="relative p-2 text-gray-500 hover:text-gray-700 rounded-xl hover:bg-gray-100 transition-colors">
                <i class="fas fa-bell text-lg"></i>
                <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
            </button>
            
            <div class="relative">
                <button id="userMenuButton"  class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="flex-shrink-0 h-10 w-10">
                        <img class="h-10 w-10 rounded-full border-2 border-gray-200 group-hover:border-primary-200 transition-colors" 
                                src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://via.placeholder.com/150/cccccc/969696?text=No+Image' }}" 
                                alt="{{ Auth::user()->name }}">
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-900">{{Auth::user()->name}}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->role}}</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-sm hidden md:block"></i>
                </button>
                <ul
                    class="absolute z-[1000] top-[calc(100%+10px)] w-52 right-1 p-3 rounded-md border border-gray-300 hidden bg-white shadow-lg"
                >
                    <li>
                        <a
                            href="{{ route("dashboard.settings")}}"
                            class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-sm transition"
                        >
                        <i class="fa-solid fa-briefcase text-gray-600"></i>
                            <span>Portfolio</span>
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route("logout")}}"
                            class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-sm transition"
                        >
                            <i class="fa-solid fa-right-from-bracket text-gray-600"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>