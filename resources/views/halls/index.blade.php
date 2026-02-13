<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
              
            
 <div class="section-header">
            <h1 class="section-title">Halls Booking</h1>
            <p class="section-subtitle">Book or release halls for your classes</p>
                <h1 style="color: #8A2BE2;">Select the start and end time to view all halls available at this time:</h1>
        </div>
                <!-- DateTime Filter Form -->
               
                    <form method="GET" action="{{ route('halls.index') }}" class="flex flex-wrap items-end gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium  mb-1">Select Start Time</label>
                            <input type="datetime-local" name="start_time" id="start_time" class="px-3 py-2 border border-gray-600 bg-gray-700  rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 hall-card" value="{{ isset($startTime) ? $startTime : '' }}">
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium  mb-1">Select End Time</label>
                            <input type="datetime-local" name="end_time" id="end_time" class="px-3 py-2 border border-gray-600 bg-gray-700  rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 hall-card" value="{{ isset($endTime) ? $endTime : '' }}">
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="bg-gradient-to-r from-purple-700 to-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:opacity-90 transition-opacity flex items-center" style="transition-duration: 0.2s;">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" class="mr-2"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V8h14v12zM7 10h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2z"/></svg>Search
                            </button>
                            @if(isset($startTime) || isset($endTime))
                                <a href="{{ route('halls.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition-colors ">
                                    View all
                                </a>
                            @endif
                        </div>
                    </form>

                    @if(isset($startTime) && isset($endTime))
                        <p class="mt-2 text-sm text-gray-600">
                            View available halls {{ \Carbon\Carbon::parse($startTime)->format('d/m/Y H:i') }} to {{ \Carbon\Carbon::parse($endTime)->format('d/m/Y H:i') }}
                        </p>
                    @endif
            
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif
<div class="section-subtitle">College Halls :</div>
<br>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                @foreach($halls as $hall)
                    @php
                        $currentLecture = $hall->lectures()->where('start_time', '<=', now())->where('end_time', '>', now())->first();
                        $currentBooking = $hall->currentBooking;
                    @endphp
                    
                    <div class="hall-card">
                      
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">{{ $hall->hall_name }}</h3>
                                            <br>
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Capacity: <span class="font-medium ml-1">{{ $hall->capacity }}</span>
                                </div>

                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <span class=" font-medium px-2 py-1 rounded-full text-xs {{ $hall->isOccupiedAt(now()) ? 'bg-red-100 text-rose-600' : 'bg-green-100 text-green-800' }}">
                                        {{ $hall->isOccupiedAt(now()) ? 'Occupied' : 'Available' }}
                                    </span>
                                </div>

                                @if($hall->isOccupiedAt(now()))
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($currentLecture)
    @php
        $start = \Carbon\Carbon::parse($currentLecture->start_time)->setTimezone('Asia/Damascus')->format('h:i A');
        $end = \Carbon\Carbon::parse($currentLecture->end_time)->setTimezone('Asia/Damascus')->format('h:i A');
    @endphp
    Lecture: {{ $currentLecture->title }} ({{ $start }} - {{ $end }})
@elseif($currentBooking)
    Booked by: {{ $currentBooking->user->name }}
