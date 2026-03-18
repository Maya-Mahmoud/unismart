<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeminiService;
use App\Models\Lecture;
use App\Models\LectureFile;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    protected $gemini;

    /**
     * تقنية الربط مع خدمة Gemini
     */
    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function handleChat(Request $request)
    {
        try {
            $message = $request->input('message') ?? 'Please analyze the context.';
            $lectureId = $request->input('lecture_id');

            // 1. حالة وجود ملف مرفق مباشر (صورة أو PDF مرفوع في الشات حالياً)
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $imageData = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();
                $reply = $this->gemini->askGeminiWithImage($message, $imageData, $mimeType);
            } 
            
            // 2. حالة السؤال عن المحاضرة (تعديل UniSmart: البحث بنطاق المادة كاملة)
            else if ($lectureId) {
                // جلب بيانات المحاضرة الحالية لمعرفة المادة التابعة لها
                $currentLecture = Lecture::find($lectureId);

                if ($currentLecture && $currentLecture->subject_id) {
                    // جلب كافة النصوص المستخرجة من جميع المحاضرات التي تنتمي لنفس المادة
                    // هذا يضمن أن النظام "يرى" الملفات المرفوعة في محاضرة (أ) وهو في واجهة محاضرة (ب)
                    $lectureTexts = LectureFile::whereHas('lecture', function($query) use ($currentLecture) {
                                            $query->where('subject_id', $currentLecture->subject_id);
                                        })
                                        ->whereNotNull('extracted_text')
                                        ->where('extracted_text', '!=', 'لم تبدأ عملية الاستخراج بعد')
                                        ->pluck('extracted_text')
                                        ->implode("\n\n---\n\n");
                } else {
                    // fallback: في حال فشل الوصول للمادة، نبحث في المحاضرة الحالية فقط
                    $lectureTexts = LectureFile::where('lecture_id', $lectureId)
                                        ->whereNotNull('extracted_text')
                                        ->pluck('extracted_text')
                                        ->implode("\n\n---\n\n");
                }

                if (!empty($lectureTexts)) {
                    // إرسال السياق الكامل (Context) للذكاء الاصطناعي
                    $systemInstruction = "أنت مساعد أكاديمي ذكي في منصة UniSmart. إليك المحتوى العلمي المستخرج من ملفات المادة:\n\n" . $lectureTexts;
                    $userPrompt = "\n\nسؤال الطالب: " . $message . "\n\nبناءً على المحتوى أعلاه، أجب بدقة وباللغة العربية.";
                    
                    $reply = $this->gemini->askGemini($systemInstruction . $userPrompt);
                } else {
                    // رسالة تنبيه في حال عدم وجود نصوص مستخرجة للمادة نهائياً
                    $reply = $this->gemini->askGemini($message . " (تنبيه: لا يوجد محتوى علمي مستخرج متاح لهذه المادة في قاعدة البيانات حالياً)");
                }
            } 
            
            // 3. حالة الدردشة العامة (بدون سياق محاضرة)
            else {
                $reply = $this->gemini->askGemini($message);
            }

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            Log::error('Chat Error: ' . $e->getMessage());
            return response()->json(['reply' => 'System Error: ' . $e->getMessage()], 500);
        }
    }
}