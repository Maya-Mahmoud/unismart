@extends('layouts.student-app')

@section('title', 'My Subjects & Library')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
   

    @if(isset($files) && isset($subjectModel))
     <div class="flex flex-col ">
            
                 <a href="{{ route('student.subjects') }}" class="text-base text-purple-600 text-lg hover:text-purple-700 mb-2">
                    ← Back to  library
                </a>
                
            
        </div>
    <div class="section-header">
            <h1 class="section-title">{{ $subjectModel->name }}</h1>
            <p class="section-subtitle">Year: {{ $displayYear }} | Semester: {{ $displaySemester }} | {{ $subjectModel->department->name ?? $subjectModel->department }}</p>
            
        </div>
       
     

        @if($files->isEmpty())
            <div class="text-center py-20 hall-card rounded-xl">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No materials yet</h3>
                <p class="text-gray-500 text-lg">No lecture files uploaded for this subject</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($files as $file)
                    @php
                        $isQuiz = ($file->file_type === 'application/json');
                        $quizResult = $isQuiz ? \App\Models\QuizResult::where('user_id', auth()->id())->where('lecture_file_id', $file->id)->first() : null;
                    @endphp
                    <div class="hall-card group hover:shadow-xl transition-all duration-300 h-full">
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 truncate">{{ $file->file_name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $isQuiz ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} border">
                                {{ $isQuiz ? 'Quiz' : 'PDF' }}
                            </span>
                        </div>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                {{ $file->lecture->title ?? 'Lecture Materials' }}
                            </div>
                        </div>
                        <div class="flex space-x-3 pt-2">
                            @if($isQuiz)
                                @if($quizResult)
                                    <div class="flex-1 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-lg p-3 text-center font-bold text-sm">
                                        <div class="text-2xl">{{ $quizResult->score }}%</div>
                                        <div class="text-xs opacity-75">{{ $quizResult->correct_answers }}/{{ $quizResult->total_questions }}</div>
                                    </div>
                                @else
                                    <a href="{{ route('student.quiz.play', $file->id) }}" class="flex-1 bg-purple-600 text-white rounded-lg px-4 py-3 text-sm font-bold hover:bg-purple-700 shadow-md transition flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path>
                                        </svg>
                                        Start Quiz
                                    </a>
                                @endif
                            @else
                                <a href="/student/lecture-files/{{ $file->id }}/view" target="_blank" class="flex-1 bg-white border border-gray-300 rounded-lg px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition text-center">Preview</a>
                                <a href="/student/lecture-files/{{ $file->id }}/download" class="flex-1 bg-green-500 text-white rounded-lg px-4 py-3 text-sm font-bold hover:bg-green-600 shadow-sm transition text-center">Download</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    @elseif(isset($subjects))
        <div class="section-header">
            <h1 class="section-title">Courses</h1>
            <p class="section-subtitle">Explore your subjects and access recorded lectures and materials (Year: {{ $displayYear }} - Semester: {{ $displaySemester }})</p>
        </div>

        <form method="GET" action="{{ route('student.subjects') }}" class="flex flex-wrap items-end gap-4 mb-6">
            <div>
                <label for="year" class="block text-sm font-medium mb-1">Year</label>
                <select id="year" name="year" class="w-full px-2 py-2 border rounded-lg hall-card appearance-auto">
                    @foreach(['First', 'Second', 'Third', 'Fourth', 'Fifth'] as $y)
                        <option value="{{ $y }}" {{ $displayYear === $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="semester" class="block text-sm font-medium mb-1">Semester</label>
                <select id="semester" name="semester" class="w-full px-2 py-2 border rounded-lg hall-card appearance-auto">
                    @foreach(['First', 'Second'] as $s)
                        <option value="{{ $s }}" {{ $displaySemester === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-gradient-to-r from-purple-700 to-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:opacity-90 transition-opacity flex items-center" style="transition-duration: 0.2s;">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" class="mr-2"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V8h14v12zM7 10h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2z"/></svg>Search
                </button>
            </div>
        </form>

        @if($subjects->isEmpty())
            <div class="text-center py-20 hall-card rounded-xl">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No subjects found</h3>
                <p class="text-gray-500 text-lg">Adjust filters to see available subjects</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-5 lg:grid-cols-3 gap-6">
                @foreach($subjects as $subject)
                    @php
                        $hasFiles = \App\Models\LectureFile::whereHas('lecture.subject', fn($q) => $q->where('id', $subject->id))->exists();
                        $status = $hasFiles ? 'available' : 'empty';
                        $color = $hasFiles ? 'green' : 'gray';
                    @endphp
                    <a href="{{ route('student.subjects.files', $subject->id) }}" class="hall-card group hover:shadow-xl transition-all duration-300 h-full">
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-purple-600">{{ $subject->name }}</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 border border-{{ $color }}-200">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm text-gray-600 group-hover:text-gray-800">
                                👤 Prof. {{ $subject->lectures->first()->professor ?? 'Instructor: TBD' }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600 group-hover:text-gray-800">
                                📚 {{ $subject->all_files_count > 0 ? $subject->all_files_count . ' lectures' : 'No files available' }}
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <span class="text-sm font-medium text-purple-600 hover:text-purple-700 transition flex items-center">
                               Access Lectures  
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    @endif
</div>

@push('scripts')
<script>
    document.querySelectorAll('.hall-card').forEach(card => {
        card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-4px)');
        card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
    });
</script>
@endpush
@endsection