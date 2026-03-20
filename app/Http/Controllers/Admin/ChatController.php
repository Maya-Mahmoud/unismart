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

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function handleChat(Request $request)
    {
        try {
            $message = $request->input('message') ?? 'Analyze context.';
            $lectureId = $request->input('lecture_id');
            $reply = "";

            // 1. Handling Direct File Attachments
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $imageData = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();
                $reply = $this->gemini->askGeminiWithImage($message, $imageData, $mimeType);
            } 
            
            // 2. Academic Context Case
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
            
            // 3. General Chat Case
            else {
                $reply = $this->gemini->askGemini($message);
            }

            // التأكد أن الرد نصي قبل الإرسال لـ JSON
            return response()->json(['reply' => is_string($reply) ? $reply : 'No response from AI.']);

        } catch (\Exception $e) {
            Log::error('Chat Error: ' . $e->getMessage());
            return response()->json(['reply' => 'خطأ في النظام: ' . $e->getMessage()], 500);
        }
    }
}