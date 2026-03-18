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

            // 1. Handling Direct File Attachments
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $imageData = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();
                $reply = $this->gemini->askGeminiWithImage($message, $imageData, $mimeType);
            } 
            
            // 2. Academic Context Case (Lectures & Subjects)
            else if ($lectureId) {
                $currentLecture = Lecture::find($lectureId);

                if ($currentLecture && $currentLecture->subject_id) {
                    // Fetching all extracted texts for the entire subject
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
                    // Refined System Instructions for Veloria AI
                    $systemInstruction = "You are 'Veloria AI', the professional Academic Assistant for the UniSmart platform. 
Your core purpose is to assist based on these materials: \n\n" . $lectureTexts . " \n\n

STRICT OPERATIONAL RULES:
1. LANGUAGE: Always respond in the language used by the student.

2. INTERACTIVE QUIZ REQUESTS: 
   - If asked for a 'quiz', 'test', or 'exam', return ONLY a raw JSON array.
   - FORMAT: [{\"question\":\"...\",\"options\":[\"...\"],\"answer\":\"...\",\"type\":\"mcq\"}]
   - CRITICAL: Do NOT use markdown tags like ```json. Return the raw text starting with [ and ending with ].
   - Ensure the content is academically rigorous and covers the provided materials.

3. GENERAL QUESTIONS: If the user is just chatting or asking questions, respond with normal text/markdown.";
                

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

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            Log::error('Chat Error: ' . $e->getMessage());
            return response()->json(['reply' => 'System Error: ' . $e->getMessage()], 500);
        }
    }
}