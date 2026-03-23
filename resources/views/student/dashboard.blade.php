@extends('layouts.student-app')

@section('title', 'My Schedule - Lectures')

@section('content')
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Lectures</h2>
</x-slot>

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8"></div>
    <!-- Page Header -->

    <div class="section-header">
            <h1 class="section-title">My Schedule</h1>
            <p class="section-subtitle">View your lectures, halls, and timings</p>
            <h1 style="color: #8A2BE2;">Select the date & year & Semester to view your lectures  :</h1>
        </div>

    <!-- Sub Navigation (like hall-management) -->
    

    <!-- Filters Card -->
   
        <form method="GET" action="{{ route('student.dashboard') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="date" class="block text-sm font-medium  mb-1">Date</label>
                <input type="date" id="date" name="date" value="{{ $date ?? '' }}"  class="px-3 py-2 border border-gray-600 bg-gray-700  rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 hall-card">
            </div>
            <div>
                <label for="year" class="block text-sm font-medium  mb-1">Year</label>
                <select id="year" name="year" class="w-full px-2 py-2 border  rounded-lg  hall-card appearance-auto " >
                    @foreach(['First', 'Second', 'Third', 'Fourth', 'Fifth'] as $y)
                        <option value="{{ $y }}" {{ $displayYear === $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div >
                <label for="semester" class="block text-sm font-medium  mb-1 ">Semester</label>
                <select id="semester" name="semester" class="w-full px-2 py-2 border  rounded-lg  hall-card appearance-auto">
                    @foreach(['First', 'Second'] as $s)
                        <option value="{{ $s }}" {{ $displaySemester === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
           <div class="flex gap-2">
                            <button type="submit" class="bg-gradient-to-r from-purple-700 to-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:opacity-90 transition-opacity flex items-center" style="transition-duration: 0.2s;">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" class="mr-2"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V8h14v12zM7 10h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2z"/></svg>Search
                            </button>
                
                @if(isset($date))
                                <a href="{{ route('student.dashboard') }}" class="bg-gray-400 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-500 transition-colors ">
                                    Reset
                                </a>
                            @endif
            </div>
        </form>
        <br>
    
    @if($lectures->isEmpty())
        <div class="text-center py-20 hall-card rounded-xl">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h3 class="text-xl font-bold text-gray-800 mb-3">No lectures today! Enjoy your free time</h3>
    <p class="text-gray-600 mb-8 leading-relaxed">
        It's a great chance to explore the <span class="text-purple-600 font-semibold text-lg">Library</span> and get ahead with your studies before the exam rush.
    </p>

    <a href="{{ route('student.subjects') }}" 
       class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-purple-400 hover:-translate-y-1 transition-all duration-300">
        Go to Library
        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
        </svg>
    </a>
        </div>
    @else
        <!-- Lectures Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($lectures as $lecture)
                @php
                    $now = now();
                    $start = \Carbon\Carbon::parse($lecture->start_time);
                    $end = \Carbon\Carbon::parse($lecture->end_time);
                    if ($now->lt($start)) {
                        $status = 'upcoming';
                        $color = 'blue';
                    } elseif ($now->between($start, $end)) {
                        $status = 'ongoing';
                        $color = 'green';
                    } else {
                        $status = 'past';
                        $color = 'gray';
                    }
                @endphp
                <a href="{{ route('student.scan-qr', $lecture->id) }}" class="hall-card group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-purple-600 transition">{{ $lecture->subject ?? $lecture->title ?? 'Untitled' }}</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 border border-{{ $color }}-200">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center text-sm text-gray-600 group-hover:text-gray-800">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $start->format('D, M j, Y') }} {{ $start->format('g:i A') }} - {{ $end->format('g:i A') }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600 group-hover:text-gray-800">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $lecture->hall->hall_name ?? 'TBA' }} ({{ $lecture->hall->building ?? 'N/A' }}, Floor {{ $lecture->hall->floor ?? 'N/A' }})
                        </div>
                        <div class="flex items-center text-sm text-gray-600 group-hover:text-gray-800">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Prof. {{ $lecture->professor ?? 'TBA' }}
                        </div>
                    </div>
                    <div class="pt-4 border-t border-gray-100">
                        <span class="text-sm font-medium text-purple-600 hover:text-purple-700 transition">Scan QR to Attend →</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Optional: Add hover animations or status updates
    document.querySelectorAll('.hall-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-4px)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
</script>
@endpush
@endsection
