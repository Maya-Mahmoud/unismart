<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeminiService;
use App\Models\Lecture;
use App\Models\ChatMessage;
use App\Models\LectureFile;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * جلب قائمة المحادثات الخاصة بالمستخدم الحالي
     */
    public function getConversations()
    {
        $conversations = Conversation::where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')
            ->take(15)
            ->get(['id', 'title', 'updated_at']);

        return response()->json($conversations);
    }

    /**
     * جلب رسائل محادثة معينة
     */
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
     * التعديل المطلوب للسرعة: توليد عنوان سريع بدلاً من استخدام الـ API
     */
    private function generateFastTitle($firstMessage)
    {
        return Str::limit($firstMessage, 30);
    }
    /**
     * توليد عنوان ذكي للمحادثة باستخدام الذكاء الاصطناعي
     */
    private function generateSmartTitle($firstMessage)
    {
        try {
            // نرسل طلباً صغيراً جداً ومستقلاً فقط لتوليد العنوان
            $prompt = "Generate a very short title (max 3 words) in the same language for this message: '$firstMessage'. Return ONLY the title text.";
            $smartTitle = $this->gemini->askGemini($prompt);
            
            // تنظيف النص من أي علامات ترقيم زائدة
            return trim(str_replace(['"', "'", '*', '-', '.'], '', $smartTitle));
        } catch (\Exception $e) {
            // في حال فشل الذكاء الاصطناعي نعود للقص التلقائي كخطة بديلة
            return Str::limit($firstMessage, 30);
        }
    }

    /**
     * معالجة إرسال الرسائل (الشات الأساسي)
     */
    public function handleChat(Request $request)
    {
        try {
            $message = $request->input('message') ?? 'Analyze context.';
            $lectureId = $request->input('lecture_id');
            $conversationId = $request->input('conversation_id');
            $reply = "";

            // --- [إدارة سجل المحادثة] ---
            if (!$conversationId) {
                $title = $this->generateSmartTitle($message);

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
                'content' => $message
            ]);

            // --- [منطق الذكاء الاصطناعي] ---
            
            // 1. حالة وجود مرفق
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $imageData = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();
                $reply = $this->gemini->askGeminiWithImage($message, $imageData, $mimeType);
            } 
            
            // 2. حالة السياق الأكاديمي (هنا تم التعديل لحل مشكلة الـ Request too large)
            else if ($lectureId) {
                $currentLecture = Lecture::find($lectureId);

                if ($currentLecture && $currentLecture->subject_id) {
                    $lectureTexts = LectureFile::whereHas('lecture', function($query) use ($currentLecture) {
                                        $query->where('subject_id', $currentLecture->subject_id);
                                    })
                                    ->whereNotNull('extracted_text')
                                    ->where('extracted_text', '!=', 'لم تبدأ عملية الاستخراج بعد')
                                    ->pluck('extracted_text')
                                    ->implode("\n\n---\n\n");
                } else {
                    $lectureTexts = LectureFile::where('lecture_id', $lectureId)
                                    ->whereNotNull('extracted_text')
                                    ->where('extracted_text', '!=', 'لم تبدأ عملية الاستخراج بعد')
                                    ->pluck('extracted_text')
                                    ->implode("\n\n---\n\n");
                }

                if (!empty($lectureTexts)) {
                    // التعديل: قص نصوص المحاضرات لضمان عدم تجاوز الـ 6000 توكن
                    $clippedLectureTexts = Str::limit($lectureTexts, 4000, '...'); 

                   $systemInstruction = "You are 'Veloria AI', the professional and friendly Academic Assistant for the UniSmart platform.
Your core purpose is to assist based on these materials: \n\n" . $clippedLectureTexts . " \n\n
PERSONALITY & TONE:
- Be helpful, supportive, and natural in your conversation.
- If the user greets you or asks how you are, respond with a friendly and positive tone in Arabic (Levantine/Syrian touch is welcomed).
- Avoid robotic or overly formal 'machine-translated' phrases.

STRICT OPERATIONAL RULES:
1. LANGUAGE: Always respond in the language used by the student.
2. INTERACTIVE QUIZ REQUESTS: Return ONLY raw JSON array [{\"question\":\"...\",...}]. No markdown.
3. GENERAL QUESTIONS: Respond with normal text.";

                    $userPrompt = "\n\nStudent Query: " . $message . "\n\nResponse:";
                    $reply = $this->gemini->askGemini($systemInstruction . $userPrompt);
                } else {
                    $reply = $this->gemini->askGemini($message . " (Note: No academic content available for this subject yet.)");
                }
            } 
            
            // 3. الدردشة العامة
            else {
                $reply = $this->gemini->askGemini($message);
            }

            $finalReply = is_string($reply) ? $reply : 'No response from AI.';
            
            ChatMessage::create([
                'conversation_id' => $conversationId,
                'role' => 'assistant',
                'content' => $finalReply
            ]);

            return response()->json([
                'reply' => $finalReply,
                'conversation_id' => $conversationId
            ]);

        } catch (\Exception $e) {
            Log::error('Chat Error: ' . $e->getMessage());
            return response()->json(['reply' => 'خطأ في النظام: ' . $e->getMessage()], 500);
        }
    }
}