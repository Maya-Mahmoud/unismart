<x-admin-layout title="My Profile">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-600">View your personal information</p>
        </div>

        <!-- Profile Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Information -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">User Information</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-700">Full Name:</span>
                                <span class="text-sm text-gray-900">{{ $user->name }}</span>
                            </div>

                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-700">Email Address:</span>
                                <span class="text-sm text-gray-900">{{ $user->email }}</span>
                            </div>

                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-700">Role:</span>
                                <span class="text-sm text-gray-900">{{ ucfirst($user->role) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Account Information</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-700">Department:</span>
                                <span class="text-sm text-gray-900">{{ $user->department->name ?? 'Not assigned' }}</span>
                            </div>

                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-700">Member Since:</span>
                                <span class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Button -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <button type="button" onclick="openEditModal()" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Profile
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" id="my-modal">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Edit Profile Information</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
                        </div>
                    </div>

                    <!-- Password Change Section -->
                    <div class="pt-4 border-t border-gray-200">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Change Password (Optional)</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                                <input type="password" name="current_password" id="current_password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            </div>

                            <div class="md:col-span-2">
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeEditModal()" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal() {
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</x-admin-layout>
