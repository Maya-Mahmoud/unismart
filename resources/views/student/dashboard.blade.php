@extends('layouts.student-app')

@section('title', 'My Schedule')

@section('content')
    <h1 class="text-2xl font-bold mb-4">My Schedule</h1>
    <p class="text-gray-600 mb-6">View your upcoming lectures and hall locations</p>

    <form method="GET" action="{{ route('student.dashboard') }}" class="flex space-x-4 mb-6">
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
            <input type="date" id="date" name="date" value="{{ $date ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
        <div>
            <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
            <select id="year" name="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @foreach(['First', 'Second', 'Third', 'Fourth', 'Fifth'] as $y)
                    <option value="{{ $y }}" {{ $displayYear === $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
            <select id="semester" name="semester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @foreach(['First', 'Second'] as $s)
                    <option value="{{ $s }}" {{ $displaySemester === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end space-x-2">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Search
            </button>
            <a href="{{ route('student.dashboard') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Clear
            </a>
        </div>
    </form>

    @if($lectures->isEmpty())
        <div class="text-center text-gray-500 mt-20">
            <svg class="mx-auto mb-4 h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z" />
            </svg>
            <p class="text-lg font-semibold">No lectures found</p>
            <p>Try adjusting your search criteria</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($lectures as $lecture)
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-semibold text-purple-700">{{ $lecture->subject }}</h2>
                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($lecture->start_time)->format('D, M j, Y g:i A') }} - {{ \Carbon\Carbon::parse($lecture->end_time)->format('g:i A') }}</p>
                    <p class="text-gray-600">Hall: {{ $lecture->hall ? $lecture->hall->hall_name : 'N/A' }}</p>
                    <p class="text-gray-600">Location: {{ $lecture->hall ? ($lecture->hall->building ?? 'N/A') . ' - Floor ' . ($lecture->hall->floor ?? 'N/A') : 'N/A' }}</p>
                    <p class="text-gray-600">Professor: {{ $lecture->professor ?? 'N/A' }}</p>
                </div>
            @endforeach
        </div>
    @endif
@endsection