@endif

                                    </div>
                                @endif

                                @if($hall->equipment)
                                    <div class="text-xs">
                                        Equipment: {{ $hall->equipment }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-between items-center">
                                <div class="text-sm ">
                                    {{ $hall->building }}, Floor {{ $hall->floor }}
                                </div>

                                <button data-hall-id="{{ $hall->id }}" data-hall-name="{{ $hall->hall_name }}" class="booking-details-btn bg-gradient-to-r from-purple-700 to-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:opacity-90 transition-opacity flex items-center border border-purple-300">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" class="mr-2">
                                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V8h14v12zM7 10h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2z"/>
                                    </svg>
                                    Booking Details
                                </button>

                                @if($hall->currentBooking && $hall->currentBooking->user_id === auth()->id())
                                    <form action="{{ route('halls.release', $hall) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition-colors">
                                            Release Hall
                                        </button>
                                    </form>
                                @endif
                            </div>
                       
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div id="bookingDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="bookingModalTitle">Booking Details</h3>
                    <button id="closeBookingModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex justify-center space-x-4 mb-4">
                    <button id="upcomingBtn" class="bg-gradient-to-r from-gray-200 to-slate-300 text-gray-900 px-5 py-2 rounded-lg text-sm font-medium hover:scale-105 hover:shadow-lg transition-all focus:ring-2 focus:ring-slate-500">
                        Upcoming
                    </button>
                    <button id="completedBtn" class="bg-gradient-to-r from-teal-500 to-emerald-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:scale-105 hover:shadow-lg transition-all focus:ring-2 focus:ring-teal-500">
                        Completed
                    </button>
                </div>

                <div id="bookingDetailsContent">
                    <div id="lecturesContainer"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentLectures = [];

        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.booking-details-btn');
            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const hallId = this.dataset.hallId;
                    const hallName = this.dataset.hallName;
                    showBookingDetails(hallId, hallName);
                });
            });

            // Add event listeners for filter buttons with null checks
            const upcomingBtn = document.getElementById('upcomingBtn');
            const completedBtn = document.getElementById('completedBtn');
            if (upcomingBtn) {
                upcomingBtn.addEventListener('click', () => displayLectures('upcoming'));
            }
            if (completedBtn) {
                completedBtn.addEventListener('click', () => displayLectures('completed'));
            }

            // Add event listener for close button with null check
            const closeBtn = document.getElementById('closeBookingModal');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    const modal = document.getElementById('bookingDetailsModal');
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                });
            }

            // Add event listener for modal background click with null check
            const modal = document.getElementById('bookingDetailsModal');
            if (modal) {
                modal.addEventListener('click', e => {
                    if (e.target === e.currentTarget) {
                        e.currentTarget.classList.add('hidden');
                    }
                });
            }
        });

        function showBookingDetails(hallId, hallName) {
            const modalTitle = document.getElementById('bookingModalTitle');
            const modal = document.getElementById('bookingDetailsModal');
            const lecturesContainer = document.getElementById('lecturesContainer');
            const filterButtons = document.querySelector('.flex.justify-center.space-x-4.mb-4');

            if (!modalTitle || !modal || !lecturesContainer) {
                console.error('Modal elements not found');
                return;
            }

            modalTitle.textContent = `Booking Details - ${hallName}`;
            lecturesContainer.innerHTML = '<p class="text-center text-gray-500">Loading...</p>';
            modal.classList.remove('hidden');

            fetch(`/admin/api/halls/${hallId}/lectures`)
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        currentLectures = data.data;
                        const currentLecture = currentLectures.find(l => l.status === 'ongoing');
                        if (currentLecture) {
                            // Hide filter buttons and show current lecture message
                            if (filterButtons) filterButtons.style.display = 'none';
                            lecturesContainer.innerHTML = `
                                <div class="text-center">
                                    <p class="text-lg font-semibold text-red-600 mb-4">There is a lecture in this hall now.</p>
                                    <div class="bg-white border border-purple-300 rounded-lg p-4 shadow-sm">
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Lecture title:</label>
                                            <p class="text-sm font-semibold text-gray-900">${currentLecture.title || 'N/A'}</p>
                                        </div>
                                        <div class="grid grid-cols-1 gap-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">subject:</span>
                                                <span class="font-medium text-gray-900">${currentLecture.subject || 'N/A'}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Professor:</span>
                                                <span class="font-medium text-gray-900">${currentLecture.professor || 'N/A'}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Start time</span>
                                                <span class="font-medium text-gray-900">
                                                    ${currentLecture.start_time
                                                        ? new Date(currentLecture.start_time).toLocaleString('en-US', {
                                                            timeZone: 'Asia/Damascus',
                                                            hour: 'numeric',
                                                            minute: '2-digit',
                                                            hour12: true
                                                        })
                                                        : 'N/A'}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">end time:</span>
                                                <span class="font-medium text-gray-900">
                                                    ${currentLecture.end_time
                                                        ? new Date(currentLecture.end_time).toLocaleString('en-US', {
                                                            timeZone: 'Asia/Damascus',
                                                            hour: 'numeric',
                                                            minute: '2-digit',
                                                            hour12: true
                                                        })
                                                        : 'N/A'}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            // Show filter buttons and lectures
                            if (filterButtons) filterButtons.style.display = 'flex';
                            displayLectures('upcoming');
                        }
                    } else {
                        currentLectures = [];
                        if (filterButtons) filterButtons.style.display = 'flex';
                        displayLectures('upcoming');
                    }
                })
                .catch(err => {
                    console.error(err);
                    lecturesContainer.innerHTML = `<p class="text-center text-red-500">Error: ${err.message}</p>`;
                });
        }

        function displayLectures(filterType) {
            const container = document.getElementById('lecturesContainer');
            if (!container) {
                console.error('Lectures container not found');
                return;
            }

            const filtered = currentLectures.filter(l => l.status === filterType);

            if (filtered.length === 0) {
                container.innerHTML = `<p class="text-center text-gray-500">No ${filterType} lectures found</p>`;
                return;
            }

            let html = '<div class="space-y-4">';
            filtered.forEach(lecture => {
                html += `
                    <div class="bg-white border border-purple-300 rounded-lg p-4 shadow-sm">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lecture Title:</label>
                            <p class="text-sm font-semibold text-gray-900">${lecture.title || 'N/A'}</p>
                        </div>
                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subject:</span>
                                <span class="font-medium text-gray-900">${lecture.subject || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Professor:</span>
                                <span class="font-medium text-gray-900">${lecture.professor || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Start Time:</span>
                                <span class="font-medium text-gray-900">
                                    ${lecture.start_time
                                        ? new Date(lecture.start_time).toLocaleString('en-US', {
                                            timeZone: 'Asia/Damascus',
                                            hour: 'numeric',
                                            minute: '2-digit',
                                            hour12: true
                                        })
                                        : 'N/A'}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">End Time:</span>
                                <span class="font-medium text-gray-900">
                                    ${lecture.end_time
                                        ? new Date(lecture.end_time).toLocaleString('en-US', {
                                            timeZone: 'Asia/Damascus',
                                            hour: 'numeric',
                                            minute: '2-digit',
                                            hour12: true
                                        })
                                        : 'N/A'}
                                </span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                                <span class="text-gray-600">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                    lecture.status === 'ongoing' ? 'bg-green-100 text-green-800' :
                                    lecture.status === 'upcoming' ? 'bg-blue-100 text-blue-800' :
                                    'bg-gray-100 text-gray-800'
                                }">
                                    ${lecture.status ? lecture.status.charAt(0).toUpperCase() + lecture.status.slice(1) : 'Unknown'}
                                </span>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            container.innerHTML = html;
        }

        document.getElementById('closeBookingModal').addEventListener('click', () => {
            document.getElementById('bookingDetailsModal').classList.add('hidden');
        });

        document.getElementById('bookingDetailsModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) e.currentTarget.classList.add('hidden');
        });
    </script>
</x-admin-layout>