<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('All Notifications') }}
            </h2>
            <button id="mark-all-btn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Mark All as Read
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Filter Tabs -->
                <div class="border-b border-gray-200">
                    <div class="px-6 py-4 flex gap-4">
                        <button class="filter-btn px-4 py-2 rounded font-medium text-gray-700 hover:bg-gray-100 active" data-filter="all">
                            All Notifications
                        </button>
                        <button class="filter-btn px-4 py-2 rounded font-medium text-gray-700 hover:bg-gray-100" data-filter="unread">
                            Unread
                        </button>
                        <button class="filter-btn px-4 py-2 rounded font-medium text-gray-700 hover:bg-gray-100" data-filter="read">
                            Read
                                            </button>
                                </div>
                            </div>

                <!-- Notifications List -->
                <div id="notifications-container" class="divide-y divide-gray-200">
                    <div class="text-center py-12">
                        <p class="text-gray-500">Loading notifications...</p>
                    </div>
                    </div>

                <!-- Pagination -->
                <div id="pagination-container" class="px-6 py-4 border-t border-gray-200">
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let currentFilter = 'all';

        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications(1);

            // Filter buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active', 'bg-blue-100', 'text-blue-700'));
                    this.classList.add('active', 'bg-blue-100', 'text-blue-700');
                    currentFilter = this.getAttribute('data-filter');
                    currentPage = 1;
                    loadNotifications(1);
                });
            });

            // Mark all as read
            document.getElementById('mark-all-btn').addEventListener('click', function() {
                if (confirm('Are you sure you want to mark all notifications as read?')) {
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
                            loadNotifications(1);
                        }
                    });
                }
            });
        });

        function loadNotifications(page) {
            let url = `/api/notifications?page=${page}`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderNotifications(data.data, data.total);
                        renderPagination(data.total, data.per_page, page);
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    document.getElementById('notifications-container').innerHTML = 
                        '<div class="text-center py-12"><p class="text-red-500">Error loading notifications</p></div>';
                });
        }

        function renderNotifications(notifications, total) {
            const container = document.getElementById('notifications-container');
            
            if (!notifications || notifications.length === 0) {
                container.innerHTML = '<div class="text-center py-12"><p class="text-gray-500">No notifications</p></div>';
                return;
            }

            let html = '';
            notifications.forEach(notification => {
                const data = notification.data || {};
                const message = data.message || 'New notification';
                const type = data.type || 'default';
                const isUnread = !notification.read_at;
                const createdAt = new Date(notification.created_at).toLocaleString();

                let icon = '📢';
                let bgColor = 'bg-blue-50';
                
                if (type === 'professor_reminder') {
                    icon = '⏰';
                    bgColor = 'bg-yellow-50';
                } else if (type === 'qr_code_ready') {
                    icon = '✅';
                    bgColor = 'bg-green-50';
                } else if (type === 'student_scan_qr') {
                    icon = '📱';
                    bgColor = 'bg-purple-50';
                }

                const borderClass = isUnread ? 'border-l-4 border-l-blue-500' : '';

                html += `
                    <div class="notification-item ${bgColor} ${borderClass} px-6 py-4 hover:bg-gray-50 cursor-pointer transition" data-id="${notification.id}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4 flex-1">
                                <span class="text-3xl">${icon}</span>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">${message}</p>
                                    <p class="text-xs text-gray-500 mt-1">${createdAt}</p>
                                    ${isUnread ? '<span class="inline-block mt-2 px-2 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded">Unread</span>' : ''}
                                </div>
                            </div>
                            <div class="flex gap-2">
                                ${isUnread ? `<button class="mark-read-btn px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700" data-id="${notification.id}">Mark Read</button>` : ''}
                                <button class="delete-btn px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700" data-id="${notification.id}">Delete</button>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;

            // Add event listeners
            document.querySelectorAll('.mark-read-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const id = this.getAttribute('data-id');
                    markAsRead(id);
                });
            });

            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const id = this.getAttribute('data-id');
                    if (confirm('Delete this notification?')) {
                        deleteNotification(id);
                    }
                });
            });
        }

        function renderPagination(total, perPage, currentPage) {
            const container = document.getElementById('pagination-container');
            const totalPages = Math.ceil(total / perPage);

            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '<div class="flex justify-between items-center">';
            html += `<p class="text-sm text-gray-600">Showing page ${currentPage} of ${totalPages} (${total} total)</p>`;
            html += '<div class="flex gap-2">';

            for (let i = 1; i <= totalPages; i++) {
                const activeClass = i === currentPage ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300';
                html += `<button class="px-3 py-1 rounded ${activeClass}" onclick="loadNotifications(${i})">${i}</button>`;
            }

            html += '</div></div>';
            container.innerHTML = html;
        }

        function markAsRead(notificationId) {
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
                    loadNotifications(currentPage);
                }
            });
        }

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
                    loadNotifications(currentPage);
                }
            });
        }
    </script>

    <style>
        .filter-btn.active {
            @apply bg-blue-100 text-blue-700;
        }
    </style>
</x-app-layout>
