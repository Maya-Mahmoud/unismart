<x-student-app title="Notifications">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Notifications</h1>

                    <div class="space-y-4">
                        @forelse($notifications as $notification)
                            <div class="border border-gray-200 rounded-lg p-4 {{ !$notification->read_at ? 'bg-blue-50 border-blue-200' : 'bg-white' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-gray-900">{{ $notification->data['message'] }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(!$notification->read_at)
                                        <form method="POST" action="{{ route('notifications.mark-as-read', $notification->id) }}" class="ml-4">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Mark as Read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                                <p class="mt-1 text-sm text-gray-500">You don't have any notifications yet.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($notifications->hasPages())
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-student-app>
