<x-admin-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="section-header">
            <h1 class="section-title">Admin Panel</h1>
            <p class="section-subtitle">Manage users, halls, and system settings</p>
        </div>
      

        <!-- Sub Navigation -->
        <div class="hall-card rounded-xl mb-8 overflow-hidden">
            
                <nav class="flex space-x-10">
                    <a href="#"  class="nav-link active">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Overview
                    </a>
                    <a href="{{ route('admin.users') }}" class="nav-link">
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

        <!-- Statistics Cards with Glowing Gradients -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
            <!-- Total Users (Purple Glow) -->
            <div class="relative rounded-2xl overflow-hidden backdrop-blur-md shadow-2xl border border-purple-500/20" style="background: linear-gradient(135deg, rgba(147, 51, 234, 0.8), rgba(79, 70, 229, 0.6)); box-shadow: 0 8px 32px rgba(147, 51, 234, 0.4);">
                <div class="p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-l font-medium opacity-90">Total Users</p>
                            <p class="text-4xl font-bold mt-2">{{ $totalUsers }}</p>
                            <p class="text-l opacity-90 mt-1">↗ {{ $userChange }}</p>
                            <p class="text-l opacity-80 mt-3">{{ $admins }} Admins • {{ $professors }} Professors • {{ $students }} Students</p>
                        </div>
                        <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl backdrop-blur-sm">
                            <svg class="w-10 h-10 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hall Utilization (Blue Glow) -->
            <div class="relative rounded-2xl overflow-hidden backdrop-blur-md shadow-2xl border border-blue-500/20" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(37, 99, 235, 0.6)); box-shadow: 0 8px 32px rgba(59, 130, 246, 0.4);">
                <div class="p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-l font-medium opacity-90">Hall Utilization</p>
                            <p class="text-4xl font-bold mt-2">{{ $hallUtilization }}%</p>
                            <p class="text-l opacity-90 mt-1">↗ {{ $hallChange }}</p>
                            <p class="text-l opacity-80 mt-3">{{ $bookedHalls }}/{{ $totalHalls }} halls booked</p>
                        </div>
                        <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl backdrop-blur-sm">
                            <svg class="w-10 h-10 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Lectures (Green Glow) -->
            <div class="relative rounded-2xl overflow-hidden backdrop-blur-md shadow-2xl border border-green-500/20" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.8), rgba(21, 128, 61, 0.6)); box-shadow: 0 8px 32px rgba(34, 197, 94, 0.4);">
                <div class="p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-l font-medium opacity-90">Today's Lectures</p>
                            <p class="text-4xl font-bold mt-2">{{ $todayLectures }}</p>
                            <p class="text-l opacity-90 mt-1">↗ {{ $lectureChange }}</p>
                            <p class="text-l opacity-80 mt-3">{{ $totalLectures }} total lectures</p>
                        </div>
                        <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl backdrop-blur-sm">
                            <svg class="w-10 h-10 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Subject (Charts) - بقوا زي ما هن عشان ما يتعارضوش مع الـ glow -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- User Distribution -->
    <div class="bg-white dark:bg-gray-800 rounded-xl hall-card">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">User Distribution</h3>
        <div class="space-y-5">
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Admins</span>
                    <span class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $admins }}</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                    <div 
                        class="h-3 rounded-full progress-fill bg-purple-600"
                        data-target="{{ $adminPercent }}"
                    ></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Professors</span>
                    <span class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $professors }}</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                    <div 
                        class="h-3 rounded-full progress-fill bg-blue-600"
                        data-target="{{ $professorPercent }}"
                    ></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Students</span>
                    <span class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $students }}</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                    <div 
                        class="h-3 rounded-full progress-fill bg-green-600"
                        data-target="{{ $studentPercent }}"
                    ></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hall Utilization -->
    <div class="bg-white dark:bg-gray-800 rounded-xl hall-card">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Hall Utilization</h3>
        <div class="space-y-5">
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Booked Halls</span>
                    <span class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $bookedHalls }}</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                    <div 
                        class="h-3 rounded-full progress-fill bg-red-600"
                        data-target="{{ $bookedPercent }}"
                    ></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Available Halls</span>
                    <span class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $availableHalls }}</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                    <div 
                        class="h-3 rounded-full progress-fill bg-green-600"
                        data-target="{{ $availablePercent }}"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- السكريبت في نهاية الـ body أو في layout -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // تأخير خفيف عشان الصفحة تحمل أولاً (اختياري بس يعطي تأثير أجمل)
        setTimeout(() => {
            document.querySelectorAll('.progress-bar').forEach(bar => {
                const width = bar.getAttribute('data-width');
                bar.style.width = width + '%';
            });
        }, 200); // 200ms تأخير
    });
    document.addEventListener('DOMContentLoaded', function () {
        // نستنى شوي عشان الصفحة تحمل ونعطي تأثير أجمل
        setTimeout(function () {
            document.querySelectorAll('.progress-fill').forEach(function (bar) {
                const targetWidth = bar.getAttribute('data-target');
                bar.style.width = '0%'; // نبدأ من الصفر صراحة
                bar.style.transition = 'width 1.2s cubic-bezier(0.4, 0, 0.2, 1)'; // حركة سلسة جداً
                // نستخدم requestAnimationFrame عشان ما في تأخير
                requestAnimationFrame(function () {
                    bar.style.width = targetWidth + '%';
                });
            });
        }, 3); // تأخير 300ms عشان يبدأ بعد ما الصفحة تستقر
    });
</script>

        <!-- Recent Activity -->
        
        <div class="mt-10"></div> <!-- أو mt-20 لو بدك أكبر -->

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recentUsers as $user)
                    <div class="p-6 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-{{ $user->role == 'admin' ? 'purple' : ($user->role == 'professor' ? 'blue' : 'green') }}-100 dark:bg-{{ $user->role == 'admin' ? 'purple' : ($user->role == 'professor' ? 'blue' : 'green') }}-900/30 text-{{ $user->role == 'admin' ? 'purple' : ($user->role == 'professor' ? 'blue' : 'green') }}-600 dark:text-{{ $user->role == 'admin' ? 'purple' : ($user->role == 'professor' ? 'blue' : 'green') }}-400 font-semibold text-lg">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="px-4 py-2 text-xs font-medium rounded-full bg-{{ $user->role == 'admin' ? 'purple' : ($user->role == 'professor' ? 'blue' : 'green') }}-100 dark:bg-{{ $user->role == 'admin' ? 'purple' : ($user->role == 'professor' ? 'blue' : 'green') }}-900/30 text-{{ $user->role == 'admin' ? 'purple' : ($user->role == 'professor' ? 'blue' : 'green') }}-800 dark:text-{{ $user->role == 'admin' ? 'purple' : ($user->role == 'professor' ? 'blue' : 'green') }}-300 capitalize">
                            {{ $user->role }}
                        </span>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                        No recent activity
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>