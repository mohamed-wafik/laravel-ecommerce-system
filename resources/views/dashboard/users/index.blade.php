@extends('layout.dashboard')

@section('avatar', 'https://i.pravatar.cc/100')

@section("content")
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Page Title -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Users Management</h1>
                <p class="text-gray-600 mt-1">Manage system users and their permissions</p>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-3">
                <!-- Export Button -->
                <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-download"></i>
                    Export
                </button>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <form method="GET" id="filterForm" class="flex gap-4">
                    <div class="relative flex-1">
                        <input 
                            type="text" 
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search users by name, email..." 
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 shadow-sm"
                        >
                        <i onclick="document.getElementById('filterForm').submit()" class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Role Filter -->
                    <div class="relative">
                        <select 
                            name="role"
                            onchange="document.getElementById('filterForm').submit()"
                            class="appearance-none w-full lg:w-40 rounded-lg border border-gray-300 bg-white py-2 px-4 pr-8 text-gray-700 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 cursor-pointer shadow-sm"
                        >
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="moderator" {{ request('role') === 'moderator' ? 'selected' : '' }}>Moderator</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>

                    <button 
                        type="submit"
                        class="hidden"
                    ></button>
                </form>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="bg-gradient-to-r from-primary-600 to-primary-700">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">USER</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">CONTACT</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">ROLE</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">ORDERS</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">JOINED</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if($users->count())
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                                <!-- USER INFO -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full border-2 border-gray-200 group-hover:border-primary-200 transition-colors" 
                                                 src="{{ $user['avatar'] ? asset('storage/' . $user['avatar']) : 'https://via.placeholder.com/150/cccccc/969696?text=No+Image' . $user['id'] }}"  
                                                 alt="{{ $user["name"] }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user["name"] }}</div>
                                            <div class="text-sm text-gray-500">ID: #{{ $user["id"] }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- CONTACT -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user["email"] }}</div>
                                    <div class="text-sm text-gray-500">{{ $user["phone"] ?? 'No phone' }}</div>
                                </td>
                                
                                <!-- ROLE -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleColors = [
                                            'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'user' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'moderator' => 'bg-green-100 text-green-800 border-green-200'
                                        ];
                                        $roleColor = $roleColors[$user["role"]] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $roleColor }}">
                                        <i class="fa-solid fa-user-shield mr-1 text-xs"></i>
                                        {{ ucfirst($user["role"]) }}
                                    </span>
                                </td>
                                
                                <!-- ORDERS COUNT -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                        <i class="fa-solid fa-shopping-cart mr-2 text-xs"></i>
                                        {{ $user["orders_count"] ?? 0 }}
                                    </span>
                                </td>
                                
                                <!-- JOINED DATE -->
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ $user["created_at"]->format('M j, Y') }}
                                </td>
                                
                                <!-- ACTIONS -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route("users.show", $user["id"]) }}" class="inline-flex items-center p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>
                                        <a 
                                            href="#" 
                                            class="inline-flex items-center p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors edit-role-btn"
                                            data-id="{{ $user["id"] }}"
                                            data-name="{{ $user["name"] }}"
                                            data-role="{{ $user["role"] }}"
                                            title="Edit Role"
                                        >
                                            <i class="fa-solid fa-edit text-sm"></i>
                                        </a>
                                        <button onclick="confirmDelete({{ $user['id'] }})" 
                                            class="inline-flex items-center p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                            title="Delete">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>    
                        @endforeach
                    @else 
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-users text-5xl mb-4"></i>
                                    <p class="text-xl font-medium mb-2">No Users Found</p>
                                    <p class="text-sm">Try adjusting filters or add new users.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                    </div>
                    <div class="flex space-x-2">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('script')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This user will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-2xl shadow-xl',
            confirmButton: 'rounded-lg px-4 py-2 font-semibold',
            cancelButton: 'rounded-lg px-4 py-2 font-semibold'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.showLoading();
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
                        text: 'The user has been removed successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', 'Failed to delete user.', 'error');
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
                    admin: 'admin',
                    user: 'User',
                    manager: 'Manager'
                },
                inputValue: currentRole,
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10B981', // green-500
                cancelButtonColor: '#6B7280',  // gray-500
                inputValidator: (value) => {
                    if (!value) return 'You must select a role!';
                }
            });

            if (!newRole) return; // cancelled

            // Confirm update
            Swal.fire({
                title: 'Updating...',
                text: 'Please wait while we update this user role.',
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

                console.log(response)

                if (!response.ok) throw new Error("Network response was not ok");
                const data = await response.json();

                Swal.fire({
                    icon: 'success',
                    title: 'Role Updated!',
                    text: data.message || 'User role updated successfully.',
                    timer: 1500,
                    showConfirmButton: false
                });
                
                location.reload();

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed!',
                    text: 'Something went wrong while updating the user role.'
                });
            }
        });
    });
}

);
</script>

@endpush
