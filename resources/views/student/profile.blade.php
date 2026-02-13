<x-student-app title="My Profile">
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
                                <span class="text-sm font-medium text-gray-700">University number:</span>
                                <span class="text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Academic Information</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-700">Department:</span>
                                <span class="text-sm text-gray-900">{{ $student->department->name ?? 'Not assigned' }}</span>
                            </div>

                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-700">Year:</span>
                                <span class="text-sm text-gray-900">{{ ucfirst($student->year ?? 'Not specified') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Button -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('student.edit-profile') }}" 
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-student-app>
