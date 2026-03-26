<x-admin-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-12">
        <div class="flex flex-col ">
            <a href="{{ route('admin.lectures') }}" class="text-base text-purple-600 text-lg hover:text-purple-700 mb-2">
                ← Back to Lectures
            </a>
           
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900"> {{ $lecture->title }}</h1>
                </div>
                <button id="exportCsvBtn"
                    class="flex items-center gap-2 bg-green-600 text-white px-5 py-3 rounded-xl text-sm font-semibold hover:bg-green-700 transition-all shadow-md hover:shadow-lg hover:scale-105 active:scale-95">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-y-1"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v12m0 0l4-4m-4 4l-4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
                    </svg>
                    Export CSV
                </button>
            </div>
        </div>
        

        {{-- Lecture Info Card (Top Banner) --}}
        <div class="bg-white p-7 border border-green-100 rounded-xl shadow-md hall-card mb-4 ">
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
            </div>
        </div>

        {{-- Main Layout Grid: Statistics (Left) & Veloria AI (Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
            
           {{-- Attendance Statistics Column (Side Column) --}}
<div class="lg:col-span-2 space-y-14">
    <h3 class="text-lg text-center font-bold text-purple-600 mb-3 px-1">Lecture Stats 👇🏻</h3>
    
    {{-- Grid for Small Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-1 gap-4 text-center">
        
        {{-- Total Students Card --}}
        <div class="bg-white p-4 border border-gray-100 rounded-2xl shadow-sm hall-card">
            <div class="flex flex-col">
                <p class="text-[14px] font-bold text-black-500 uppercase tracking-wider">Total Students</p>
                <div class="flex items-center justify-between mt-1">
                    <p class="text-xl font-black text-gray-900">{{ $totalStudents }}</p>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
                </div>
            </div>
        </div>

        {{-- Present Card --}}
        <div class="bg-green-50 p-4 border border-green-100 rounded-2xl shadow-sm hall-card">
            <div class="flex flex-col">
                <h4 class="text-[14px] font-bold text-green-600 uppercase tracking-wider">Present</h4>
                <div class="flex items-center justify-between mt-1">
                    <p class="text-xl font-black text-green-900">{{ $presentCount }}</p>
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Absent Card --}}
        <div class="bg-rose-50 p-4 border border-rose-100 rounded-2xl shadow-sm hall-card">
            <div class="flex flex-col">
                <h4 class="text-[14px] font-bold text-rose-600 uppercase tracking-wider">Absent</h4>
                <div class="flex items-center justify-between mt-1">
                    <p class="text-xl font-black text-rose-900">{{ $absentCount }}</p>
                    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Attendance Rate Card --}}
        <div class="bg-blue-50 p-4 border border-blue-100 rounded-2xl shadow-sm hall-card">
            <div class="flex flex-col">
                <h4 class="text-[14px] font-bold text-blue-600 uppercase tracking-wider">Att. Rate</h4>
                <div class="flex items-center justify-between mt-1">
                    <p class="text-xl font-black text-blue-900">{{ $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100, 1) : 0 }}%</p>
                   <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
                </div>
            </div>
        </div>

        {{-- Absent Rate Card (الجديد) --}}
        <div class="bg-amber-50 p-4 border border-amber-100 rounded-2xl shadow-sm hall-card">
            <div class="flex flex-col">
                <h4 class="text-[14px] font-bold text-amber-600 uppercase tracking-wider">Abs. Rate</h4>
                <div class="flex items-center justify-between mt-1">
                    <p class="text-xl font-black text-amber-900">{{ $totalStudents > 0 ? round(($absentCount / $totalStudents) * 100, 1) : 0 }}%</p>
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

    </div>
