<div class="relative">
    <!-- Notification Bell Icon -->
    <button id="notification-bell" class="relative inline-flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-900 focus:outline-none group">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span id="notification-badge" class="hidden absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1 -translate-y-1 bg-red-600 rounded-full">
            0
        </span>
    </button>

    <!-- Notification Dropdown -->
    <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
            <div class="flex items-center justify-between">
                <h3 class="text-white font-semibold text-lg">Notifications</h3>
                <button id="mark-all-read-btn" class="text-blue-100 hover:text-white text-sm font-medium transition">
                    Mark all as read
                </button>
            </div>
        </div>

        <!-- Notifications List -->
        <div id="notifications-list" class="max-h-96 overflow-y-auto">
            <div class="text-center py-8 text-gray-500">
                <p class="text-sm">Loading notifications...</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t px-4 py-3 bg-gray-50">
            <a href="{{ route('notifications.index') }}" class="text-center block text-blue-600 hover:text-blue-800 text-sm font-medium transition">
                View All Notifications
            </a>
        </div>
    </div>
</div>

<style>
    .notification-item {
        transition: all 0.3s ease;
    }
    
    .notification-item:hover {
        background-color: #f3f4f6;
    }
    
    .notification-item.unread {
        background-color: #eff6ff;
        border-left: 3px solid #3b82f6;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationBell = document.getElementById('notification-bell');
    const notificationDropdown = document.getElementById('notification-dropdown');
    const notificationsList = document.getElementById('notifications-list');
    const notificationBadge = document.getElementById('notification-badge');
    const markAllReadBtn = document.getElementById('mark-all-read-btn');

    // Toggle dropdown
    notificationBell.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationDropdown.classList.toggle('hidden');
        if (!notificationDropdown.classList.contains('hidden')) {
            loadNotifications();
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationBell.contains(e.target) && !notificationDropdown.contains(e.target)) {
            notificationDropdown.classList.add('hidden');
        }
    });

    // Load unread notifications
    function loadNotifications() {
        fetch('/api/notifications/unread')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    renderNotifications(data.data);
                } else {
                    notificationsList.innerHTML = '<div class="text-center py-8 text-gray-500"><p class="text-sm">No notifications</p></div>';
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                notificationsList.innerHTML = '<div class="text-center py-8 text-red-500"><p class="text-sm">Error loading notifications</p></div>';
            });
    }

    // Render notifications
    function renderNotifications(notifications) {
        if (notifications.length === 0) {
            notificationsList.innerHTML = '<div class="text-center py-8 text-gray-500"><p class="text-sm">No unread notifications</p></div>';
            return;
        }

        let html = '';
        notifications.forEach(notification => {
            const data = notification.data || {};
            const message = data.message || 'New notification';
            const type = data.type || 'default';
            const createdAt = new Date(notification.created_at).toLocaleString();
            const isUnread = !notification.read_at;

            let icon = '📢';
            if (type === 'professor_reminder') {
                icon = '⏰';
            } else if (type === 'qr_code_ready') {
                icon = '✅';
            } else if (type === 'student_scan_qr') {
                icon = '📱';
            }

            html += `
                <div class="notification-item ${isUnread ? 'unread' : ''} px-4 py-3 border-b hover:bg-gray-50 cursor-pointer" data-notification-id="${notification.id}">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">${icon}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 line-clamp-2">${message}</p>
                            <p class="text-xs text-gray-500 mt-1">${createdAt}</p>
                        </div>
                        <button class="delete-notification-btn ml-2 text-gray-400 hover:text-gray-600 text-lg" data-notification-id="${notification.id}">
                            ✕
                        </button>
                    </div>
                </div>
            `;
        });

        notificationsList.innerHTML = html;

        // Add event listeners to notification items
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (!e.target.classList.contains('delete-notification-btn')) {
                    const notificationId = this.getAttribute('data-notification-id');
                    markNotificationAsRead(notificationId);
                }
            });
        });

        // Add delete listeners
        document.querySelectorAll('.delete-notification-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const notificationId = this.getAttribute('data-notification-id');
                deleteNotification(notificationId);
            });
        });
    }

    // Update unread count
    function updateUnreadCount() {
        fetch('/api/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const count = data.unread_count;
                    if (count > 0) {
                        notificationBadge.textContent = count;
                        notificationBadge.classList.remove('hidden');
                    } else {
                        notificationBadge.classList.add('hidden');
                    }
                }
            })
            .catch(error => console.error('Error updating unread count:', error));
    }

    // Mark notification as read
    function markNotificationAsRead(notificationId) {
        fetch(`/api/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
                updateUnreadCount();
            }
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }

    // Mark all as read
    markAllReadBtn.addEventListener('click', function(e) {
        e.preventDefault();
        fetch('/api/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
                updateUnreadCount();
            }
        })
        .catch(error => console.error('Error marking all as read:', error));
    });

    // Delete notification
    function deleteNotification(notificationId) {
        fetch(`/api/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
                updateUnreadCount();
            }
        })
        .catch(error => console.error('Error deleting notification:', error));
    }

    // Load initial state
    updateUnreadCount();

    // Refresh notifications every 30 seconds
    setInterval(updateUnreadCount, 30000);
});
</script>



