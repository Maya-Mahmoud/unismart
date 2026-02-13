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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- منع الحركة عند الضغط -->
    <style>
        a:focus, a:active {
            outline: none !important;
            box-shadow: none !important;
            transform: none !important;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-600 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Hall Manager</h1>
                        <p class="text-xs text-gray-500">College Management System</p>
                    </div>
                </div>

                <!-- Right side -->
                <div class="flex items-center space-x-4">
                    <!-- Notification Icon with Dropdown -->
                    <div class="relative">
                        <button id="student-notification-bell" type="button" class="flex items-center bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs hover:bg-red-200 transition-colors focus:outline-none">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002
                                    6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67
                                    6.165 6 8.388 6 11v3.159c0 .538-.214
                                    1.055-.595 1.436L4 17h5m6 0a3 3 0
                                    11-6 0h6z"/>
                            </svg>
                            <span id="student-notification-count">0</span>
                        </button>

                        <!-- Dropdown -->
                        <div id="student-notification-dropdown" class="hidden absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl z-50 overflow-hidden">
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
                                <h3 class="text-white font-semibold">
                                Notifications</h3>
                            </div>

                            <!-- Notifications List -->
                            <div id="student-notifications-list" class="max-h-96 overflow-y-auto divide-y">
                                <div class="text-center py-8 text-gray-500">
                                    <p class="text-sm">Loading...</p>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div id="student-notifications-pagination" class="border-t px-4 py-3 bg-gray-50 flex justify-center gap-2">
                            </div>
                        </div>
                    </div>

                    <!-- Profile Button -->
                    <div class="flex items-center">
                        <a href="{{ route('student.profile') }}"
                           class="flex items-center bg-gray-100 px-2 py-1 rounded text-xs text-gray-700 hover:text-gray-900 hover:bg-gray-200 focus:outline-none focus:ring-0 select-none">
                            <svg class="w-4 h-4 text-gray-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="font-medium">{{ Auth::user()->name }}</span>
                        </a>
                        <span class="ml-2 bg-purple-100 text-purple-800 text-xs px-1.5 py-0.5 rounded">{{ Auth::user()->role }}</span>
                    </div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center text-gray-600 hover:text-gray-900 focus:outline-none">
                            <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Secondary Navigation Tabs -->
    <nav class="border-b border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8" aria-label="Tabs" role="tablist">
                <a href="{{ route('student.dashboard') }}"
                   class="{{ request()->routeIs('student.dashboard') ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                   {{ request()->routeIs('student.dashboard') ? 'aria-current="page"' : '' }} role="tab">My Schedule</a>

                <a href="{{ route('student.subjects') }}"
                   class="{{ request()->routeIs('student.subjects') ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                   {{ request()->routeIs('student.subjects') ? 'aria-current="page"' : '' }} role="tab">Subjects</a>

                <a href="{{ route('student.scan-qr') }}"
                   class="{{ request()->routeIs('student.scan-qr') ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                   {{ request()->routeIs('student.scan-qr') ? 'aria-current="page"' : '' }} role="tab">Scan QR</a>

                <a href="{{ route('student.attendance') }}"
                   class="{{ request()->routeIs('student.attendance') ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                   {{ request()->routeIs('student.attendance') ? 'aria-current="page"' : '' }} role="tab">My presence</a>

                    <a href="{{ route('student.chat') }}"
                   class="{{ request()->routeIs('student.chat.view') ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                   {{ request()->routeIs('student.chat.view') ? 'aria-current="page"' : '' }} role="tab">chat</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @yield('content')
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationBell = document.getElementById('student-notification-bell');
        const notificationDropdown = document.getElementById('student-notification-dropdown');
        const notificationCount = document.getElementById('student-notification-count');
        const notificationList = document.getElementById('student-notifications-list');
        const notificationPagination = document.getElementById('student-notifications-pagination');

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
            notificationList.innerHTML = '<div class="px-4 py-8 text-center"><div class="text-sm text-gray-500">Loading...</div></div>';
            
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
                    notificationList.innerHTML = '<div class="px-4 py-8 text-center"><div class="text-sm text-red-500">Error: ' + error.message + '</div></div>';
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
            notificationList.innerHTML = '';
            notificationPagination.innerHTML = '';

            const notifications = showingOlder ? readNotifications : unreadNotifications;

            if (notifications.length === 0) {
                notificationList.innerHTML = '<div class="px-4 py-8 text-center"><div class="text-sm text-gray-500">' + 
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
                    notificationPagination.appendChild(button);
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
                    notificationPagination.appendChild(button);
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
                
                notificationList.appendChild(item);
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
                notificationPagination.appendChild(button);
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
                notificationPagination.appendChild(button);
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

        // Get notification color based on type
        function getNotificationColor(type) {
            switch (type) {
                case 'info':    return '#3B82F6'; // Blue
                case 'warning': return '#F59E0B'; // Orange
                case 'success': return '#10B981'; // Green
                case 'error':   return '#EF4444'; // Red
                default:        return '#6B7280'; // Gray
            }
        }

        // Get notification icon based on type
        function getNotificationIcon(type) {
            switch (type) {
                case 'info':    return 'ℹ️';
                case 'warning': return '⚠️';
                case 'success': return '✓';
                case 'error':   return '✕';
                default:        return '●';
            }
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
</script>

@livewireScripts
</body>
</html>