</div>

            {{-- Veloria AI Column (Main Content) --}}
            <div class="lg:col-span-10">
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-purple-100 flex flex-col md:flex-row h-[740px]">
                    {{-- Sidebar Veloria --}}
                    <div class="w-full md:w-72 bg-gradient-to-br from-purple-700 via-indigo-800 to-indigo-950 p-6 text-white flex flex-col justify-between relative">
                        <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                        <div class="relative z-10">
                            <div class="inline-flex p-3 bg-white/10 rounded-2xl backdrop-blur-md mb-6 border border-white/20">
                                <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-1 tracking-tight">VELORIA AI</h3>
                            <p class="text-purple-200/80 text-xs mb-8 leading-relaxed font-light">Intelligent Academic Assistant</p>
                            
                            <div class="space-y-3">
                                <p class="text-[10px] uppercase tracking-widest font-bold text-purple-300/60">Tools</p>
                                <button onclick="openQuizModal()" class="w-full flex items-center justify-between px-4 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all group text-sm border border-white/5">
                                    <span >💡 Generate Quiz</span>
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                                <hr class="border-white/10 my-4">
                                <p class="text-[12px] uppercase tracking-widest font-bold text-purple-300/60 flex justify-between items-center">
                                    <span>Recent Chats</span>
                                    <button onclick="createNewChat()" class="hover:text-white p-2" title="New Chat">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </p>
                                <div id="conversationsList" class="space-y-2 max-h-70 overflow-y-auto custom-scrollbar pr-1">
                                    <p class="text-[12px] text-purple-400/50 italic">Loading chats...</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-[12px] text-purple-400/50 italic">Powered by Google Grok</p>
                    </div>

                    {{-- Chat Area --}}
                    <div class="flex-1 flex flex-col bg-gray-50/50">
                        <div class="px-6 py-4 bg-white border-b border-gray-100 flex items-center justify-between">
                            <span class="flex items-center text-base text-xs font-semibold text-green-600 uppercase tracking-widest">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span> System Online
                            </span>
                        </div>

                        <div id="chatMessages" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
                            <div class="flex justify-start items-start">
                                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center mr-3 text-purple-600 border border-purple-200 shadow-sm">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <div class="bg-white border border-gray-100 p-4 rounded-2xl rounded-tl-none shadow-sm text-gray-700 max-w-[85%] text-m leading-relaxed">
                                    Hello {{Auth::user()->name}}! 👋  How can I help you today?
                                </div>
                            </div>
                        </div>

                        {{-- Input Area --}}
                <div class="p-6 bg-white border-t border-gray-100">
                    <div id="filePreview" class="mb-2 px-3 py-2 bg-purple-50 text-purple-700 text-m rounded-xl hidden flex items-center justify-between border border-purple-100 shadow-sm">
                        <span id="fileNameDisplay" class="truncate font-medium"></span>
                        <button onclick="clearFile()" class="text-rose-500 font-bold hover:bg-white rounded-full w-5 h-5 flex items-center justify-center shadow-sm">×</button>
                    </div>
                    <div class="relative flex items-center gap-3">
                        <input type="file" id="fileInput" class="hidden" onchange="handleFileSelect(this)">
                        <button type="button" onclick="document.getElementById('fileInput').click()" class="h-12 w-12 bg-gray-100 rounded-2xl flex items-center justify-center border border-gray-200 hover:bg-gray-200 transition">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                        <input type="text" id="userInput" placeholder="Ask Veloria..." class="flex-1 bg-black-150 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-purple-500 transition-all text-base font-medium">
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
        // المتغيرات العالمية للتحكم بالسجل
        let currentConversationId = null;

        // التحقق من JSON بصيغة مرنة
        const isJson = (str) => { 
            try { 
                const trimmed = str.trim();
                if ((trimmed.startsWith('[') && trimmed.endsWith(']')) || (trimmed.startsWith('{') && trimmed.endsWith('}'))) {
                    JSON.parse(trimmed);
                    return true;
                }
            } catch (e) { return false; }
            return false;
        };

        function downloadQuizAsFile(jsonData) {
            try {
                const dataStr = JSON.stringify(jsonData, null, 2);
                const blob = new Blob([dataStr], { type: 'application/json' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                const date = new Date().toISOString().slice(0, 10);
                a.download = `Quiz_{{ $lecture->id }}_${date}.json`;
                a.href = url;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                alert("Done! Your quiz is downloaded. 📥");
            } catch (e) {
                console.error("Download failed", e);
            }
        }

        function renderQuiz(data) {
            window.lastQuizData = data; 
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
                    </div>
                </div>`;
        }

        function appendMessage(msg, isUser = false) {
            const chatMessages = document.getElementById('chatMessages');
            let isQuiz = !isUser && isJson(msg);
            const alignment = isUser ? 'justify-end' : 'justify-start';
            const bgColor = isQuiz ? '' : (isUser ? 'bg-purple-600 text-white shadow-lg' : 'bg-white border border-gray-100 shadow-md');
            const rounded = isUser ? 'rounded-tr-none' : 'rounded-tl-none';
            const messageId = 'msg-' + Date.now();

            const html = `
                <div class="flex ${alignment} items-start mb-6" dir="${!isUser ? 'rtl' : 'ltr'}">
                    ${!isUser ? `<div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center ml-3 text-purple-600 border border-purple-200 shadow-sm"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>` : ''}
                    <div id="${messageId}" class="${bgColor} ${isQuiz ? 'w-full' : 'p-5 max-w-[85%]'} rounded-3xl ${rounded} text-base leading-relaxed font-medium text-right shadow-sm overflow-hidden">
                        ${isUser ? msg : (isQuiz ? renderQuiz(JSON.parse(msg)) : "")}
                    </div>
                </div>`;
            
            chatMessages.insertAdjacentHTML('beforeend', html);
            const messageContainer = document.getElementById(messageId);

            if (!isUser && !isQuiz) {
                const finalHtml = marked.parse(msg);
                messageContainer.innerHTML = ""; 
                const words = msg.split(' ');
                let i = 0;
                
                function stream() {
                    if (i < words.length) {
                        const currentText = words.slice(0, i + 1).join(' ');
                        messageContainer.innerHTML = marked.parse(currentText);
                        i++;
                        chatMessages.scrollTo({ top: chatMessages.scrollHeight });
                        setTimeout(stream, 40);
                    }
                }
                stream();
            }
            chatMessages.scrollTo({ top: chatMessages.scrollHeight, behavior: 'smooth' });
        }

        // --- وظائف سجل المحادثات (New) ---
        async function loadConversations() {
            try {
                const response = await fetch("{{ route('ai.conversations') }}");
                const conversations = await response.json();
                const container = document.getElementById('conversationsList');
                container.innerHTML = '';

                if (conversations.length === 0) {
                    container.innerHTML = '<p class="text-[10px] text-purple-400/50 italic">No recent chats.</p>';
                    return;
                }

                conversations.forEach(conv => {
                    container.insertAdjacentHTML('beforeend', `
                        <button onclick="loadChatHistory(${conv.id})" class="w-full text-left px-3 py-2 rounded-lg text-[14px] bg-white/5 hover:bg-white/10 transition truncate border border-white/5 text-purple-100">
                          🏷️  ${conv.title || 'Chat session'}
                        </button>
                    `);
                });
            } catch (e) {
                console.error("Conversations load error", e);
            }
        }

        function createNewChat() {
            currentConversationId = null;
            document.getElementById('chatMessages').innerHTML = `
                <div class="flex justify-start items-start">
                    <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center mr-3 text-purple-600 border border-purple-200 shadow-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="bg-white border border-gray-100 p-4 rounded-2xl rounded-tl-none shadow-sm text-gray-700 max-w-[85%] text-sm">
                        New session started. How can I help you?
                    </div>
                </div>`;
        }

       async function loadChatHistory(id) {
    currentConversationId = id;
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.innerHTML = '<div class="text-center text-purple-400 text-xs animate-pulse">Retrieving history...</div>';

    try {
        // التعديل هنا: حذف كلمة admin من الرابط
        const response = await fetch(`/ai/conversations/${id}/messages`); 
        
        const messages = await response.json();
        chatMessages.innerHTML = '';
        
        if (messages.length === 0) {
            chatMessages.innerHTML = '<p class="text-center text-gray-400 text-xs">No messages in this chat.</p>';
        }

        messages.forEach(msg => appendMessage(msg.content, msg.role === 'user'));
    } catch (e) {
        console.error("Error loading messages:", e);
        chatMessages.innerHTML = '<p class="text-rose-500 text-xs text-center">Failed to load history.</p>';
    }
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
            if (currentConversationId) formData.append('conversation_id', currentConversationId);
            if (file) formData.append('attachment', file);

            try {
                const response = await fetch("{{ route('ai.chat') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                });
                const data = await response.json();
                
                if (data.conversation_id && !currentConversationId) {
                    currentConversationId = data.conversation_id;
                    loadConversations();
                }

                document.getElementById(loadingId)?.remove();
                appendMessage(data.reply || "No response.");
            } catch (e) {
                if(document.getElementById(loadingId)) document.getElementById(loadingId).innerText = "Connection error.";
            }
        }

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

        function handleFileSelect(input) {
            const preview = document.getElementById('filePreview');
            if (input.files[0]) {
                document.getElementById('fileNameDisplay').innerText = "📎 Selected: " + input.files[0].name;
                preview.classList.remove('hidden');
            }
        }

        function clearFile() { 
            document.getElementById('fileInput').value = ''; 
            document.getElementById('filePreview').classList.add('hidden'); 
        }



        async function openQuizModal() {
            const modal = document.getElementById('quizSelectionModal');
            const container = document.getElementById('filesListContainer');
            modal.classList.remove('hidden');
            
            try {
                const response = await fetch(`/admin/lectures/get-by-subject/{{ $lecture->subject_id }}`);
                const lectures = await response.json();
                container.innerHTML = '';
                let hasFiles = false;
                lectures.forEach(lec => {
                    if (lec.lecture_files?.length > 0) {
                        hasFiles = true;
                        lec.lecture_files.forEach(file => {
                            container.insertAdjacentHTML('beforeend', `
                                <label class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-purple-300 transition cursor-pointer group">
                                    <input type="checkbox" name="quiz_files" value="${file.id}" class="w-5 h-5 text-purple-600 border-gray-300 rounded">
                                    <div class="ml-4">
                                        <p class="text-sm font-bold text-gray-900 group-hover:text-purple-700">${file.file_name}</p>
                                        <p class="text-[10px] text-gray-500 uppercase">${lec.title}</p>
                                    </div>
                                </label>`);
                        });
                    }
                });
                if(!hasFiles) container.innerHTML = '<p class="text-center text-gray-400 py-4 italic">No files found.</p>';
            } catch (e) { container.innerHTML = '<p class="text-rose-500 text-center">Error loading files.</p>'; }
        }

        function closeQuizModal() { document.getElementById('quizSelectionModal').classList.add('hidden'); }

        function confirmQuizFiles() {
            const selected = Array.from(document.querySelectorAll('input[name="quiz_files"]:checked')).map(cb => cb.value);
            if(selected.length === 0) return alert('Select at least one file!');
            closeQuizModal();
            document.getElementById('userInput').value = `Generate an interactive quiz based on these file IDs: ${selected.join(', ')}`;
            handleSend();
        }

        document.addEventListener('DOMContentLoaded', function () {
            loadConversations();
            document.getElementById('sendMessageBtn').addEventListener('click', handleSend);
            document.getElementById('userInput').addEventListener('keypress', (e) => e.key === 'Enter' && handleSend());
            
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