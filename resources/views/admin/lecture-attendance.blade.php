<x-admin-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

        <div class="bg-white p-6 border border-gray-100 rounded-xl shadow-md mt-6 hall-card">
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
        
        <div class="bg-white shadow-sm rounded-lg mt-6">
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

    <div id="chatOverlay" class="fixed inset-0 bg-black/10 backdrop-blur-sm z-40 hidden transition-opacity duration-300 opacity-0"></div>

    <button id="openChatBtn" class="fixed bottom-8 right-8 w-16 h-16 bg-purple-600 text-white rounded-full shadow-2xl hover:bg-purple-700 hover:scale-110 transition-all duration-300 z-30 flex items-center justify-center border-4 border-white">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
    </button>

    <div id="chatSidebar" class="fixed top-4 bottom-4 -right-[600px] w-[500px] bg-white rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] z-50 transition-all duration-500 ease-[cubic-bezier(0.18,0.89,0.32,1.28)] flex flex-col overflow-hidden border border-gray-100">
        <div class="p-6 bg-gradient-to-r from-purple-600 to-indigo-600 text-white flex justify-between items-center shadow-md">
            <div class="flex items-center space-x-4">
                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg leading-tight">Gemini Assistant</h3>
                    <p class="text-xs text-purple-200 uppercase font-black tracking-widest">Lecture Analysis</p>
                </div>
            </div>
            <button id="closeChatBtn" class="p-2 hover:bg-white/20 rounded-full transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>

        <div id="chatMessages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50/50">
            <div class="flex items-start">
                <div class="bg-white border border-gray-100 p-4 rounded-2xl rounded-tl-none shadow-sm text-sm text-gray-700 max-w-[85%] leading-relaxed">
                    مرحباً دكتور! 👋 أنا جاهز لمساعدتك في تحليل بيانات محاضرة <b>{{ $lecture->title }}</b>. هل تريد تلخيصاً للحضور أم إنشاء اختبار؟
                </div>
            </div>
        </div>

        <div class="p-6 bg-white border-t border-gray-100">
            <div class="flex gap-2 mb-4 overflow-x-auto no-scrollbar py-1">
                <button class="whitespace-nowrap px-4 py-2 bg-purple-50 text-purple-700 text-[11px] font-bold rounded-xl border border-purple-100 hover:bg-purple-100 transition">📝 تلخيص الحضور</button>
                <button class="whitespace-nowrap px-4 py-2 bg-green-50 text-green-700 text-[11px] font-bold rounded-xl border border-green-100 hover:bg-green-100 transition">💡 توليد أسئلة</button>
            </div>
            <div class="relative group">
                <input type="text" id="userInput" placeholder="Ask Gemini something..." class="w-full bg-gray-100 border-none rounded-2xl py-4 pl-5 pr-14 focus:ring-2 focus:ring-purple-500 focus:bg-white transition-all text-sm shadow-inner">
                <button id="sendMessageBtn" class="absolute right-2 top-2 bottom-2 px-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition shadow-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Original CSV Export Logic
            const exportCsvBtn = document.getElementById('exportCsvBtn');
            exportCsvBtn.addEventListener('click', function() {
                const link = document.createElement('a');
                link.href = '{{ route("admin.api.lectures.attendance.export", $lecture->id) }}';
                link.download = 'lecture_attendance_{{ $lecture->id }}.csv';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });

            // 2. Modified Chat Logic
            const openChatBtn = document.getElementById('openChatBtn');
            const closeChatBtn = document.getElementById('closeChatBtn');
            const chatSidebar = document.getElementById('chatSidebar');
            const chatOverlay = document.getElementById('chatOverlay');
            const sendMessageBtn = document.getElementById('sendMessageBtn');
            const userInput = document.getElementById('userInput');
            const chatMessages = document.getElementById('chatMessages');

            function toggleChat(isOpen) {
                if (isOpen) {
                    chatSidebar.classList.remove('-right-[600px]');
                    chatSidebar.classList.add('right-4');
                    chatOverlay.classList.remove('hidden');
                    setTimeout(() => chatOverlay.classList.add('opacity-100'), 10);
                    openChatBtn.classList.add('scale-0', 'opacity-0');
                } else {
                    chatSidebar.classList.add('-right-[600px]');
                    chatSidebar.classList.remove('right-4');
                    chatOverlay.classList.remove('opacity-100');
                    setTimeout(() => chatOverlay.classList.add('hidden'), 300);
                    openChatBtn.classList.remove('scale-0', 'opacity-0');
                }
            }

            openChatBtn.addEventListener('click', () => toggleChat(true));
            closeChatBtn.addEventListener('click', () => toggleChat(false));
            chatOverlay.addEventListener('click', () => toggleChat(false));

            sendMessageBtn.addEventListener('click', function() {
                const msg = userInput.value.trim();
                if (msg) {
                    const userMsg = `<div class="flex justify-end"><div class="bg-purple-600 text-white p-4 rounded-2xl rounded-tr-none shadow-md text-sm max-w-[85%]">${msg}</div></div>`;
                    chatMessages.insertAdjacentHTML('beforeend', userMsg);
                    userInput.value = '';
                    chatMessages.scrollTo({ top: chatMessages.scrollHeight, behavior: 'smooth' });
                }
            });
        });
    </script>
</x-admin-layout>