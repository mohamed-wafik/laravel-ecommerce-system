@extends('layout.dashboard')

@section('avatar', 'https://i.pravatar.cc/100')

@section("content")
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Users Management</h1>
                <p class="text-gray-600 mt-2">Manage system users and their permissions</p>
            </div>
            
            <!-- Export Button -->
            <a href="{{ route('users.export') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-500/30 transition-all duration-200 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                Export Users
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
        <form method="GET" id="filterForm" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                    Search Users
                </label>
                <div class="relative">
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name, email..." 
                        class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-gray-300 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-600/20 transition-all font-medium"
                        onchange="document.getElementById('filterForm').submit()"
                    >
                    <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Role Filter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    Filter by Role
                </label>
                <div class="relative">
                    <select 
                        name="role"
                        onchange="document.getElementById('filterForm').submit()"
                        class="appearance-none w-full rounded-xl border-2 border-gray-300 bg-white py-3 px-4 pr-10 text-gray-700 font-medium focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-600/20 cursor-pointer transition-all"
                    >
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                        <option value="moderator" {{ request('role') === 'moderator' ? 'selected' : '' }}>Moderator</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-800 to-gray-900">
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                User
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                Contact
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                Role
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                </svg>
                                Orders
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Joined
                            </div>
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">
                            <div class="flex items-center justify-end gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                                Actions
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @if($users->count())
                        @foreach ($users as $user)
                            <tr class="hover:bg-purple-50/50 transition-all duration-200 group">
                                <!-- USER INFO -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <img class="h-12 w-12 rounded-full border-2 border-gray-200 group-hover:border-purple-400 transition-all object-cover shadow-sm" 
                                                 src="{{ $user['avatar'] ?? asset('storage/images/default_avatar.webp') }}"  
                                                 alt="{{ $user['name'] }}"
                                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user['name']) }}&background=random'"
                                            >
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">{{ $user["name"] }}</div>
                                            <div class="text-xs text-gray-500">ID: #{{ $user["id"] }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- CONTACT -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user["email"] }}</div>
                                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                                </svg>
                                                {{ $user["phone"] ?? 'No phone' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- ROLE -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleConfig = [
                                            'admin' => ['bg' => 'from-purple-100 to-purple-200', 'text' => 'purple-900', 'border' => 'purple-300', 'icon' => 'fa-crown'],
                                            'user' => ['bg' => 'from-blue-100 to-blue-200', 'text' => 'blue-900', 'border' => 'blue-300', 'icon' => 'fa-user'],
                                            'moderator' => ['bg' => 'from-green-100 to-green-200', 'text' => 'green-900', 'border' => 'green-300', 'icon' => 'fa-shield-halved']
                                        ];
                                        $config = $roleConfig[$user["role"]] ?? $roleConfig['user'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r {{ $config['bg'] }} text-{{ $config['text'] }} border border-{{ $config['border'] }} shadow-sm">
                                        <i class="fa-solid {{ $config['icon'] }}"></i>
                                        {{ ucfirst($user["role"]) }}
                                    </span>
                                </td>
                                
                                <!-- ORDERS COUNT -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-900 border border-blue-300 shadow-sm">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                        </svg>
                                        {{ count($user->orders) }}
                                    </span>
                                </td>
                                
                                <!-- JOINED DATE -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $user["created_at"]->format('M j, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $user["created_at"]->diffForHumans() }}</div>
                                </td>
                                
                                <!-- ACTIONS -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- View -->
                                        <a href="{{ route('users.show', $user['id']) }}" 
                                           class="inline-flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-100 hover:bg-blue-200 rounded-lg transition-all duration-200 hover:shadow-md group/btn" 
                                           title="View Details">
                                            <svg class="w-4 h-4 group-hover/btn:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </a>

                                        <!-- Edit Role -->
                                        <button 
                                            class="inline-flex items-center justify-center w-9 h-9 text-green-600 bg-green-100 hover:bg-green-200 rounded-lg transition-all duration-200 hover:shadow-md group/btn edit-role-btn"
                                            data-id="{{ $user['id'] }}"
                                            data-name="{{ $user['name'] }}"
                                            data-role="{{ $user['role'] }}"
                                            title="Edit Role">
                                            <svg class="w-4 h-4 group-hover/btn:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                            </svg>
                                        </button>

                                        <!-- Delete -->
                                        <button onclick="confirmDelete({{ $user['id'] }})" 
                                            class="inline-flex items-center justify-center w-9 h-9 text-red-600 bg-red-100 hover:bg-red-200 rounded-lg transition-all duration-200 hover:shadow-md group/btn" 
                                            title="Delete User">
                                            <svg class="w-4 h-4 group-hover/btn:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>    
                        @endforeach
                    @else 
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xl font-bold text-gray-900 mb-2">No Users Found</p>
                                    <p class="text-sm text-gray-500 mb-6">Try adjusting your filters or search criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($users) && method_exists($users, 'hasPages') && $users->hasPages())
            @include('components.Pagination', [
                'firstItem' => $users->firstItem(),
                'lastItem' => $users->lastItem(),
                'total' => $users->total(),
                'previousPageUrl' => $users->previousPageUrl(),
                'nextPageUrl' => $users->nextPageUrl(),
                'onFirstPage' => $users->onFirstPage(),
                'hasMorePages' => $users->hasMorePages()
            ])
        @endif
    </div>
@endsection

@push('script')
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Delete User?',
        text: 'This user will be permanently deleted and cannot be recovered.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        customClass: {
            popup: 'rounded-2xl shadow-2xl',
            confirmButton: 'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-2 px-6 rounded-xl',
            cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-xl'
        },
        buttonsStyling: false,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`/dashboard/users/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'User has been removed successfully.',
                        showConfirmButton: false,
                        timer: 1500,
                        customClass: {
                            popup: 'rounded-2xl'
                        }
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete user.',
                        customClass: {
                            popup: 'rounded-2xl'
                        }
                    });
                }
            });
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".edit-role-btn").forEach(button => {
        button.addEventListener("click", async (e) => {
            e.preventDefault();

            const userId = button.dataset.id;
            const userName = button.dataset.name;
            const currentRole = button.dataset.role;

            const { value: newRole } = await Swal.fire({
                title: `Edit Role for ${userName}`,
                input: 'select',
                inputOptions: {
                    admin: 'Admin',
                    user: 'User',
                    moderator: 'Moderator'
                },
                inputValue: currentRole,
                showCancelButton: true,
                confirmButtonText: 'Update Role',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl',
                    confirmButton: 'bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-2 px-6 rounded-xl',
                    cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-xl'
                },
                buttonsStyling: false,
                inputValidator: (value) => {
                    if (!value) return 'You must select a role!';
                }
            });

            if (!newRole) return;

            Swal.fire({
                title: 'Updating...',
                text: 'Please wait while we update the user role.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const response = await fetch(`/dashboard/users/${userId}/role`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ role: newRole })
                });

                if (!response.ok) throw new Error("Network response was not ok");
                const data = await response.json();

                Swal.fire({
                    icon: 'success',
                    title: 'Role Updated!',
                    text: data.message || 'User role updated successfully.',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-2xl'
                    }
                });
                
                setTimeout(() => location.reload(), 1500);

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed!',
                    text: 'Something went wrong while updating the user role.',
                    customClass: {
                        popup: 'rounded-2xl'
                    }
                });
            }
        });
    });
});
</script>
@endpush