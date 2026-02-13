<x-student-app title="Edit Profile">
    <div class="max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
            <p class="text-gray-600">Update your personal information</p>
        </div>

        <!-- Edit Profile Form -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
            </div>

            <!-- Form Section -->
            <form method="POST" action="{{ route('student.update-profile') }}" class="p-6">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Information -->
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
                        </div>

                       
                    </div>

                    <!-- Student Information -->
                    <div class="space-y-4">
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                            <select name="year" id="year" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
                                <option value="first" {{ old('year', $student->year) == 'first' ? 'selected' : '' }}>First Year</option>
                                <option value="second" {{ old('year', $student->year) == 'second' ? 'selected' : '' }}>Second Year</option>
                                <option value="third" {{ old('year', $student->year) == 'third' ? 'selected' : '' }}>Third Year</option>
                                <option value="fourth" {{ old('year', $student->year) == 'fourth' ? 'selected' : '' }}>Fourth Year</option>
                                <option value="fifth" {{ old('year', $student->year) == 'fifth' ? 'selected' : '' }}>Fifth Year</option>
                            </select>
                        </div>
                          <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">University number:</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        
                    </div>
                </div>

                <!-- Password Change Section -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Change Password (Optional)</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('student.profile') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Back
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-student-app>
