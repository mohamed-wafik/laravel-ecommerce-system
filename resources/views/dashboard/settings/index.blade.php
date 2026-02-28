@extends('layout.dashboard')

@section("content")
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">User Profile</h1>
                <p class="text-gray-600 mt-2">Manage your account settings and preferences</p>
            </div>

            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl px-6 py-4 border-2 border-emerald-200 shadow-sm">
                <div class="text-center">
                    <div class="text-sm font-semibold text-emerald-700 uppercase tracking-wide">Member Since</div>
                    <div class="text-2xl font-bold text-emerald-900 mt-1">
                        {{ Auth::user()->created_at->format('M Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content - Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-user-circle"></i>
                            Profile Information
                        </h2>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold border-2 bg-primary-100 text-primary-800 border-primary-300">
                            <i class="fa-solid fa-shield-halved"></i>
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('user.update', Auth::user()->id)}}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="p-6 space-y-6">
                        <!-- Avatar Upload Section -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border-2 border-gray-200">
                            <div class="flex flex-col sm:flex-row items-center gap-6">
                                <div class="relative group">
                                    <img id="avatarPreview"
                                         src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}"
                                         alt="Profile Avatar"
                                         class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-xl ring-4 ring-gray-200 group-hover:ring-primary-400 transition-all duration-300"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random'"
                                    >

                                    <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-40 rounded-full transition-all flex items-center justify-center opacity-0 hover:opacity-100 cursor-pointer"
                                         onclick="document.getElementById('avatarInput').click()">
                                        <div class="text-center">
                                            <i class="fa-solid fa-camera text-white text-2xl mb-1"></i>
                                            <p class="text-white text-xs font-semibold">Change</p>
                                        </div>
                                    </div>
                                    <div class="absolute bottom-2 right-2 bg-emerald-500 rounded-full p-2 border-4 border-white shadow-lg">
                                        <i class="fa-solid fa-check text-white text-xs"></i>
                                    </div>
                                </div>
                                
                                <div class="flex-1 text-center sm:text-left">
                                    <input type="file"
                                           name="image"
                                           id="avatarInput"
                                           accept="image/*"
                                           class="hidden"
                                           onchange="previewImage(this, 'avatarPreview')">
                                    <label class="block text-sm font-bold text-gray-900 mb-3">Profile Photo</label>
                                    <div class="flex flex-wrap gap-3 justify-center sm:justify-start">
                                        <label for="avatarInput"
                                               class="cursor-pointer inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-primary-600 to-primary-700 rounded-xl hover:from-primary-700 hover:to-primary-800 shadow-lg shadow-primary-500/30 transition-all duration-200 hover:shadow-xl hover:shadow-primary-500/40 hover:-translate-y-0.5">
                                            <i class="fa-solid fa-upload"></i>
                                            Upload New Photo
                                        </label>

                                        @if(Auth::user()->avatar)
                                            <button type="button"
                                                    onclick="removeAvatar({{ Auth::id() }})"
                                                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-red-700 bg-red-100 border-2 border-red-300 rounded-xl hover:bg-red-200 hover:border-red-400 transition-all duration-200">
                                                <i class="fa-solid fa-trash"></i>
                                                Remove
                                            </button>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-600 mt-3 flex items-center gap-1.5 justify-center sm:justify-start">
                                        <i class="fa-solid fa-info-circle text-gray-500"></i>
                                        JPG, PNG or GIF - Max 2MB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">
                                    <i class="fa-solid fa-user text-primary-600 mr-1"></i>
                                    Full Name
                                </label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name', Auth::user()->name) }}"
                                       class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 focus:outline-none focus:border-primary-600 focus:ring-4 focus:ring-primary-600/20 transition-all font-medium"
                                       placeholder="Enter your full name">
                                @error('name')
                                    <p class="text-red-600 text-xs mt-2 flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Email Address (Read-only in profile form) -->
                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">
                                    <i class="fa-solid fa-envelope text-primary-600 mr-1"></i>
                                    Email Address
                                </label>
                                <div class="relative">
                                    <input type="email"
                                           value="{{ Auth::user()->email }}"
                                           class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 bg-gray-50 text-gray-700 font-medium cursor-not-allowed"
                                           disabled>
                                    <button type="button"
                                            onclick="openChangeEmailModal()"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        Change
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    Requires password verification to change
                                </p>
                            </div>

                            <!-- Role -->
                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">
                                    <i class="fa-solid fa-shield text-purple-600 mr-1"></i>
                                    Role
                                </label>
                                <div class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 bg-gray-50 text-gray-700 font-semibold capitalize flex items-center justify-between">
                                    {{ ucfirst(Auth::user()->role) }}
                                    <i class="fa-solid fa-lock text-gray-400"></i>
                                </div>
                                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                    <i class="fa-solid fa-info-circle"></i>
                                    User role cannot be changed
                                </p>
                            </div>

                            <!-- Member Since -->
                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">
                                    <i class="fa-solid fa-calendar-check text-emerald-600 mr-1"></i>
                                    Member Since
                                </label>
                                <div class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 bg-gray-50 text-gray-700 font-semibold flex items-center gap-2">
                                    <i class="fa-solid fa-clock text-gray-400"></i>
                                    {{ Auth::user()->created_at->format('F j, Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-6 border-t-2 border-gray-200">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-8 py-3 text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl hover:from-emerald-700 hover:to-emerald-800 shadow-lg shadow-emerald-500/30 transition-all duration-200 hover:shadow-xl hover:shadow-emerald-500/40 hover:-translate-y-0.5">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Security Settings Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 border-b border-red-700">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-lock"></i>
                        Security Settings
                    </h2>
                    <p class="text-red-100 text-sm mt-1">Update your password to keep your account secure</p>
                </div>

                <form id="changePasswordForm" method="POST" action="{{ route('user.changePassword', Auth::id())}}">
                    @csrf
                    @method('PUT')

                    <div class="p-6 space-y-6">
                        <!-- Current Password -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                <i class="fa-solid fa-key text-gray-600 mr-1"></i>
                                Current Password
                            </label>
                            <div class="relative">
                                <input type="password"
                                       name="currentPassword"
                                       id="currentPassword"
                                       class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 pr-12 focus:outline-none focus:border-red-600 focus:ring-4 focus:ring-red-600/20 transition-all font-medium"
                                       placeholder="Enter your current password"
                                       required>
                                <button type="button" 
                                        onclick="togglePassword('currentPassword')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fa-solid fa-eye" id="currentPassword-icon"></i>
                                </button>
                            </div>
                            @error('currentPassword')
                                <p class="text-red-600 text-xs mt-2 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                <i class="fa-solid fa-lock text-gray-600 mr-1"></i>
                                New Password
                            </label>
                            <div class="relative">
                                <input type="password"
                                       name="password"
                                       id="newPassword"
                                       class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 pr-12 focus:outline-none focus:border-red-600 focus:ring-4 focus:ring-red-600/20 transition-all font-medium"
                                       placeholder="Enter new password"
                                       oninput="checkPasswordStrength(this.value)"
                                       required>
                                <button type="button" 
                                        onclick="togglePassword('newPassword')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fa-solid fa-eye" id="newPassword-icon"></i>
                                </button>
                            </div>
                            <!-- Password Strength Indicator -->
                            <div id="passwordStrength" class="mt-3 hidden">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="strengthBar" class="h-full transition-all duration-300 rounded-full"></div>
                                    </div>
                                    <span id="strengthText" class="text-xs font-bold"></span>
                                </div>
                                <p class="text-xs text-gray-600">Use 8+ characters with letters, numbers & symbols</p>
                            </div>
                            @error('password')
                                <p class="text-red-600 text-xs mt-2 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                <i class="fa-solid fa-shield-check text-gray-600 mr-1"></i>
                                Confirm New Password
                            </label>
                            <div class="relative">
                                <input type="password"
                                       name="password_confirmation"
                                       id="confirmPassword"
                                       class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 pr-12 focus:outline-none focus:border-red-600 focus:ring-4 focus:ring-red-600/20 transition-all font-medium"
                                       placeholder="Confirm new password"
                                       required>
                                <button type="button" 
                                        onclick="togglePassword('confirmPassword')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fa-solid fa-eye" id="confirmPassword-icon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-6 border-t-2 border-gray-200">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-8 py-3 text-sm font-bold text-white bg-gradient-to-r from-red-600 to-red-700 rounded-xl hover:from-red-700 hover:to-red-800 shadow-lg shadow-red-500/30 transition-all duration-200 hover:shadow-xl hover:shadow-red-500/40 hover:-translate-y-0.5">
                                <i class="fa-solid fa-key"></i>
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column - Account Stats & Info -->
        <div class="space-y-6">
            <!-- Account Overview Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 border-b border-blue-700">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-chart-line"></i>
                        Account Overview
                    </h2>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Account Status -->
                    <div class="flex items-center justify-between p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl border-2 border-emerald-200">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-emerald-500 rounded-lg">
                                <i class="fa-solid fa-circle-check text-white"></i>
                            </div>
                            <span class="text-sm font-bold text-gray-900">Account Status</span>
                        </div>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-600 text-white border-2 border-emerald-700 shadow-sm">
                            <i class="fa-solid fa-bolt text-[10px]"></i>
                            Active
                        </span>
                    </div>

                    <!-- Email Verification -->
                    <div class="flex items-center justify-between p-4 bg-gradient-to-br from-{{ Auth::user()->email_verified_at ? 'blue' : 'amber' }}-50 to-{{ Auth::user()->email_verified_at ? 'blue' : 'amber' }}-100 rounded-xl border-2 border-{{ Auth::user()->email_verified_at ? 'blue' : 'amber' }}-200">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-{{ Auth::user()->email_verified_at ? 'blue' : 'amber' }}-500 rounded-lg">
                                <i class="fa-solid fa-envelope text-white"></i>
                            </div>
                            <span class="text-sm font-bold text-gray-900">Email Status</span>
                        </div>
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-{{ Auth::user()->email_verified_at ? 'blue' : 'amber' }}-600 text-white border-2 border-{{ Auth::user()->email_verified_at ? 'blue' : 'amber' }}-700 shadow-sm">
                            <i class="fa-solid fa-{{ Auth::user()->email_verified_at ? 'check-circle' : 'clock' }} text-[10px]"></i>
                            {{ Auth::user()->email_verified_at ? 'Verified' : 'Pending' }}
                        </span>
                    </div>

                    <!-- Last Updated -->
                    <div class="flex items-center justify-between p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border-2 border-purple-200">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-500 rounded-lg">
                                <i class="fa-solid fa-clock-rotate-left text-white"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900">Last Updated</div>
                                <div class="text-xs text-gray-600">{{ Auth::user()->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 border-b border-purple-700">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-bolt"></i>
                        Quick Actions
                    </h2>
                </div>

                <div class="p-6 space-y-3">
                    <a href="#" class="flex items-center gap-3 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-gray-200 hover:border-primary-400 hover:shadow-md transition-all duration-200 group">
                        <div class="p-2 bg-blue-100 group-hover:bg-blue-200 rounded-lg transition-colors">
                            <i class="fa-solid fa-download text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-bold text-gray-900 group-hover:text-primary-600 transition-colors">Download Data</div>
                            <div class="text-xs text-gray-600">Export your information</div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-primary-600 transition-colors"></i>
                    </a>

                    <a href="#" class="flex items-center gap-3 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-gray-200 hover:border-primary-400 hover:shadow-md transition-all duration-200 group">
                        <div class="p-2 bg-purple-100 group-hover:bg-purple-200 rounded-lg transition-colors">
                            <i class="fa-solid fa-shield-halved text-purple-600"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-bold text-gray-900 group-hover:text-primary-600 transition-colors">Privacy Settings</div>
                            <div class="text-xs text-gray-600">Manage your privacy</div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-400 group-hover:text-primary-600 transition-colors"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Email Modal -->
    <div id="changeEmailModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[1001] hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-envelope"></i>
                        Change Email Address
                    </h3>
                    <button onclick="closeChangeEmailModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <form id="changeEmailForm" method="POST" action="{{ route('user.changeEmail', Auth::id()) }}">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4">
                    <!-- Current Email (Display) -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Current Email</label>
                        <div class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 bg-gray-50 text-gray-600 font-medium">
                            {{ Auth::user()->email }}
                        </div>
                    </div>

                    <!-- New Email -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">
                            <i class="fa-solid fa-envelope text-blue-600 mr-1"></i>
                            New Email Address
                        </label>
                        <input type="email"
                               name="email"
                               id="newEmail"
                               class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/20 transition-all font-medium"
                               placeholder="Enter new email address"
                               required>
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">
                            <i class="fa-solid fa-lock text-red-600 mr-1"></i>
                            Confirm Password
                        </label>
                        <div class="relative">
                            <input type="password"
                                   name="password"
                                   id="emailChangePassword"
                                   class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 pr-12 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/20 transition-all font-medium"
                                   placeholder="Enter your password"
                                   required>
                            <button type="button" 
                                    onclick="togglePassword('emailChangePassword')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fa-solid fa-eye" id="emailChangePassword-icon"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-shield-halved"></i>
                            We need your password to confirm this change
                        </p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 rounded-b-2xl flex items-center justify-end gap-3">
                    <button type="button"
                            onclick="closeChangeEmailModal()"
                            class="px-5 py-2.5 text-sm font-bold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-500/30 transition-all duration-200">
                        <i class="fa-solid fa-check"></i>
                        Update Email
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('js/setting.js') }}"></script>
@endpush