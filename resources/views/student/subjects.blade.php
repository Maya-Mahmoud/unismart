@extends('layouts.student-app')

@section('content')
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <form method="GET" action="{{ route('student.subjects') }}" class="flex space-x-4 mb-6">
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
                    <a href="{{ route('student.subjects') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Clear
                    </a>
                </div>
            </form>

            @if($subjects->isEmpty())
                <p class="text-gray-600">No subjects found for the selected year and semester.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($subjects as $subject)
                        <li class="py-4 flex justify-between items-center">
                            <span class="text-gray-900 font-medium">{{ $subject->name }}</span>
                            <span class="text-gray-500 text-sm">{{ $subject->department }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
