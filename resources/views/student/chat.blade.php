@extends('layouts.student-app')

@section('title', 'Veloria AI Assistant')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
    <div class="section-header mb-8">
        <h1 class="section-title text-3xl font-bold">Veloria AI Assistant</h1>
        <p class="section-subtitle">Your intelligent academic companion for lectures, halls, and more</p>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-200 flex flex-col lg:flex-row h-[70vh] max-h-[800px]">
        
        <div class="w-full lg:w-80 bg-gradient-to-br from-purple-600 via-indigo-600 to-blue-700 p-6 text-white flex flex-col">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold">Veloria AI</h3>
                    <p class="text-blue-100 text-sm">Academic Assistant</p>
                </div>
            </div>

            <div class="flex-1 min-h-0 flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xs font-bold uppercase tracking-wide text-blue-200">Recent Chats</span>
                    <button class="bg-white/10 hover:bg-white/20 p-1.5 rounded-lg transition" onclick="newChat()" title="New Chat">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
                <div id="chatHistory" class="space-y-2 overflow-y-auto custom-scrollbar flex-1 pr-2">
                    <div class="text-xs text-blue-300 italic">Loading history...</div>
                </div>
            </div>
        </div>

        <div class="flex-1 flex flex-col bg-gradient-to-b from-gray-50 to-white">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3 text-purple-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-bold text-gray-800 block">Veloria AI</span>
                        <span class="text-[10px] text-green-500 flex items-center">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1 animate-pulse"></span> Active Now
                        </span>
                    </div>
                </div>
            </div>

            <div id="messages" class="flex-1 p-6 overflow-y-auto space-y-4 custom-scrollbar">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 flex-shrink-0 mt-1">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-2xl p-4 max-w-lg shadow-sm">
                        <p class="text-gray-800 text-sm">Hello!👋  I'm Veloria. How can I assist you with your university tasks today?</p>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 bg-white">
                <div class="flex items-end space-x-3">
                    <input type="text" id="messageInput" placeholder="Type your academic question..." 
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition text-sm">
                    <button onclick="sendMessage()" id="sendBtn" 
                            class="w-12 h-12 bg-purple-600 text-white rounded-2xl hover:bg-purple-700 flex items-center justify-center shadow-lg transition active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentConversationId = null;

// تحميل سجل المحادثات فور فتح الصفحة
document.addEventListener('DOMContentLoaded', loadConversations);

function loadConversations() {
    fetch("{{ route('student.chat.conversations') }}")
        .then(res => res.json())
        .then(data => {
            const historyContainer = document.getElementById('chatHistory');
            historyContainer.innerHTML = '';
            
            if (data.length === 0) {
                historyContainer.innerHTML = '<div class="text-xs text-blue-300 italic opacity-70 p-2">No recent sessions.</div>';
                return;
            }

            data.forEach(conv => {
                const item = document.createElement('div');
                // تنسيق العنصر في السجل
                item.className = "p-3 mb-2 rounded-xl bg-white/5 hover:bg-white/20 cursor-pointer transition text-sm border border-transparent hover:border-white/30 truncate flex items-center group";
                item.innerHTML = `
                   
                    <span class="truncate">🏷️ ${conv.title}</span>
                `;
                item.onclick = () => loadMessages(conv.id);
                historyContainer.appendChild(item);
            });
        });
}

function loadMessages(id) {
    currentConversationId = id;
    const container = document.getElementById('messages');
    container.innerHTML = ''; 
    showTyping(); // إظهار مؤشر التحميل

    fetch(`/student/chat/messages/${id}`)
        .then(res => res.json())
        .then(messages => {
            hideTyping();
            if(messages.length === 0) {
                appendMessage("This conversation is empty.", false);
            } else {
                messages.forEach(m => appendMessage(m.content, m.role === 'user'));
            }
        });
}

function newChat() {
    currentConversationId = null;
    document.getElementById('messages').innerHTML = `
        <div class="flex items-start space-x-3 mb-4">
            <div class="bg-white border border-gray-200 rounded-2xl p-4 max-w-lg shadow-sm">
                <p class="text-gray-800 text-sm italic opacity-70">New session started. Ask me anything!</p>
            </div>
        </div>`;
    document.getElementById('messageInput').focus();
}

