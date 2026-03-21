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
     * جلب قائمة المحادثات الخاصة بالمستخدم الحالي (للسجل الجانبي)
     */
    public function getConversations()
    {
        $conversations = Conversation::where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')
            ->take(15) // جلب آخر 15 محادثة
            ->get(['id', 'title', 'updated_at']);

        return response()->json($conversations);
    }

    /**
     * جلب رسائل محادثة معينة عند الضغط عليها من السجل
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
    private function generateSmartTitle($firstMessage)
{
    try {
        $prompt = "Generate a very short, professional title (max 4 words) in the same language for this message: '$firstMessage'. Return ONLY the title text.";
        $smartTitle = $this->gemini->askGemini($prompt);
        
        // تنظيف النص الناتج من أي علامات تنصيص أو مسافات زائدة
        return trim(str_replace(['"', "'", '*', '-'], '', $smartTitle));
    } catch (\Exception $e) {
        // في حال فشل الذكاء الاصطناعي، نعود للطريقة التقليدية كخيار احتياطي
        return Str::limit($firstMessage, 40);
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
            // --- [إدارة سجل المحادثة] ---
if (!$conversationId) {
    // توليد عنوان ذكي بدلاً من أخذ الرسالة كما هي
    $smartTitle = $this->generateSmartTitle($message);

    $conversation = Conversation::create([
        'user_id' => auth()->id(),
        'title' => $smartTitle // استخدام العنوان الذكي
    ]);
    $conversationId = $conversation->id;
} else {
    // تحديث وقت المحادثة لتظهر في الأعلى
    Conversation::where('id', $conversationId)->update(['updated_at' => now()]);
}

            // حفظ رسالة المستخدم
            ChatMessage::create([
                'conversation_id' => $conversationId,
                'role' => 'user',
                'content' => $message
            ]);

            // --- [منطق الذكاء الاصطناعي] ---
            
            // 1. حالة وجود مرفق (صورة/ملف)
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $imageData = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();
                $reply = $this->gemini->askGeminiWithImage($message, $imageData, $mimeType);
            } 
            
            // 2. حالة السياق الأكاديمي (محاضرة)
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
                    $systemInstruction = "You are 'Veloria AI', the professional Academic Assistant for the UniSmart platform. 
                    Your core purpose is to assist based on these materials: \n\n" . $lectureTexts . " \n\n
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

            // --- [حفظ رد الذكاء الاصطناعي] ---
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