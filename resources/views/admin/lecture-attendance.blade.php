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



                <button id="exportCsvBtn"
    class="flex items-center gap-2 bg-green-600 text-white px-5 py-3 rounded-xl text-sm font-semibold hover:bg-green-700 transition-all shadow-md hover:shadow-lg hover:scale-105 active:scale-95">

    <!-- Download Icon -->
    <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-y-1"
         fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 3v12m0 0l4-4m-4 4l-4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
    </svg>

    Export CSV
</button>

            </div>

        </div>

        <br>



        {{-- Lecture Info Card --}}

        <div class="bg-white p-8 border border-green-100 rounded-xl shadow-md hall-card">

            <div class="flex flex-wrap justify-between items-start gap-y-4">

                {{-- Date Section --}}

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



                {{-- Time Section --}}

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



                {{-- Location Section --}}

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



                {{-- Professor Section --}}

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



                {{-- Total Students --}}

                <div class="flex items-start min-w-1/4">

                    <div class="mr-3 text-green-500">

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>

                        </svg>

                    </div>

                    <div>

                        <p class="text-base font-medium text-black-700">Total Students</p>

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



        {{-- Gemini Assistant Chat Card (Veloria AI) --}}

        <div class="mt-8 bg-white rounded-3xl shadow-xl overflow-hidden border border-purple-100 flex flex-col md:flex-row h-[550px]">

            <div class="w-full md:w-80 bg-gradient-to-br from-purple-700 via-indigo-800 to-indigo-950 p-8 text-white flex flex-col justify-between relative">

                <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>

               

                <div class="relative z-10">

                    <div class="inline-flex p-3 bg-white/10 rounded-2xl backdrop-blur-md mb-6 border border-white/20">

                        <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>

                        </svg>

                    </div>
                    <h3 class="text-2xl font-bold mb-2 tracking-tight">VELORIA AI</h3>
                    <p class="text-purple-200/80 text-sm mb-8 leading-relaxed font-light">Intelligent Academic Assistant</p>
                    
                    <div class="space-y-3">
                        <p class="text-[10px] uppercase tracking-widest font-bold text-purple-300/60">Tools</p>
                        <button onclick="quickAction('Summarize Attendance')" class="w-full flex items-center justify-between px-4 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all group text-sm border border-white/5">
                            <span>📝 Attendance Summary</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <button onclick="openQuizModal()" class="w-full flex items-center justify-between px-4 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all group text-sm border border-white/5">
                            <span>💡 Generate Quiz</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </div>
                <p class="text-[10px] text-purple-400/50 italic">Powered by Google Gemini</p>
            </div>

            {{-- Chat Area --}}
            <div class="flex-1 flex flex-col bg-gray-50/50">
                <div class="px-6 py-3 bg-white/80 backdrop-blur-sm border-b border-gray-100 flex items-center justify-between">
                    <span class="flex items-center text-xs font-semibold text-green-500 uppercase tracking-widest">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span> System Online
                    </span>
                </div>

                <div id="chatMessages" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
                    <div class="flex justify-start items-start">
                        <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center mr-3 text-purple-600 border border-purple-200 shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div class="bg-white border border-gray-100 p-4 rounded-2xl rounded-tl-none shadow-sm text-gray-700 max-w-[85%] text-sm">
                            Hello Professor! 👋 I've analyzed <b>{{ $lecture->title }}</b>. How can I help?
                        </div>
                    </div>
                </div>

                {{-- Input Area --}}
                <div class="p-6 bg-white border-t border-gray-100">
                    <div id="filePreview" class="mb-2 px-3 py-2 bg-purple-50 text-purple-700 text-xs rounded-xl hidden flex items-center justify-between border border-purple-100 shadow-sm">
                        <span id="fileNameDisplay" class="truncate font-medium"></span>
                        <button onclick="clearFile()" class="text-rose-500 font-bold hover:bg-white rounded-full w-5 h-5 flex items-center justify-center shadow-sm">×</button>
                    </div>
                    <div class="relative flex items-center gap-3">
                        <input type="file" id="fileInput" class="hidden" onchange="handleFileSelect(this)">
                        <button type="button" onclick="document.getElementById('fileInput').click()" class="h-12 w-12 bg-gray-100 rounded-2xl flex items-center justify-center border border-gray-200 hover:bg-gray-200 transition">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                        <input type="text" id="userInput" placeholder="Ask Veloria..." class="flex-1 bg-gray-100 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-purple-500 transition-all text-base font-medium">
                        <button id="sendMessageBtn" class="h-12 w-12 bg-purple-600 text-white rounded-2xl hover:bg-purple-700 transition shadow-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Select Files for Quiz --}}
    <div id="quizSelectionModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md relative z-10 border border-purple-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">Generate Quiz 💡</h3>
                    <button onclick="closeQuizModal()" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
                </div>
                <p class="text-xs text-gray-500 mb-6 font-medium">Choose documents to include in the AI analysis:</p>
                
                <div id="filesListContainer" class="space-y-3 max-h-60 overflow-y-auto mb-8 pr-2 custom-scrollbar">
                    {{-- Dynamically filled via JS --}}
                    <div class="animate-pulse flex space-y-4 flex-col">
                        <div class="h-10 bg-gray-100 rounded-xl w-full"></div>
                        <div class="h-10 bg-gray-100 rounded-xl w-full"></div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button onclick="confirmQuizFiles()" class="flex-1 bg-purple-600 text-white py-3.5 rounded-2xl font-bold hover:bg-purple-700 transition shadow-lg">Generate Now 🚀</button>
                    <button onclick="closeQuizModal()" class="flex-1 bg-gray-100 text-gray-500 py-3.5 rounded-2xl font-bold hover:bg-gray-200 transition">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        // دالة تحميل ملف الـ JSON على الجهاز
