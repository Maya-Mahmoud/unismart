<x-admin-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
       <div class="flex flex-col">
    <a href="{{ route('admin.lectures') }}" class="text-base text-purple-600 text-lg hover:text-purple-700 mb-2">
        ← Back to Lectures
    </a>
    <br>

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900"> {{ $lecture->title }}</h1>
            
          
        </div>

        <button id="exportCsvBtn" class="flex items-center bg-green-600 text-white px-5 py-3 rounded-lg text-sm font-medium hover:bg-green-700">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export CSV
        </button>
    </div>
</div>
<br>

        <!-- Lecture Details -->
       <div class="bg-white p-8 border border-green-100 rounded-xl shadow-md hall-card">
    <div class="flex flex-wrap justify-between items-start gap-y-4">

        <div class="flex items-start min-w-1/4">
            <div class="mr-3 text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-base font-medium text-black-700">
                    {{ \Carbon\Carbon::parse($lecture->start_time)->locale('en')->isoFormat('dddd, MMMM D') }}
                </p>
                <p class="text-sm text-black-500">
                    {{ \Carbon\Carbon::parse($lecture->start_time)->format('Y') }}
                </p>
            </div>
        </div>

        <div class="flex items-start min-w-1/4">
            <div class="mr-3 text-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
              <p class="text-sm font-semibold text-gray-800">
            {{ \Carbon\Carbon::parse($lecture->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($lecture->end_time)->format('h:i A') }}
        </p>

        <p class="text-base text-black-500">
            @php
                $startTime = \Carbon\Carbon::parse($lecture->start_time);
                $endTime = \Carbon\Carbon::parse($lecture->end_time);
                $duration = $startTime->diffInMinutes($endTime);
            @endphp
            {{ $duration }} minutes
        </p>
            </div>
        </div>

        <div class="flex items-start min-w-1/4">
            <div class="mr-3 text-green-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-base font-medium text-black-700">
                    {{ $lecture->hall->hall_name }}
                </p>
                <p class="text-sm text-black-500">
                    {{ $lecture->hall->building }} , floor: {{ $lecture->hall->floor }}
                </p>
            </div>
        </div>

        <div class="flex items-start min-w-1/4">
            <div class="mr-3 text-red-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M12 10a5 5 0 110-10 5 5 0 010 10zm-2 1a2 2 0 00-2 2v2a2 2 0 002 2h4a2 2 0 002-2v-2a2 2 0 00-2-2h-4z"></path>
                </svg>
            </div>
            <div>
                <p class="text-base font-medium text-black-700">
                    Professor: {{ $lecture->user->name }}
                </p>
                <p class="text-sm text-black-500">
                   {{ $lecture->subject }}
                </p>
            </div>
        </div>

        <div class="flex items-start min-w-1/4">
            <div class="mr-3 text-green-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-base font-medium text-black-700">
                    Total Students
                </p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalStudents }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Statistics -->

<div class="bg-white p-6 border border-gray-100 rounded-xl shadow-md mt-6 hall-card ">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Attendance Statistics</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-green-100 p-4 rounded-lg border border-green-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-base font-medium text-green-600">Present</h4>
                    <p class="text-2xl font-bold text-green-900">{{ $presentCount }}</p>
                </div>
                <div class="text-green-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-rose-100 p-4 rounded-lg border border-rose-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-base font-medium text-rose-600">Absent</h4>
                    <p class="text-2xl font-bold text-rose-900">{{ $absentCount }}</p>
                </div>
                <div class="text-red-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-blue-100 p-4 rounded-lg border border-blue-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-base font-medium text-blue-600">Attendance Rate</h4>
                    <p class="text-2xl font-bold text-blue-900">{{ $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100, 1) : 0 }}%</p>
                </div>
                <div class="text-blue-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
        
    </div>
</div>

        <!-- Attendance List -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Student Attendance</h3>
                @if($attendances->isEmpty())
                    <p class="text-gray-600">No attendance records found for this lecture.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($attendances as $attendance)
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-md font-medium text-gray-900">{{ $attendance->student->user->name }}</h4>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600">Scanned at: {{ $attendance->scanned_at ? \Carbon\Carbon::parse($attendance->scanned_at)->locale('ar')->isoFormat('MMMM D, YYYY H:mm') : 'N/A' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const exportCsvBtn = document.getElementById('exportCsvBtn');

            exportCsvBtn.addEventListener('click', function() {
                // Create a link to download the CSV
                const link = document.createElement('a');
                link.href = '{{ route("admin.api.lectures.attendance.export", $lecture->id) }}';
                link.download = 'lecture_attendance_{{ $lecture->id }}.csv';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        });
    </script>
</x-admin-layout>