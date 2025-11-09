@extends('layout.dashboard')

@section("content")
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Profile</h1>
                <p class="text-gray-600 mt-1">Manage your account settings and preferences</p>
            </div>

            <div class="text-center">
                <div class="text-lg font-semibold text-green-600">
                    {{ Auth::user()->created_at->format('M Y') }}
                </div>
                <div class="text-gray-500">Member Since</div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Profile Information</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                    <i class="fa-solid fa-user mr-1"></i>
                    {{ ucfirst(Auth::user()->role) }}
                </span>
            </div>

            <form method="POST" action="{{ route("user.update", Auth::user()->id)}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-6">
                            <div class="relative">
                                <img id="avatarPreview"
                                        src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://via.placeholder.com/150/cccccc/969696?text=No+Image' }}"
                                        alt="Profile Avatar"
                                        class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 shadow-sm">
                                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 rounded-full transition-all flex items-center justify-center opacity-0 hover:opacity-100 cursor-pointer"
                                        onclick="document.getElementById('avatarInput').click()">
                                    <i class="fa-solid fa-camera text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="file"
                                        name="image"
                                        id="avatarInput"
                                        accept="image/*"
                                        class="hidden"
                                        onchange="previewImage(this, 'avatarPreview')">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                                    <div class="flex gap-3">
                                        <label for="avatarInput"
                                                class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                            <i class="fa-solid fa-upload"></i>
                                            Upload New Photo
                                        </label>

                                        @if(Auth::user()->avatar)
                                            <button type="button"
                                                    onclick="removeAvatar({{ Auth::id() }})"
                                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                                                <i class="fa-solid fa-trash"></i>
                                                Remove
                                            </button>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF - Max 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text"
                                name="name"
                                value="{{ old('name', Auth::user()->name) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-colors"
                                placeholder="Enter your full name">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email"
                                name="email"
                                value="{{ old('email', Auth::user()->email) }}"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-colors"
                                placeholder="Enter your email address">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <div class="w-full rounded-lg border border-gray-300 px-4 py-2 bg-gray-50 text-gray-700">
                            {{ ucfirst(Auth::user()->role) }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">User role cannot be changed</p>
                    </div>

                    <!-- Member Since -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Member Since</label>
                        <div class="w-full rounded-lg border border-gray-300 px-4 py-2 bg-gray-50 text-gray-700">
                            {{ Auth::user()->created_at->format('F j, Y') }}
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6 pt-6 border-t border-gray-200">
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Security Settings</h2>

            <form id="changePassword" method="POST"  action="{{ route("user.changePassword", Auth::id())}}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                        <input type="password"
                                name="currentPassword"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-colors"
                                placeholder="Enter your current password"
                                required>
                        @error('currentPassword')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password"
                                name="password"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-colors"
                                placeholder="Enter new password"
                                required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password"
                                name="password_confirmation"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-colors"
                                placeholder="Confirm new password"
                                required>
                    </div>
                </div>

                <div class="flex justify-end mt-6 pt-6 border-t border-gray-200">
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fa-solid fa-key"></i>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="space-y-6 mt-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Account Overview</h2>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fa-solid fa-circle text-[8px] mr-1"></i>
                        Active
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Email Verified</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ Auth::user()->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        <i class="fa-solid {{ Auth::user()->email_verified_at ? 'fa-check' : 'fa-clock' }} text-xs mr-1"></i>
                        {{ Auth::user()->email_verified_at ? 'Verified' : 'Pending' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('script')
<script>
function previewImage(input, previewId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById(previewId).src = e.target.result;
        reader.readAsDataURL(file);
    }
}
function removeAvatar(userId) {
    fetch(`/dashboard/portfolio/${userId}/remove-avator`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        }
    }).then(response => {
        if (response.ok) {
            location.reload();
        }
    });
}
</script>
@endpush