function sendMessage() {
    const input = document.getElementById('messageInput');
    const btn = document.getElementById('sendBtn');
    const msg = input.value.trim();
    
    if (!msg) return;

    appendMessage(msg, true);
    input.value = '';
    input.disabled = true;
    btn.disabled = true;
    showTyping();

    // إرسال الطلب مع الـ ID للمحادثة
    fetch("{{ route('student.student.chat.send') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            message: msg,
            conversation_id: currentConversationId 
        })
    })
    .then(res => res.json())
    .then(data => {
        hideTyping();
        appendMessage(data.reply, false);
        
        // إذا بدأت محادثة جديدة، نحدث السجل فوراً
        if (!currentConversationId && data.conversation_id) {
            currentConversationId = data.conversation_id;
            loadConversations(); 
        }
    })
    .catch(err => {
        hideTyping();
        appendMessage('Connection error. Please check your internet.', false);
    })
    .finally(() => {
        input.disabled = false;
        btn.disabled = false;
        input.focus();
    });
}
function appendMessage(content, isUser) {
    const container = document.getElementById('messages');
    const div = document.createElement('div');
    div.className = `flex ${isUser ? 'justify-end' : 'justify-start'} mb-4`;
    
    const bubbleClass = isUser 
        ? 'bg-purple-600 text-white rounded-t-2xl rounded-bl-2xl px-4 py-3 shadow-md ml-12' 
        : 'bg-white border border-purple-100 text-gray-800 rounded-t-2xl rounded-br-2xl px-4 py-3 shadow-sm mr-12';

    div.innerHTML = `
        <div class="flex flex-col ${isUser ? 'items-end' : 'items-start'}">
            <div class="${bubbleClass} max-w-lg">
                <p class="text-base leading-relaxed" id="typing-text-${Date.now()}"></p> 
            </div>
            <span class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest font-semibold">
                ${isUser ? 'You' : 'Veloria AI'}
            </span>
        </div>`;
    
    container.appendChild(div);
    const textElement = div.querySelector('p');

    // إذا كان المستخدم هو اللي كاتب، يظهر الحكي فوراً
    // أما إذا كان Veloria، يظهر كلمة كلمة
    if (isUser) {
        textElement.innerText = content;
    } else {
        typeWriter(textElement, content);
    }

    container.scrollTop = container.scrollHeight;
}

// دالة المحاكاة للكتابة التدريجية
function typeWriter(element, text, index = 0) {
    if (index < text.length) {
        element.innerHTML += text.charAt(index);
        index++;
        setTimeout(() => typeWriter(element, text, index), 15); // سرعة الكتابة بالـ ms
        
        // لجعل السكرول ينزل تلقائياً مع الكتابة
        const container = document.getElementById('messages');
        container.scrollTop = container.scrollHeight;
    }
}

function showTyping() {
    const container = document.getElementById('messages');
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typingBubble';
    typingDiv.className = 'flex justify-start mb-4';
    typingDiv.innerHTML = `
        <div class="flex flex-col items-start">
            <div class="bg-purple-50 border border-purple-100 rounded-t-2xl rounded-br-2xl px-4 py-3 mr-12">
                <div class="flex space-x-1">
                    <div class="w-1.5 h-1.5 bg-purple-400 rounded-full animate-bounce"></div>
                    <div class="w-1.5 h-1.5 bg-purple-400 rounded-full animate-bounce" style="animation-delay:0.2s"></div>
                    <div class="w-1.5 h-1.5 bg-purple-400 rounded-full animate-bounce" style="animation-delay:0.4s"></div>
                </div>
            </div>
        </div>`;
    container.appendChild(typingDiv);
    container.scrollTop = container.scrollHeight;
}

function hideTyping() {
    const loader = document.getElementById('typingBubble');
    if (loader) loader.remove();
}

// دعم زر Enter
document.getElementById('messageInput').addEventListener('keypress', e => {
    if (e.key === 'Enter') sendMessage();
});
</script>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection