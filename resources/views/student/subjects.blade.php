@extends('layouts.student-app')

@section('title', 'My Library')

@section('content')
<div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm rounded-lg p-6">
        
        {{-- 1. Search Form --}}
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
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Search
                </button>
                <a href="{{ route('student.subjects') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Clear
                </a>
            </div>
        </form>

        <hr class="my-6 border-gray-200">

        {{-- 2. Logic to Show Files or Subjects --}}
        @if(isset($files) && isset($subjectModel))
            <div class="mb-6 bg-indigo-600 p-6 rounded-lg text-white">
                <h1 class="text-2xl font-bold mb-2">{{ $subjectModel->name }}</h1>
                <p class="text-lg opacity-90">Year: {{ $displayYear }} | Semester: {{ $displaySemester }} | Department: {{ $subjectModel->department }}</p>
            </div>

            @if($files->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No lectures available</h3>
                    <p class="text-gray-500">No files uploaded for this subject yet</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($files as $file)
                        @php
                            $isQuiz = ($file->file_type === 'application/json');
                            
                            // البحث عن نتيجة الطالب لهذا الملف تحديداً
                            $quizResult = null;
                            if($isQuiz) {
                                $quizResult = \App\Models\QuizResult::where('user_id', auth()->id())
                                                ->where('lecture_file_id', $file->id)
                                                ->first();
                            }
                        @endphp
                        <div class="bg-gradient-to-br {{ $isQuiz ? 'from-purple-50 to-indigo-50 border-purple-200' : 'from-blue-50 to-indigo-50 border-blue-200' }} border rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0">
                                    @if($isQuiz)
                                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 shadow-sm">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="font-semibold text-gray-900 text-lg truncate">{{ $file->file_name }}</h3>
                                    <p class="text-sm text-gray-600">Lecture: {{ $file->lecture->title ?? 'Not specified' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                @if($isQuiz)
                                    @if($quizResult)
                                        {{-- عرض علامة الطالب بدلاً من الزر --}}
                                        <div class="flex-1 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-lg px-4 py-2 flex flex-col items-center justify-center">
                                            <span class="text-[10px] uppercase font-bold tracking-wider opacity-70">Your Result</span>
                                            <div class="text-xl font-black">{{ $quizResult->score }}%</div>
                                            <span class="text-[10px] font-medium">{{ $quizResult->correct_answers }}/{{ $quizResult->total_questions }} Correct</span>
                                        </div>
                                    @else
                                        {{-- زر تشغيل الاختبار التفاعلي فقط إذا لم يكن قد حله سابقاً --}}
                                        <a href="{{ route('student.quiz.play', $file->id) }}" 
                                           class="flex-1 bg-purple-600 text-white rounded-lg px-4 py-3 text-sm text-center font-bold hover:bg-purple-700 shadow-md transition-all flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path></svg>
                                            Start Quiz
                                        </a>
                                    @endif
                                @else
                                    <a href="/student/lecture-files/{{ $file->id }}/view" target="_blank" class="flex-1 bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm text-center font-medium text-gray-700 hover:bg-gray-50 shadow-sm">Preview</a>
                                    <a href="/student/lecture-files/{{ $file->id }}/download" class="flex-1 bg-green-500 text-white rounded-lg px-4 py-2 text-sm text-center font-medium hover:bg-green-600 shadow-sm">Download</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        @elseif(isset($subjects))
            {{-- قائمة المواد الافتراضية --}}
            <h2 class="text-2xl font-bold mb-6 text-gray-900">My Library</h2>
            <p class="text-gray-600 mb-8">Click a subject to view available lectures (Year: {{ $displayYear }} - Semester: {{ $displaySemester }})</p>

            @if($subjects->isEmpty())
                <div class="text-center py-12">
                    <h3 class="text-lg font-medium text-gray-900">No subjects found</h3>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($subjects as $subject)
                        @php
                            $hasFiles = \App\Models\LectureFile::whereHas('lecture.subject', fn($q) => $q->where('id', $subject->id))->exists();
                        @endphp
                        <div class="group bg-white border-2 {{ $hasFiles ? 'border-green-300' : 'border-gray-200' }} rounded-xl p-8 shadow-sm hover:shadow-md cursor-pointer transition-all" onclick="window.location.href='{{ route('student.subjects.files', $subject->id) }}'">
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $subject->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $subject->department }}</p>
                            <span class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $hasFiles ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                {{ $hasFiles ? 'Lectures Available' : 'No lectures yet' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

    </div>
</div>
@endsection