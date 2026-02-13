@props(['title' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
       
        <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
   <body class="dark-theme">
   <div class="dashboard-container">
            <!-- Header -->
        
<header>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center h-16">

            <!-- Logo -->
            <div class="flex items-center">
                <div class="flex items-center justify-center w-16 h-16 rounded-lg mr-3
                            bg-gradient-to-br from-green-400 to-green-700 shadow-lg ring-2 ring-gray-400/80 mb-4 ">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>

                <div>
                    <h4 class="brand-title ">unismart</h4>
                    <p class="brand-subtitle">College Management System</p>
                </div> 
            </div>

            <!-- Right side -->
            <div class="flex items-center space-x-4">

                <!-- Theme Toggle Button -->
                <button id="theme-toggle-btn" 
                        class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-all duration-300 cursor-pointer hover:-translate-y-0.5">
                    <svg id="sun-icon" class="theme-icon w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg id="moon-icon" class="theme-icon w-5 h-5 text-gray-700 dark:text-gray-300 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <!-- Notification Bell -->
                <div class="relative">
                    <button id="admin-notification-bell" type="button" 
                            class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-all duration-300 cursor-pointer hover:-translate-y-0.5 relative">
                        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z"/>
                        </svg>
                        <span id="admin-notification-count" 
                              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center text-[10px] font-bold">
                            0
                        </span>
                    </button>

                    <!-- Dropdown (يبقى نفس الكود السابق بدون تغيير) -->
                    <div id="admin-notification-dropdown" class="hidden absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-50 overflow-hidden">
                        <!-- ... باقي كود الـ dropdown ... -->
                    </div>
                </div>

                <!-- Profile Button -->
                <a href="{{ Auth::user()->role === 'admin' || Auth::user()->role === 'professor' ? route('admin.profile') : '#' }}"
                   class="flex items-center space-x-3 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-all duration-300 cursor-pointer hover:-translate-y-0.5">
                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                </a>

                <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" x-data @submit.prevent="$root.submit()">
            @csrf
            <button type="submit" 
                    class="btn btn-green flex items-center">
                
                <span>Logout </span>
                
                <!-- الأيقونة حد الكلمة (على اليمين) -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </button>
        </form>

            </div>
        </div>
    </div>
</header>

            <!-- Navigation -->
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-12">
                        <div class="flex">
                            @can('admin panel')
                                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ in_array(request()->route()->getName(), ['admin.dashboard', 'admin.users', 'admin.halls', 'admin.subjects']) ? 'active' : '' }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-2m-9-2l4 2m0-5L9 7m5 0L9 7"/>
                                    </svg>
                                    Admin Panel
                                </a>
                            @endcan

                            <a href="{{ route('halls.index') }}" class="nav-link {{ request()->routeIs('halls.index') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                </svg>
                                Book Halls
                            </a>

                            @can('lectures')
                                <a href="{{ route('admin.lectures') }}" class="nav-link {{ request()->routeIs('admin.lectures') ? 'active' : '' }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"/>
                                    </svg>
                                    Lectures
                                </a>
                            @endcan

                            @if(Auth::user()->role === 'professor')
                                <a href="{{ route('professor.lectures') }}" class="nav-link {{ request()->routeIs('professor.lectures') ? 'active' : '' }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"/>
                                    </svg>
                                    Lectures
                                </a>
                            @endif

                            <a href="{{ route('admin.generate-qr') }}" class="nav-link {{ request()->routeIs('admin.generate-qr') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Generate QR
                            </a>

                            <a href="{{ route('admin.advanced-scheduler') }}" class="nav-link {{ request()->routeIs('admin.advanced-scheduler') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Advanced Scheduler
                            </a>

                            <a href="{{ route('admin.performance') }}" class="nav-link {{ request()->routeIs('admin.performance') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Performance
                            </a>
                            
                            <a href="{{ route('admin.absence.alerts') }}" class="nav-link {{ request()->routeIs('admin.absence.alerts') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Alerts
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="py-8">
                {{ $slot }}
            </main>

        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const notificationBell = document.getElementById('admin-notification-bell');
                const notificationDropdown = document.getElementById('admin-notification-dropdown');
                const notificationCount = document.getElementById('admin-notification-count');
                const notificationsList = document.getElementById('admin-notifications-list');
                const notificationsPagination = document.getElementById('admin-notifications-pagination');

                let showingOlder = false;
                let allNotifications = [];
                let unreadNotifications = [];
                let readNotifications = [];

                // Remove sample notifications - use real API instead
                // const sampleNotifications = [ ... ];

                // Toggle dropdown
                notificationBell.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (notificationDropdown.classList.contains('hidden')) {
                        notificationDropdown.classList.remove('hidden');
                        if (allNotifications.length === 0) {
                            loadNotifications();
                        }
                        // Mark all visible unread notifications as read when opening dropdown
                        if (!showingOlder) {
                            markVisibleAsRead();
                        }
                    } else {
                        notificationDropdown.classList.add('hidden');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!notificationDropdown.contains(e.target) && !notificationBell.contains(e.target)) {
                        notificationDropdown.classList.add('hidden');
                    }
                });

                // Load notifications from API
                function loadNotifications() {
                    notificationsList.innerHTML = '<div class="px-4 py-8 text-center"><div class="text-sm text-gray-500">Loading...</div></div>';
                    
                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    
                    // Fetch from API instead of using sample data
                    fetch('/api/notifications', {
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            // Check if response is OK
                            if (!response.ok) {
                                console.error('API response error:', response.status, response.statusText);
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Notifications data:', data);
                            if (data.success && data.data) {
                                // Transform API data to match our format
                                allNotifications = data.data.map(notif => ({
                                    id: notif.id,
                                    title: notif.data?.title || 'Notification',
                                    message: notif.data?.message || '',
                                    type: notif.data?.type || 'info',
                                    created_at: notif.created_at,
                                    read_at: notif.read_at
                                }));
                            } else if (Array.isArray(data)) {
                                // If data is directly array (no success wrapper)
                                allNotifications = data.map(notif => ({
                                    id: notif.id,
                                    title: notif.data?.title || 'Notification',
                                    message: notif.data?.message || '',
                                    type: notif.data?.type || 'info',
                                    created_at: notif.created_at,
                                    read_at: notif.read_at
                                }));
                            } else {
                                allNotifications = [];
                            }
                            separateNotifications();
                            showingOlder = false;
                            displayNotifications();
                        })
                        .catch(error => {
                            console.error('Error loading notifications:', error);
                            notificationsList.innerHTML = '<div class="px-4 py-8 text-center"><div class="text-sm text-red-500">Error: ' + error.message + '</div></div>';
                        });
                }

                // Separate unread and read notifications
                function separateNotifications() {
                    unreadNotifications = allNotifications.filter(n => !n.read_at).sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                    readNotifications = allNotifications.filter(n => n.read_at).sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                    updateNotificationCount();
                }

                // Display notifications
                function displayNotifications() {
                    notificationsList.innerHTML = '';
                    notificationsPagination.innerHTML = '';

                    const notifications = showingOlder ? readNotifications : unreadNotifications;

                    if (notifications.length === 0) {
                        notificationsList.innerHTML = '<div class="px-4 py-8 text-center"><div class="text-sm text-gray-500">' + 
                            (showingOlder ? 'No older notifications' : 'No new notifications') + '</div></div>';
                        
                        // Show View Older button only if we have read notifications and showing unread
                        if (!showingOlder && readNotifications.length > 0) {
                            const button = document.createElement('button');
                            button.type = 'button';
                            button.className = 'w-full px-4 py-2 text-center text-sm text-blue-600 hover:bg-gray-100 border-t';
                            button.textContent = 'View Older';
                            button.addEventListener('click', (e) => {
                                e.stopPropagation();
                                showingOlder = true;
                                displayNotifications();
                            });
                            notificationsPagination.appendChild(button);
                        }
                        
                        // Show Back button if viewing older
                        if (showingOlder) {
                            const button = document.createElement('button');
                            button.type = 'button';
                            button.className = 'w-full px-4 py-2 text-center text-sm text-blue-600 hover:bg-gray-100 border-t';
                            button.textContent = 'Back to New';
                            button.addEventListener('click', (e) => {
                                e.stopPropagation();
                                showingOlder = false;
                                displayNotifications();
                            });
                            notificationsPagination.appendChild(button);
                        }
                        return;
                    }

                    // Display notification items
                    notifications.forEach(notification => {
                        const item = document.createElement('div');
                        item.className = 'px-4 py-3 border-b border-gray-200 hover:bg-gray-50 cursor-pointer transition ' + 
                            (notification.read_at ? 'bg-white' : 'bg-blue-50');
                        
                        const date = new Date(notification.created_at);
                        const timeAgo = getTimeAgo(date);
                        
                        item.innerHTML = `
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-white text-lg font-semibold" style="background-color: ${getNotificationColor(notification.type)}">
                                    ${getNotificationIcon(notification.type)}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">${notification.title}</p>
                                    <p class="text-sm text-gray-600 mt-1">${notification.message}</p>
                                    <p class="text-xs text-gray-400 mt-1">${timeAgo}</p>
                                </div>
                            </div>
                        `;
                        
                        item.addEventListener('click', () => {
                            markNotificationAsRead(notification.id);
                        });
                        
                        notificationsList.appendChild(item);
                    });

                    // Show View Older or Back button
                    if (!showingOlder && readNotifications.length > 0) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'w-full px-4 py-2 text-center text-sm text-blue-600 hover:bg-gray-100 border-t';
                        button.textContent = 'View Older';
                        button.addEventListener('click', (e) => {
                            e.stopPropagation();
                            showingOlder = true;
                            displayNotifications();
                        });
                        notificationsPagination.appendChild(button);
                    }
                    
                    if (showingOlder) {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'w-full px-4 py-2 text-center text-sm text-blue-600 hover:bg-gray-100 border-t';
                        button.textContent = 'Back to New';
                        button.addEventListener('click', (e) => {
                            e.stopPropagation();
                            showingOlder = false;
                            displayNotifications();
                        });
                        notificationsPagination.appendChild(button);
                    }
                }

                // Mark notification as read
                function markNotificationAsRead(notificationId) {
                    const notification = allNotifications.find(n => n.id === notificationId);
                    if (notification && !notification.read_at) {
                        notification.read_at = new Date();
                        separateNotifications();
                        displayNotifications();
                    }
                }

                // Mark all visible unread notifications as read
                function markVisibleAsRead() {
                    unreadNotifications.forEach(n => {
                        if (!n.read_at) {
                            n.read_at = new Date();
                        }
                    });
                    separateNotifications();
                }

                // Update notification count
                function updateNotificationCount() {
                    const count = unreadNotifications.length;
                    if (count > 0) {
                        notificationCount.textContent = count;
                        notificationBell.style.display = 'flex';
                    } else {
                        notificationBell.style.display = 'flex';
                        notificationCount.textContent = '';
                        notificationCount.classList.add('hidden');
                    }
                }

                // Get notification color based on type
                function getNotificationColor(type) {
                    switch (type) {
                        case 'info':
                            return '#3B82F6'; // Blue
                        case 'warning':
                            return '#F59E0B'; // Orange
                        case 'success':
                            return '#10B981'; // Green
                        case 'error':
                            return '#EF4444'; // Red
                        default:
                            return '#6B7280'; // Gray
                    }
                }

                // Get notification icon based on type
                function getNotificationIcon(type) {
                    switch (type) {
                        case 'info':
                            return 'ℹ️';
                        case 'warning':
                            return '⚠️';
                        case 'success':
                            return '✓';
                        case 'error':
                            return '✕';
                        default:
                            return '●';
                    }
                }

                // Format time ago
                function getTimeAgo(date) {
                    const now = new Date();
                    const seconds = Math.floor((now - date) / 1000);
                    
                    if (seconds < 60) return 'Just now';
                    if (seconds < 3600) return Math.floor(seconds / 60) + ' minutes ago';
                    if (seconds < 86400) return Math.floor(seconds / 3600) + ' hours ago';
                    if (seconds < 604800) return Math.floor(seconds / 86400) + ' days ago';
                    
                    return date.toLocaleDateString();
                }

                // Load initial notifications count
                updateNotificationCount();

                // Auto-refresh notifications every 30 seconds when dropdown is open
                setInterval(() => {
                    if (!notificationDropdown.classList.contains('hidden')) {
                        loadNotifications();
                        if (!showingOlder) {
                            markVisibleAsRead();
                        }
                    }
                }, 30000);
            });
        document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('theme-toggle-btn');
    const sun = document.getElementById('sun-icon');
    const moon = document.getElementById('moon-icon');
    const body = document.body;
    const header = document.querySelector('header'); // نضيف الـ header

    // تحميل الثيم المحفوظ
    if (localStorage.getItem('theme') === 'light') {
        body.classList.remove('dark-theme');
        body.classList.add('light-mode');
        header.classList.add('light-mode'); // نضيف الكلاس على الـ header
        sun.classList.add('hidden');
        moon.classList.remove('hidden');
    } else {
        body.classList.add('dark-theme');
        body.classList.remove('light-mode');
        header.classList.remove('light-mode'); // نشيل الكلاس من الـ header
        sun.classList.remove('hidden');
        moon.classList.add('hidden');
    }

    // التبديل
    toggleBtn.addEventListener('click', () => {
        if (body.classList.contains('dark-theme')) {
            // من Dark لـ Light
            body.classList.remove('dark-theme');
            body.classList.add('light-mode');
            header.classList.add('light-mode');
            localStorage.setItem('theme', 'light');
            sun.classList.add('hidden');
            moon.classList.remove('hidden');
        } else {
            // من Light لـ Dark
            body.classList.remove('light-mode');
            body.classList.add('dark-theme');
            header.classList.remove('light-mode');
            localStorage.setItem('theme', 'dark');
            sun.classList.remove('hidden');
            moon.classList.add('hidden');
        }
    });
});
        </script>

    </body>
</html>