function downloadQuizAsFile(jsonData) {
    try {
        const dataStr = JSON.stringify(jsonData, null, 2); // تنسيق الـ JSON ليطلع مرتب
        const blob = new Blob([dataStr], { type: 'application/json' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        
        // تسمية الملف باسم المحاضرة مع تاريخ اليوم
        const date = new Date().toISOString().slice(0, 10);
        a.download = `Quiz_{{ $lecture->id }}_${date}.json`;
        
        a.href = url;
        document.body.appendChild(a);
        a.click();
        
        // تنظيف الذاكرة
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        alert("Done! Your quiz is downloaded. Now you can upload it in the lecture files. 📥");
    } catch (e) {
        console.error("Download failed", e);
        alert("Something went wrong with the download.");
    }
}
        // --- Quiz Selection Logic ---
        async function openQuizModal() {
            const modal = document.getElementById('quizSelectionModal');
            const container = document.getElementById('filesListContainer');
            modal.classList.remove('hidden');
            
            const subjectId = "{{ $lecture->subject_id }}";
            try {
                const response = await fetch(`/admin/lectures/get-by-subject/${subjectId}`);
                const lectures = await response.json();
                container.innerHTML = '';

                let hasFiles = false;
                lectures.forEach(lec => {
                    if (lec.lecture_files && lec.lecture_files.length > 0) {
                        hasFiles = true;
                        lec.lecture_files.forEach(file => {
                            container.insertAdjacentHTML('beforeend', `
                                <label class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-purple-300 transition cursor-pointer group">
                                    <input type="checkbox" name="quiz_files" value="${file.id}" class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-900 group-hover:text-purple-700">${file.file_name}</p>
                                        <p class="text-[10px] text-gray-500 uppercase">${lec.title}</p>
                                    </div>
                                </label>
                            `);
                        });
                    }
                });
                if(!hasFiles) container.innerHTML = '<p class="text-center text-gray-400 py-4 italic">No files found.</p>';
            } catch (e) {
                container.innerHTML = '<p class="text-rose-500 text-center">Error loading files.</p>';
            }
        }

        function closeQuizModal() { document.getElementById('quizSelectionModal').classList.add('hidden'); }

        function confirmQuizFiles() {
            const selected = Array.from(document.querySelectorAll('input[name="quiz_files"]:checked')).map(cb => cb.value);
            if(selected.length === 0) return alert('Select at least one file!');
            
            closeQuizModal();
            const msg = `Generate an interactive quiz based on these file IDs: ${selected.join(', ')}`;
            document.getElementById('userInput').value = msg;
            handleSend();
        }

        // --- UI & Chat Logic ---
        const isJson = (str) => { try { JSON.parse(str); return true; } catch (e) { return false; } };

        function checkUserAnswer(btn, selected, correct) {
            const card = btn.closest('.quiz-card');
            const feedback = card.querySelector('.feedback-area');
            const allBtns = card.querySelectorAll('.quiz-opt-btn');
            
            feedback.classList.remove('hidden');
            allBtns.forEach(b => b.disabled = true);

            if (selected === correct) {
                btn.classList.add('bg-emerald-100', 'border-emerald-500', 'text-emerald-700');
                feedback.innerHTML = "✅ Correct! Well done.";
                feedback.classList.add('text-emerald-600');
            } else {
                btn.classList.add('bg-rose-100', 'border-rose-500', 'text-rose-700');
                feedback.innerHTML = `❌ Incorrect. The answer is: <b>${correct}</b>`;
                feedback.classList.add('text-rose-600');
            }
        }

       function renderQuiz(data) {
    // تخزين البيانات في سمة البيانات (Data Attribute) للزر
    const quizJsonBase64 = btoa(unescape(encodeURIComponent(JSON.stringify(data))));

    return `
        <div class="space-y-4">
            ${data.map((q, i) => `
                <div class="quiz-card bg-white/40 backdrop-blur-md border border-purple-200 p-5 rounded-2xl shadow-sm">
                    <p class="font-bold text-gray-800 mb-4 text-base">${i+1}. ${q.question}</p>
                    <div class="grid gap-2">
                        ${q.options.map(opt => `
                            <button onclick="checkUserAnswer(this, '${opt.replace(/'/g, "\\'")}', '${q.answer.replace(/'/g, "\\'")}')" 
                                    class="quiz-opt-btn text-left p-3 rounded-xl bg-white border border-gray-200 hover:border-purple-400 transition text-base font-medium text-gray-700">
                                ${opt}
                            </button>
                        `).join('')}
                    </div>
                    <div class="feedback-area mt-3 hidden text-[11px] font-bold italic"></div>
                </div>
            `).join('')}
            
            <div class="mt-8 flex flex-col items-center p-6 bg-emerald-50 rounded-3xl border-2 border-dashed border-emerald-200 shadow-inner">
                <p class="text-emerald-700 font-bold mb-3 text-sm">Perfect! The interactive quiz is ready.</p>
               <button onclick="downloadQuizAsFile(window.lastQuizData)" 
        class="w-full flex items-center justify-center bg-emerald-600 text-white px-6 py-4 rounded-2xl font-extrabold hover:bg-emerald-700 transition shadow-xl transform hover:scale-[1.02]">
    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    DOWNLOAD INTERACTIVE QUIZ (.JSON)
</button>
                <p class="text-[10px] text-emerald-600/70 mt-3 italic font-semibold">Step 2: Upload this file to your lecture materials.</p>
            </div>
        </div>`;
}
       function appendMessage(msg, isUser = false) {
    const chatMessages = document.getElementById('chatMessages');
    const isQuiz = !isUser && isJson(msg);
    
    if (isQuiz) {
        // حفظ البيانات في متغير عالمي ليسهل الوصول إليها عند الضغط على الزر
        window.lastQuizData = JSON.parse(msg); 
    }

    const content = isQuiz ? renderQuiz(window.lastQuizData) : (isUser ? msg : marked.parse(msg));
    
    // باقي الكود كما هو...
    const alignment = isUser ? 'justify-end' : 'justify-start';
    const bgColor = isQuiz ? '' : (isUser ? 'bg-purple-600 text-white shadow-lg' : 'bg-white border border-gray-100 shadow-md');
    const rounded = isUser ? 'rounded-tr-none' : 'rounded-tl-none';

    const html = `
        <div class="flex ${alignment} items-start mb-6">
            ${!isUser ? `<div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center mr-3 text-purple-600 border border-purple-200 shadow-sm"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>` : ''}
            <div class="${bgColor} ${isQuiz ? 'w-full' : 'p-5 max-w-[85%]'} rounded-3xl ${rounded} text-base md:text-lg leading-relaxed font-medium">
                ${content}
            </div>
        </div>`;
    
    chatMessages.insertAdjacentHTML('beforeend', html);
    chatMessages.scrollTo({ top: chatMessages.scrollHeight, behavior: 'smooth' });
}

        async function handleSend() {
            const input = document.getElementById('userInput');
            const fileInput = document.getElementById('fileInput');
            const msg = input.value.trim();
            const file = fileInput.files[0];

            if (!msg && !file) return;

            appendMessage(msg + (file ? `<br><small class="opacity-70">[Attached: ${file.name}]</small>` : ''), true);
            input.value = '';
            clearFile();

            const loadingId = 'loading-' + Date.now();
            document.getElementById('chatMessages').insertAdjacentHTML('beforeend', `<div id="${loadingId}" class="text-[10px] text-purple-400 italic ml-10 mb-4 animate-pulse">Veloria AI is analyzing...</div>`);

            const formData = new FormData();
            formData.append('message', msg);
            formData.append('lecture_id', "{{ $lecture->id }}");
            if (file) formData.append('attachment', file);

            try {
                const response = await fetch("{{ route('admin.ai.chat') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const data = await response.json();
                document.getElementById(loadingId)?.remove();
                appendMessage(data.reply || "API Error.");
            } catch (e) {
                if(document.getElementById(loadingId)) document.getElementById(loadingId).innerText = "Connection error.";
            }
        }

        function handleFileSelect(input) {
            const preview = document.getElementById('filePreview');
            if (input.files[0]) {
                document.getElementById('fileNameDisplay').innerText = "📎 Selected: " + input.files[0].name;
                preview.classList.remove('hidden');
            }
        }
        function clearFile() { document.getElementById('fileInput').value = ''; document.getElementById('filePreview').classList.add('hidden'); }
        function quickAction(cmd) { document.getElementById('userInput').value = cmd; handleSend(); }

        document.getElementById('sendMessageBtn').addEventListener('click', handleSend);
        document.getElementById('userInput').addEventListener('keypress', (e) => e.key === 'Enter' && handleSend());
         document.addEventListener('DOMContentLoaded', function () {
        const exportCsvBtn = document.getElementById('exportCsvBtn');

        if (exportCsvBtn) {
            exportCsvBtn.addEventListener('click', function () {
                window.location.href = '{{ route("admin.api.lectures.attendance.export", $lecture->id) }}';
            });
        }
    });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .quiz-card { transition: all 0.2s ease-in-out; }
        .quiz-card:hover { border-color: #a855f7; background: rgba(255, 255, 255, 0.6); }
    </style>
</x-admin-layout>