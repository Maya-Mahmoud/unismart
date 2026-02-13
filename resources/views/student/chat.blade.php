@extends('layouts.student-app')

@section('title', 'مساعد الطالب الذكي')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex items-center">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <h1 class="ml-2 text-2xl font-medium text-gray-900">مساعد الطالب الذكي</h1>
        </div>
        <p class="mt-2 text-sm text-gray-600">يمكنك السؤال عن المحاضرات، القاعات، والإجراءات الجامعية</p>
    </div>

    <div class="bg-gray-50 px-6 py-4">
        <!-- Chat Messages Container -->
        <div id="chat-messages" class="space-y-4 mb-4 max-h-96 overflow-y-auto bg-white rounded-lg border border-gray-200 p-4">
            <!-- Welcome Message -->
            <div class="flex items-start space-x-3 rtl:space-x-reverse">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 bg-blue-50 rounded-lg p-3">
                    <p class="text-sm text-gray-800">مرحباً! أنا مساعدك الجامعي. يمكنك السؤال عن:</p>
                    <ul class="text-sm text-gray-600 mt-2 space-y-1">
                        <li>• مواعيد المحاضرات والمواد</li>
                        <li>• أماكن القاعات والمباني</li>
                        <li>• الإجراءات الجامعية والتسجيل</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Message Input -->
        <div class="flex space-x-3 rtl:space-x-reverse">
            <div class="flex-1">
                <input type="text"
                       id="message-input"
                       placeholder="اكتب سؤالك هنا..."
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-500"
                       maxlength="500">
            </div>
            <button onclick="sendMessage()"
                    id="send-button"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                <span class="sr-only">إرسال</span>
            </button>
        </div>

        <!-- Typing Indicator -->
        <div id="typing-indicator" class="hidden mt-3 text-sm text-gray-500">
            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                <div class="flex space-x-1">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
                <span>يكتب...</span>
            </div>
        </div>
    </div>
</div>

<script>
let isTyping = false;

function addMessage(content, isUser = false) {
    const messagesContainer = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex items-start space-x-3 rtl:space-x-reverse ${isUser ? 'justify-end' : ''}`;

    if (isUser) {
        messageDiv.innerHTML = `
            <div class="flex-1 max-w-xs lg:max-w-md">
                <div class="bg-blue-600 text-white rounded-lg p-3">
                    <p class="text-sm">${content}</p>
                </div>
                <p class="text-xs text-gray-500 mt-1 text-right">أنت</p>
            </div>
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1 max-w-xs lg:max-w-2xl">
                <div class="bg-gray-100 rounded-lg p-3">
                    <p class="text-sm text-gray-800 whitespace-pre-line">${content.replace(/https?:\/\/[^\s]+/g, '<a href="$&" target="_blank" class="text-blue-600 underline">$&</a>')}</p>
                </div>
                <p class="text-xs text-gray-500 mt-1">مساعد الطالب</p>
            </div>
        `;
    }

    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function showTypingIndicator() {
    if (isTyping) return;
    isTyping = true;
    document.getElementById('typing-indicator').classList.remove('hidden');
}

function hideTypingIndicator() {
    if (!isTyping) return;
    isTyping = false;
    document.getElementById('typing-indicator').classList.add('hidden');
}

async function sendMessage() {
    const input = document.getElementById('message-input');
    const button = document.getElementById('send-button');
    const message = input.value.trim();

    if (message === '') return;

    // Disable input and button
    input.disabled = true;
    button.disabled = true;

    // Add user message
    addMessage(message, true);

    // Clear input
    input.value = '';

    // Show typing indicator
    showTypingIndicator();

    try {
        // Get CSRF token
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Send to server
        const response = await fetch('{{ route("student.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        // Hide typing indicator
        hideTypingIndicator();

        // Add AI response
        addMessage(data.reply);

    } catch (error) {
        console.error('Error:', error);

        // Hide typing indicator
        hideTypingIndicator();

        // Add error message
        addMessage('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.');
    } finally {
        // Re-enable input and button
        input.disabled = false;
        button.disabled = false;
        input.focus();
    }
}

// Handle Enter key
document.getElementById('message-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// Focus on input when page loads
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('message-input').focus();
});
</script>
@endsection
