<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40 backdrop-blur-sm bg-white/95">
    <div class="flex justify-between items-center pl-[65px] pr-3 lg:px-6 py-3.5">
        <!-- Left Section - Title & Breadcrumb -->
        <div class="flex items-center space-x-4">
            <h1 class="text-xl lg:text-2xl font-bold text-gray-900 tracking-tight">Dashboard Overview</h1>
            <div class="hidden sm:flex items-center space-x-2 text-sm">
                <span class="text-gray-500 font-medium">Admin</span>
                <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                <span class="text-primary-600 font-semibold">Dashboard</span>
            </div>
        </div>
        
        <!-- Right Section - Actions -->
        <div class="flex items-center space-x-3">
            <!-- Search Bar -->
            <div class="dashboard-search relative">
                <div class="relative hidden md:block">
                    <input 
                        id="dashboard-search"
                        type="text" 
                        placeholder="Search anything..." 
                        class="w-64 pl-10 pr-10 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 text-sm placeholder:text-gray-400 hover:border-gray-300"
                        autocomplete="off"
                    >
                    <!-- Search Icon (hidden while spinning) -->
                    <i id="search-icon" class="fas fa-search absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                    <!-- Spinner (hidden by default, sits on the right) -->
                    <div id="search-spinner" class="hidden absolute right-3.5 top-1/2 transform -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                </div>
                <div id="dashboard-results" style="display:none;" class="absolute z-50 mt-1 w-64 bg-white border-2 border-gray-200 rounded-xl shadow-xl overflow-hidden">
                </div>
            </div>

            <!-- Mobile Search Button -->
            <button class="md:hidden p-2.5 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all duration-200">
                <i class="fas fa-search text-lg"></i>
            </button>
            
            <!-- Notifications -->
            <div class="relative">
                <button class="relative p-2.5 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all duration-200 group">
                    <i class="fas fa-bell text-lg group-hover:animate-pulse"></i>
                    <span class="absolute top-1 right-1 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
                    </span>
                </button>
                {{-- @if(Auth::user()->unreadNotifications->count() > 0) --}}
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center border-2 border-white shadow-sm">
                        {{-- {{ Auth::user()->unreadNotifications->count() }} --}} 3
                    </span>
                {{-- @endif
                <div class="notificationDropMenu top-full right-0 mt-2 w-80 hidden opacity-0 scale-95 origin-top-right transition-all duration-200">
                    <div class="bg-white rounded-xl border-2 border-gray-200 shadow-2xl overflow-hidden">
                        <div class="p-4 border-b-2 border-gray-100">
                            <h3 class="text-sm font-bold text-gray-900">Notifications</h3> 
                            @forEach(Auth::user()->unreadNotifications as $notification)
                                <div class="mt-3">
                                    <a href="{{ $notification->data['url'] ?? '#' }}" class="block px-3 py-2 rounded-lg hover:bg-primary-50 transition-all duration-200">
                                        <p class="text-sm text-gray-800">{{ $notification->data['message'] ?? 'You have a new notification.' }}</p>
                                        <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                    </a>
                                </div>
                            @endforEach
                        </div>
                    </div>
                </div> --}}
            </div>
            
            <!-- User Menu -->
            <div class="relative">
                <button id="userMenuButton" class="flex items-center space-x-3 p-2 pr-3 rounded-xl hover:bg-gray-50 transition-all duration-200 group border-2 border-transparent hover:border-gray-200">
                    <div class="relative flex-shrink-0">
                        <img class="h-10 w-10 rounded-full border-2 border-gray-200 group-hover:border-primary-400 transition-all duration-200 object-cover" 
                             src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" 
                             alt="{{ Auth::user()->name }}"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random'"
                        >
                        <span class="absolute bottom-0 right-0 h-3 w-3 bg-emerald-500 rounded-full border-2 border-white"></span>
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 font-medium capitalize">{{ Auth::user()->role }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs hidden md:block group-hover:text-primary-600 transition-all duration-200 group-hover:rotate-180"></i>
                </button>
                
                <!-- Dropdown Menu -->
                <div id="userMenuDropdown" class="absolute z-[1000] top-[calc(100%+10px)] right-0 w-60 hidden opacity-0 scale-95 origin-top-right transition-all duration-200">
                    <div class="bg-white rounded-xl border-2 border-gray-200 shadow-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-4 py-4">
                            <div class="flex items-center gap-3">
                                <img class="h-12 w-12 rounded-full border-2 border-white shadow-md object-cover" 
                                     src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" 
                                     alt="{{ Auth::user()->name }}"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random'"
                                >
                                <div>
                                    <p class="text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-primary-100 capitalize">{{ Auth::user()->role }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-2">
                            <a href="{{ route('dashboard.settings') }}" 
                               class="flex items-center gap-3 px-3 py-2.5 hover:bg-primary-50 rounded-lg transition-all duration-200 group">
                                <div class="p-2 bg-blue-100 group-hover:bg-blue-200 rounded-lg transition-colors">
                                    <i class="fa-solid fa-user text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-semibold text-gray-900 group-hover:text-primary-600">My Profile</span>
                                    <p class="text-xs text-gray-500">View and edit profile</p>
                                </div>
                                <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:text-primary-600 opacity-0 group-hover:opacity-100 transition-all"></i>
                            </a>

                            <a href="{{ route('dashboard.settings') }}" 
                               class="flex items-center gap-3 px-3 py-2.5 hover:bg-primary-50 rounded-lg transition-all duration-200 group">
                                <div class="p-2 bg-purple-100 group-hover:bg-purple-200 rounded-lg transition-colors">
                                    <i class="fa-solid fa-cog text-purple-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-semibold text-gray-900 group-hover:text-primary-600">Settings</span>
                                    <p class="text-xs text-gray-500">Preferences & privacy</p>
                                </div>
                                <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:text-primary-600 opacity-0 group-hover:opacity-100 transition-all"></i>
                            </a>

                            <a href="{{ route('dashboard.settings') }}" 
                               class="flex items-center gap-3 px-3 py-2.5 hover:bg-primary-50 rounded-lg transition-all duration-200 group">
                                <div class="p-2 bg-emerald-100 group-hover:bg-emerald-200 rounded-lg transition-colors">
                                    <i class="fa-solid fa-briefcase text-emerald-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-semibold text-gray-900 group-hover:text-primary-600">Portfolio</span>
                                    <p class="text-xs text-gray-500">Manage your work</p>
                                </div>
                                <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:text-primary-600 opacity-0 group-hover:opacity-100 transition-all"></i>
                            </a>
                        </div>

                        <div class="border-t-2 border-gray-100 my-2"></div>

                        <div class="p-2">
                            <a href="{{ route('logout') }}" 
                               class="flex items-center gap-3 px-3 py-2.5 hover:bg-red-50 rounded-lg transition-all duration-200 group">
                                <div class="p-2 bg-red-100 group-hover:bg-red-200 rounded-lg transition-colors">
                                    <i class="fa-solid fa-right-from-bracket text-red-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-semibold text-gray-900 group-hover:text-red-600">Sign Out</span>
                                    <p class="text-xs text-gray-500">Logout from account</p>
                                </div>
                                <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:text-red-600 opacity-0 group-hover:opacity-100 transition-all"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── User Menu ───────────────────────────────────────────────
    const userMenuButton   = document.getElementById('userMenuButton');
    const userMenuDropdown = document.getElementById('userMenuDropdown');

    if (userMenuButton && userMenuDropdown) {
        function closeUserMenu() {
            userMenuDropdown.classList.remove('opacity-100', 'scale-100');
            userMenuDropdown.classList.add('opacity-0', 'scale-95');
            setTimeout(() => userMenuDropdown.classList.add('hidden'), 200);
        }

        userMenuButton.addEventListener('click', function (e) {
            e.stopPropagation();
            if (userMenuDropdown.classList.contains('hidden')) {
                userMenuDropdown.classList.remove('hidden');
                setTimeout(() => {
                    userMenuDropdown.classList.remove('opacity-0', 'scale-95');
                    userMenuDropdown.classList.add('opacity-100', 'scale-100');
                }, 10);
            } else {
                closeUserMenu();
            }
        });

        document.addEventListener('click', function (e) {
            if (!userMenuButton.contains(e.target) && !userMenuDropdown.contains(e.target)) {
                closeUserMenu();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !userMenuDropdown.classList.contains('hidden')) {
                closeUserMenu();
            }
        });
    }

    // ─── Dashboard Search ────────────────────────────────────────
    const searchInput   = document.getElementById('dashboard-search');
    const resultsBox    = document.getElementById('dashboard-results');
    const searchSpinner = document.getElementById('search-spinner');
    let searchTimeout;

    if (searchInput && resultsBox && searchSpinner) {

        // ── helpers ──────────────────────────────────────────────
        function showSpinner() {
            searchSpinner.classList.remove('hidden');
        }
        function hideSpinner() {
            searchSpinner.classList.add('hidden');
        }
        function resetSearch() {
            clearTimeout(searchTimeout);
            hideSpinner();
            resultsBox.style.display = 'none';
        }

        // ── ⌘K / Ctrl+K shortcut ─────────────────────────────────
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchInput.focus();
            }
        });

        // ── hide results on blur ─────────────────────────────────
        searchInput.addEventListener('blur', () => {
            if(resultsBox.target === searchInput) return;
            setTimeout(() => {
                resultsBox.style.display = 'none';
            }, 200);
        });

        // ── main input handler ───────────────────────────────────
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);

            const q = searchInput.value.trim();

            // Too short → reset everything immediately
            if (q.length < 2) {
                resetSearch();
                return;
            }

            // Show spinner the moment we start the debounce wait
            showSpinner();

            searchTimeout = setTimeout(() => {

                fetch(`{{ route('dashboard.search') }}?q=${encodeURIComponent(q)}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })
                    .then((r) => {
                        // Session expired → redirect to login instead of showing an error
                        if (r.status === 401 || r.status === 403) {
                            window.location.href = '{{ route("login") }}';
                            return null;
                        }
                        if (!r.ok) {
                            throw new Error('status ' + r.status);
                        }
                        return r.json();
                    })
                    .then((data) => {
                        if (data === null) return; // already redirecting

                        hideSpinner();

                        if (!data.length) {
                            resultsBox.innerHTML = `
                                <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                    No results for "<span class="font-semibold text-gray-700">${q}</span>"
                                </div>`;
                        } else {
                            resultsBox.innerHTML = data.map((item) => `
                                <a href="${item.url}"
                                   class="flex items-center gap-3 px-3 py-2.5 hover:bg-primary-50 transition-colors duration-150 border-b border-gray-100 last:border-0 group">
                                    <span class="text-lg w-6 text-center">${item.icon}</span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 truncate">${item.title}</p>
                                        <p class="text-xs text-gray-500">${item.type}</p>
                                    </div>
                                </a>
                            `).join('');
                        }
                        resultsBox.style.display = 'block';
                    })
                    .catch(() => {
                        hideSpinner();
                        resultsBox.innerHTML = `
                            <div class="px-4 py-3 text-sm text-red-500 text-center">Something went wrong. Try again.</div>`;
                        resultsBox.style.display = 'block';
                    });
            }, 250);
        });
    }
});
</script>
@endpush