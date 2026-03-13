<x-admin-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-12">
        <div class="flex flex-col">
            <a href="{{ route('admin.lectures') }}" class="text-base text-purple-600 text-lg hover:text-purple-700 mb-2">
                ← Back to Lectures
            </a>
            <br>

            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900"> {{ $lecture->title }}</h1>
                </div>

                <button id="exportCsvBtn" class="flex items-center bg-green-600 text-white px-5 py-3 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>
            </div>
        </div>
        <br>

        {{-- Lecture Info Card --}}
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

        {{-- Attendance Statistics Card --}}
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

        {{-- Gemini Assistant Chat Card --}}
        <div class="mt-8 bg-white rounded-3xl shadow-xl overflow-hidden border border-purple-100 flex flex-col md:flex-row h-[550px]">
            <div class="w-full md:w-80 bg-gradient-to-br from-purple-700 via-indigo-800 to-indigo-950 p-8 text-white flex flex-col justify-between relative">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex p-3 bg-white/10 rounded-2xl backdrop-blur-md mb-6 border border-white/20">
                        <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">VELORIA AI</h3>
                    <p class="text-purple-200/80 text-sm leading-relaxed mb-8">
                        Intelligent Academic Assistant
                    </p>
                    
                    <div class="space-y-3">
                        <p class="text-[10px] uppercase tracking-widest font-bold text-purple-300/60">Quick Actions</p>
                        <button onclick="quickAction('Summarize Attendance')" class="w-full flex items-center justify-between px-4 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all group text-sm border border-white/5">
                            <span>📝 Summarize Attendance</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <button onclick="quickAction('Generate Questions')" class="w-full flex items-center justify-between px-4 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all group text-sm border border-white/5">
                            <span>💡 Generate Questions</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </div>
                <p class="relative z-10 text-[10px] text-purple-400/50 mt-4 italic">Powered by Google Gemini</p>
            </div>

            <div class="flex-1 flex flex-col bg-gray-50/50">
                <div class="px-6 py-3 bg-white/80 backdrop-blur-sm border-b border-gray-100 flex items-center justify-between">
                    <span class="flex items-center text-xs font-semibold text-green-500 uppercase tracking-tighter">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span> System Online
                    </span>
                </div>

                <div id="chatMessages" class="flex-1 overflow-y-auto p-6 space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center mr-3 text-purple-600 border border-purple-200 shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div class="bg-white border border-gray-100 p-4 rounded-2xl rounded-tl-none shadow-sm text-m text-black-700 max-w-[85%] leading-relaxed">
                            Hello Professor! 👋 I've analyzed the data for <b>{{ $lecture->title }}</b>. How can I assist you today?
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border-t border-gray-100">
                    {{-- شريط معاينة الملف --}}
                    <div id="filePreview" class="mb-2 px-3 py-2 bg-purple-50 text-purple-700 text-xs rounded-xl hidden flex items-center justify-between border border-purple-100 shadow-sm">
                        <span id="fileNameDisplay" class="truncate max-w-[250px] font-medium"></span>
                        <button onclick="clearFile()" class="ml-2 text-rose-500 hover:text-rose-700 font-bold bg-white rounded-full w-5 h-5 flex items-center justify-center shadow-sm">×</button>
                    </div>

                    <div class="relative flex items-center gap-3">
                        {{-- مدخل الملف المخفي --}}
                        <input type="file" id="fileInput" class="hidden" accept="image/*,.pdf,.doc,.docx" onchange="handleFileSelect(this)">
                        
                        {{-- زر إضافة الملف (الزائد) --}}
                        <button type="button" onclick="document.getElementById('fileInput').click()" class="h-12 w-12 bg-gray-100 text-gray-500 rounded-2xl hover:bg-gray-200 transition flex items-center justify-center flex-shrink-0 shadow-sm border border-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>

                        <input type="text" id="userInput" placeholder="Type your request or ask about a file..." 
                               class="flex-1 bg-gray-100 border-none rounded-2xl py-3.5 px-6 focus:ring-2 focus:ring-purple-500 focus:bg-white transition-all text-sm shadow-inner">
                        
                        <button id="sendMessageBtn" class="h-12 w-12 bg-purple-600 text-white rounded-2xl hover:bg-purple-700 hover:scale-105 transition shadow-lg flex items-center justify-center flex-shrink-0 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Student Attendance List --}}
        <div class="bg-white shadow-sm rounded-lg mt-8">
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

  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

  <script>
    function formatMessage(text) {
        return marked.parse(text);
    }

    function appendMessage(msg, isUser = false) {
        const chatMessages = document.getElementById('chatMessages');
        const alignment = isUser ? 'justify-end' : 'justify-start';
        const bgColor = isUser ? 'bg-purple-600 text-white' : 'bg-white border border-gray-200 text-gray-900 shadow-sm';
        const rounded = isUser ? 'rounded-tr-none' : 'rounded-tl-none';
        
        const finalContent = isUser ? msg : formatMessage(msg);

        const icon = isUser ? '' : `
            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center mr-3 text-purple-600 border border-purple-200 shadow-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>`;
        
        const msgHtml = `
            <div class="flex ${alignment} items-start transition-all duration-300">
                ${icon}
                <div dir="auto" class="${bgColor} p-4 rounded-2xl ${rounded} text-base max-w-[85%] leading-relaxed font-medium ${!isUser ? 'prose prose-base prose-purple' : ''}">
                    ${finalContent}
                </div>
            </div>`;
        
        chatMessages.insertAdjacentHTML('beforeend', msgHtml);
        chatMessages.scrollTo({ top: chatMessages.scrollHeight, behavior: 'smooth' });
    }

    // دوال إدارة الملفات الجديدة
    function handleFileSelect(input) {
        const preview = document.getElementById('filePreview');
        const nameDisplay = document.getElementById('fileNameDisplay');
        if (input.files && input.files[0]) {
            nameDisplay.innerText = "📎 Selected: " + input.files[0].name;
            preview.classList.remove('hidden');
        }
    }

    function clearFile() {
        const fileInput = document.getElementById('fileInput');
        const preview = document.getElementById('filePreview');
        fileInput.value = '';
        preview.classList.add('hidden');
    }

    function quickAction(command) {
        const input = document.getElementById('userInput');
        input.value = command;
        document.getElementById('sendMessageBtn').click();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const sendMessageBtn = document.getElementById('sendMessageBtn');
        const userInput = document.getElementById('userInput');

        async function handleSend() {
            const msg = userInput.value.trim();
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];

            if (!msg && !file) return;

            // عرض الرسالة في الواجهة
            let displayMsg = msg;
            if (file) displayMsg += `<br><span class="text-xs opacity-70 italic font-normal">[Attached: ${file.name}]</span>`;
            appendMessage(displayMsg, true);

            // تصفير المدخلات
            userInput.value = '';
            clearFile();

            const loadingId = 'loading-' + Date.now();
            const loadingHtml = `<div id="${loadingId}" class="text-xs text-black-400 italic ml-10 animate-pulse">Veloria AI is analyzing...</div>`;
            document.getElementById('chatMessages').insertAdjacentHTML('beforeend', loadingHtml);

            // استخدام FormData لإرسال الرسالة والملف معاً
            const formData = new FormData();
            formData.append('message', msg);
            formData.append('lecture_id', "{{ $lecture->id }}");
            if (file) {
                formData.append('attachment', file);
            }

            try {
                const response = await fetch("{{ route('admin.ai.chat') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        // لا نضع Content-Type مع FormData
                    },
                    body: formData
                });

                const data = await response.json();
                const loader = document.getElementById(loadingId);
                if(loader) loader.remove();
                
                appendMessage(data.reply, false);
            } catch (error) {
                const loader = document.getElementById(loadingId);
                if(loader) loader.innerText = "Error: Could not connect to AI.";
                console.error(error);
            }
        }

        sendMessageBtn.addEventListener('click', handleSend);

        userInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') handleSend();
        });

        const exportCsvBtn = document.getElementById('exportCsvBtn');
        if(exportCsvBtn) {
            exportCsvBtn.addEventListener('click', function() {
                window.location.href = '{{ route("admin.api.lectures.attendance.export", $lecture->id) }}';
            });
        }
    });
</script>
</x-admin-layout>