@extends('layouts.student-app')

@section('title', 'My Attendance')

@section('content')
<div class="max-w-4xl mx-auto mt-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg leading-6 font-medium text-gray-900">
                Attendance Overview - {{ $displayYear }} Year, {{ $displaySemester }} Semester
            </h2>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Your attendance records for lectures with QR codes.
            </p>
        </div>
        <div class="border-t border-gray-200">
            <div class="px-4 py-5 sm:p-6">
                @if(empty($attendanceData))
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No attendance records</h3>
                        <p class="mt-1 text-sm text-gray-500">No QR codes have been generated for your lectures yet.</p>
                    </div>
                @else
                    <div class="grid gap-6">
                        @foreach($attendanceData as $subject => $counts)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $subject }}</h3>
                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-8">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-green-600">{{ $counts['present'] }}</div>
                                            <div class="text-sm text-gray-500">Present</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-red-600">{{ $counts['absent'] }}</div>
                                            <div class="text-sm text-gray-500">Absent</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500">
                                            Total: {{ $counts['present'] + $counts['absent'] }} lectures
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ number_format(($counts['present'] / ($counts['present'] + $counts['absent']) * 100), 1) }}% attendance
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
