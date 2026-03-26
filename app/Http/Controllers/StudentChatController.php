<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeminiService; 
use Illuminate\Support\Facades\Log;
use App\Models\ChatMessage;
use App\Models\Conversation;
use Illuminate\Support\Str;

class StudentChatController extends Controller
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function getConversations()
    {
        $conversations = Conversation::where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')
            ->take(15)
            ->get(['id', 'title', 'updated_at']);

        return response()->json($conversations);
    }

    public function getMessages($id)
    {
        $conversation = Conversation::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $messages = ChatMessage::where('conversation_id', $id)
            ->orderBy('created_at', 'asc')
            ->get(['role', 'content']);

        return response()->json($messages);
    }

    /**
     * توليد عنوان ذكي للمحادثة (التعديل الجديد)
     */
    private function generateSmartTitle($firstMessage)
    {
        try {
            // طلب عنوان قصير جداً (3 كلمات كحد أقصى)
            $prompt = "Generate a very short title (max 3 words) in the same language for this message: '$firstMessage'. Return ONLY the title text.";
            $smartTitle = $this->gemini->askGemini($prompt);
            
            return trim(str_replace(['"', "'", '*', '-', '.'], '', $smartTitle));
        } catch (\Exception $e) {
            return Str::limit($firstMessage, 30);
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            $userMessage = $request->input('message');
            $conversationId = $request->input('conversation_id');

            if (!$userMessage) {
                return response()->json(['reply' => 'الرجاء كتابة رسالة.'], 400);
            }

            if (!$conversationId) {
                // استخدام توليد العنوان الذكي بدلاً من القص التلقائي
                $title = $this->generateSmartTitle($userMessage);
                $conversation = Conversation::create([
                    'user_id' => auth()->id(),
                    'title' => $title
                ]);
                $conversationId = $conversation->id; 
            } else {
                Conversation::where('id', $conversationId)->update(['updated_at' => now()]);
            }

            ChatMessage::create([
                'conversation_id' => $conversationId,
                'role' => 'user',
                'content' => $userMessage
            ]);

            // إضافة تعليمات الشخصية اللطيفة لضمان ردود طبيعية (Veloria Persona)
            $systemInstruction = "You are 'Veloria AI', the professional and friendly Academic Assistant for the UniSmart platform.
            PERSONALITY: Be helpful, supportive, and natural. If asked how you are, respond cheerfully in Arabic.
            RULES: Answer concisely and directly in the language used by the student.";
            
            $reply = $this->gemini->askGemini($systemInstruction . "\n\nUser: " . $userMessage);

            ChatMessage::create([
                'conversation_id' => $conversationId,
                'role' => 'assistant',
                'content' => $reply
            ]);

            return response()->json([
                'reply' => $reply,
                'conversation_id' => $conversationId
            ]);

        } catch (\Exception $e) {
            Log::error('Student Chat Error: ' . $e->getMessage());
            return response()->json(['reply' => 'عذراً، حدث خطأ: ' . $e->getMessage()], 500);
        }
    }
}