@extends('layouts.student-app')

@section('title', 'My Attendance')

@section('content')
<div class="py-12">
    
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="section-header">
            <h1 class="section-title">My Attendance</h1>
            <p class="section-subtitle">Track your presence records ({{ $displayYear ?? 'All' }} - {{ $displaySemester ?? 'All' }})</p>
        </div>

        @if(empty($attendanceData))
            <div class="text-center py-24 hall-card rounded-3xl max-w-2xl mx-auto">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">No Records Yet</h2>
                <p class="text-xl text-gray-500 mb-8 max-w-md mx-auto">Start attending lectures by scanning QR codes to see your records here</p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('student.scan-qr') }}" class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-bold rounded-2xl shadow-xl bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-4 focus:ring-green-500/50 transition-all duration-300">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Scan First QR
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach($attendanceData as $subject => $counts)
                    @php
                        $totalLectures = $counts['present'] + $counts['absent'];
                        $percentage = $totalLectures > 0 ? ($counts['present'] / $totalLectures * 100) : 0;
                        $status = $percentage >= 80 ? 'excellent' : ($percentage >= 60 ? 'good' : 'needs_improvement');
                        $statusColor = $percentage >= 80 ? 'green' : ($percentage >= 60 ? 'yellow' : 'red');
                    @endphp
                    {{-- التعديل الوحيد هنا: إضافة border-gray-200 --}}
                    <div class="hall-card group hover:shadow-2xl transition-all duration-300 h-full border border-green-800">
                        <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-100">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 group-hover:text-purple-600">{{ $subject }}</h3>
                                <p class="text-sm text-gray-800">Year {{ $displayYear }} - Semester {{ $displaySemester }}</p>
                            </div>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-{{ $statusColor }}-500 to-{{ $statusColor }}-600 text-white shadow-lg">
                                {{ number_format($percentage, 1) }}%
                            </span>
                        </div>
                        
                        <div class="space-y-6 mb-8">
                            <div class="flex items-center justify-center bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6  border border-green-600">
                                <div class="flex-1 grid grid-cols-2 gap-8">
                                    <div class="text-center">
                                        <div class="text-4xl font-black text-green-600 mb-1">{{ $counts['present'] }}</div>
                                        <div class="text-sm font-semibold text-gray-700 uppercase tracking-wide ">Present</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-4xl font-black text-red-600 mb-1">{{ $counts['absent'] }}</div>
                                        <div class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Absent</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="relative pt-1">
                                <div class="flex mb-2 items-center justify-between">
                                    <div>
                                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full bg-gray-100 text-gray-800">
                                            {{ $totalLectures }} Total Lectures
                                        </span>
                                    </div>
                                </div>
                                <div class="overflow-hidden h-4 mb-4 text-xs flex rounded-full bg-gray-200">
                                    <div style="width: {{ $percentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap justify-center bg-gradient-to-r from-green-500 to-emerald-500 text-white font-bold rounded-full transition-all duration-1000 animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3 pt-6 border-t border-gray-100">
                            <span class="flex-1 text-center py-3 px-4 bg-green-100 border border-green-200 rounded-xl text-sm font-bold text-green-800 group-hover:bg-green-200 transition">
                                {{ $counts['present'] }} Sessions ✓
                            </span>
                            <span class="flex-1 text-center py-3 px-4 bg-red-100 border border-red-200 rounded-xl text-sm font-bold text-red-800 group-hover:bg-red-200 transition">
                                {{ $counts['absent'] }} Missed
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Smooth animations
    document.querySelectorAll('.hall-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-8px)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
</script>
@endpush
@endsection