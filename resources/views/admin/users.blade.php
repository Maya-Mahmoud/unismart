<x-admin-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Ensures modals are hidden by default as 'hidden' class might be toggled by JS later */
        #editUserModal, #addUserModal {
            display: none;
        }

        /* Overrides 'display: none' when 'hidden' class is removed, using flex for centering */
        #editUserModal:not(.hidden), #addUserModal:not(.hidden) {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        /* Adjusts max-width for smaller screens if needed, although w-96 is used now */
        #editUserModal .relative, #addUserModal .relative {
            max-width: 96%;
        }

        /* General styling for select-green (assuming a custom style) */
        .select-green {
            /* Example base Tailwind classes for a select input */
            @apply border border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500;
            padding: 0.5rem 2.5rem 0.5rem 0.75rem; /* Adjust padding for visual appeal */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%236B7280'%3e%3cpath d='M7 7l3-3 3 3m0 6l-3 3-3-3' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.5em 1.5em;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
    </style>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="section-header">
            <h1 class="section-title">User Management</h1>
            <p class="section-subtitle">Manage system users, roles, and permissions</p>
        </div>

        <div class="hall-card rounded-xl mb-8">
            <nav class="flex space-x-8">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Overview
                </a>
                <a href="#" class="nav-link active">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Users
                </a>
                <a href="{{ route('admin.halls') }}" class="nav-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Halls
                </a>
                <a href="{{ route('admin.subjects') }}" class="nav-link">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Subject
                </a>
            </nav>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex flex-col sm:flex-row gap-4 flex-1">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="searchInput" placeholder="Search users..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="flex items-center text-black">
                        <select id="roleFilter" class="select-green">
                            <option value="">All Roles</option>
                            <option value="admin">Admin</option>
                            <option value="professor">Professor</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                </div>
                <button id="addUserBtn" class="btn btn-green flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add User
                </button>
            </div>
        </div>

        <div class="hall-card rounded-lg overflow-hidden ">
            <div class="overflow-x-auto  ">
                <table class="min-w-full divide-y divide-gray-200 ">
                    <thead class="bg-gray-50 ">
                        <tr class=" text-black ">
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="addUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add New User</h3>
                    <button type="button" id="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="addUserForm">
                    @csrf
                    <div class="mb-4">
                        <label for="userName" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" id="userName" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label for="userEmail" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="userEmail" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label for="userRole" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select id="userRole" name="role" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="professor">Professor</option>
                            <option value="student">Student</option>
                        </select>
                    </div>

                    <div class="mb-4 student-fields" id="studentYearField" style="display: none;">
                        <label for="userYear" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                        <select id="userYear" name="year"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select Year</option>
                            <option value="first">First</option>
                            <option value="second">Second</option>
                            <option value="third">Third</option>
                            <option value="fourth">Fourth</option>
                            <option value="fifth">Fifth</option>
                        </select>
                    </div>

                    <div class="mb-4 student-fields" id="studentDepartmentField" style="display: none;">
                        <label for="userDepartment" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <select id="userDepartment" name="department_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select Department</option>
                            @isset($departments)
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="userPassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" id="userPassword" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label for="userPasswordConfirm" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" id="userPasswordConfirm" name="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelBtn"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                                class="btn2"> Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Edit User</h3>
                    <button type="button" id="closeEditModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="editUserForm">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" id="editUserId" name="user_id">
                    <div class="mb-4">
                        <label for="editUserName" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" id="editUserName" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label for="editUserEmail" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="editUserEmail" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label for="editUserRole" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select id="editUserRole" name="role" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="professor">Professor</option>
                            <option value="student">Student</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelEditBtn"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Define global functions so they can be called from onclick/inline event handlers in dynamically generated HTML
        window.editUser = async function(userId) {
            console.log('editUser called with ID:', userId);

            try {
                const response = await fetch(`/admin/api/users/${userId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const user = await response.json();
                console.log('User data received:', user);

                // Populate the edit form
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editUserName').value = user.name;
                document.getElementById('editUserEmail').value = user.email;
                document.getElementById('editUserRole').value = user.role;
                
                // Show the edit modal
                const editUserModal = document.getElementById('editUserModal');
                editUserModal.classList.remove('hidden');
                editUserModal.style.display = 'flex'; // Ensure display is set to flex for centering
            } catch (error) {
                console.error('Error fetching user data:', error);
                alert('Error loading user data: ' + error.message);
            }
        }

        window.deleteUser = async function(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                try {
                    const response = await fetch(`/admin/api/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        // Optionally show a success message before reloading
                        // alert('User deleted successfully!'); 
                        location.reload();
                    } else {
                        const result = await response.json();
                        alert(result.message || 'Error deleting user');
                    }
                } catch (error) {
                    console.error('Error deleting user:', error);
                    alert('Error deleting user');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const addUserBtn = document.getElementById('addUserBtn');
            const addUserModal = document.getElementById('addUserModal');
            const closeModal = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const addUserForm = document.getElementById('addUserForm');
            const userRoleSelect = document.getElementById('userRole'); // Renamed for clarity

            const searchInput = document.getElementById('searchInput');
            const roleFilter = document.getElementById('roleFilter');

            // Edit modal elements
            const editUserModal = document.getElementById('editUserModal');
            const closeEditModal = document.getElementById('closeEditModal');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const editUserForm = document.getElementById('editUserForm');

            // --- Add Modal Logic ---

            function hideAddModal() {
                addUserModal.classList.add('hidden');
                addUserForm.reset();
                // Hide student fields on close/cancel
                document.querySelectorAll('.student-fields').forEach(field => {
                    field.style.display = 'none';
                });
            }

            // Show modal
            addUserBtn.addEventListener('click', function() {
                addUserModal.classList.remove('hidden');
                // Initial reset and hide student fields
                addUserForm.reset();
                document.querySelectorAll('.student-fields').forEach(field => {
                    field.style.display = 'none';
                });
            });

            // Hide modal listeners
            closeModal.addEventListener('click', hideAddModal);
            cancelBtn.addEventListener('click', hideAddModal);
            addUserModal.addEventListener('click', function(e) {
                if (e.target === addUserModal) {
                    hideAddModal();
                }
            });

            // Show/hide student fields based on role selection in Add Modal
            userRoleSelect.addEventListener('change', function() {
                const selectedRole = this.value;
                const studentFields = document.querySelectorAll('.student-fields');
                if (selectedRole === 'student') {
                    studentFields.forEach(field => {
                        field.style.display = 'block';
                    });
                    // Set 'required' on student fields if you need them to be mandatory for students
                    document.getElementById('userYear').setAttribute('required', 'required');
                    document.getElementById('userDepartment').setAttribute('required', 'required');
                } else {
                    studentFields.forEach(field => {
                        field.style.display = 'none';
                    });
                    // Remove 'required' if not a student
                    document.getElementById('userYear').removeAttribute('required');
                    document.getElementById('userDepartment').removeAttribute('required');
                }
            });

            // Handle Add User form submission
            addUserForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());

                // Remove student-specific fields if role is not student, for cleaner API payload
                if (data.role !== 'student') {
                    delete data.year;
                    delete data.department_id;
                }
                
                // Remove password confirmation field as it's not typically needed on the server side 
                // after initial validation (but keep it for the client-side check if implemented)
                delete data.password_confirmation; 
                
                // Remove CSRF token from the body if sending JSON (it's in the header)
                // However, fetch with FormData or JSON.stringify works with @csrf in Blade
                // We'll stick to JSON.stringify as it's cleaner for an API endpoint
                delete data._token; 

                try {
                    const response = await fetch('/admin/api/users', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert('User created successfully!');
                        location.reload();
                    } else {
                        // Handle validation errors or other server-side errors
                        let errorMessage = result.message || 'Error creating user.';
                        if (result.errors) {
                            // Concatenate validation errors if they exist
                            errorMessage += "\n" + Object.values(result.errors).map(e => e.join(', ')).join('\n');
                        }
                        alert(errorMessage);
                    }
                } catch (error) {
                    console.error('Error creating user:', error);
                    alert('Network error or unexpected error during user creation.');
                }
            });


            // --- Edit Modal Logic ---
            
            function hideEditModal() {
                editUserModal.classList.add('hidden');
                editUserForm.reset();
            }

            closeEditModal.addEventListener('click', hideEditModal);
            cancelEditBtn.addEventListener('click', hideEditModal);
            editUserModal.addEventListener('click', function(e) {
                if (e.target === editUserModal) {
                    hideEditModal();
                }
            });

            // Handle edit form submission (Using POST with _method=PUT)
            editUserForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                // Convert FormData to a plain object
                const data = Object.fromEntries(formData.entries());
                const userId = data.user_id;

                try {
                    // Send PUT request (spoofed via POST + _method: 'PUT')
                    const response = await fetch(`/admin/api/users/${userId}`, {
                        method: 'POST', // Use POST for method spoofing in forms
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        // We send the data, including the spoofed _method, in the JSON body
                        body: JSON.stringify({
                            _token: data._token,
                            _method: data._method, // Laravel will interpret this as a PUT request
                            name: data.name,
                            email: data.email,
                            role: data.role
                            // Add other fields if you extend the edit functionality
                        })
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert('User updated successfully!');
                        location.reload();
                    } else {
                        let errorMessage = result.message || 'Error updating user.';
                        if (result.errors) {
                            errorMessage += "\n" + Object.values(result.errors).map(e => e.join(', ')).join('\n');
                        }
                        alert(errorMessage);
                    }
                } catch (error) {
                    console.error('Error updating user:', error);
                    alert('Network error or unexpected error during user update: ' + error.message);
                }
            });


            // --- Data Loading and Filtering Logic ---
            
            async function loadUsers(searchTerm = '', selectedRole = '') {
                try {
                    const params = new URLSearchParams();
                    if (searchTerm) params.append('search', searchTerm);
                    if (selectedRole) params.append('role', selectedRole);

                    const url = `/admin/api/users${params.toString() ? '?' + params.toString() : ''}`;
                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const users = await response.json();
                    updateUsersTable(users);
                } catch (error) {
                    console.error('Error loading users:', error);
                    const tbody = document.getElementById('usersTableBody');
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-red-500">Error loading users. Please check the network and API endpoint.</td></tr>';
                }
            }

            function updateUsersTable(users) {
                const tbody = document.getElementById('usersTableBody');

                if (users.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-500">No users found</td></tr>';
                    return;
                }

                tbody.innerHTML = users.map(user => {
                    // Simple logic for initials, may fail on names with non-space separators
                    const initials = user.name.split(' ').map(n => n.charAt(0)).join('').toUpperCase().substring(0, 2); 
                    const roleColors = {
                        'admin': 'purple',
                        'professor': 'blue',
                        'student': 'green'
                    };
                    const statusColors = {
                        'active': 'green',
                        'inactive': 'red',
                        // Assuming a 'pending' status for newly created users without confirmation
                        'pending': 'yellow' 
                    };
                    
                    // Determine the user status (assuming a simple default 'active' if not set, or using the actual 'status' field)
                    const userStatus = user.status || 'active'; // Default to active if status field is missing
                    
                    const roleColor = roleColors[user.role] || 'gray';
                    const statusColor = statusColors[userStatus] || 'gray';
                    
                    const createdAtDate = user.created_at ? new Date(user.created_at).toLocaleDateString() : 'N/A';

                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-10 h-10 bg-${roleColor}-500 rounded-full flex-shrink-0">
                                        <span class="text-sm font-medium text-white">${initials}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">${user.name}</div>
                                        <div class="text-sm text-gray-500">${user.email}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-${roleColor}-100 text-${roleColor}-800 text-xs px-2 py-1 rounded-full capitalize">${user.role}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${createdAtDate}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-${statusColor}-100 text-${statusColor}-800 text-xs px-2 py-1 rounded-full capitalize">${userStatus}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button onclick="editUser(${user.id})" class="text-purple-600 hover:text-purple-900" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="deleteUser(${user.id})" class="text-red-600 hover:text-red-900" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            // Load users on page load
            loadUsers();

            // Event listeners for filters
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.trim();
                const selectedRole = roleFilter.value;
                loadUsers(searchTerm, selectedRole);
            });

            roleFilter.addEventListener('change', function() {
                const searchTerm = searchInput.value.trim();
                const selectedRole = this.value;
                loadUsers(searchTerm, selectedRole);
            });
        });
    </script>
</x-admin-layout>