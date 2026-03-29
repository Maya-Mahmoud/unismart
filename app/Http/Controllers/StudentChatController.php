<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeminiService; 
use Illuminate\Support\Facades\Log;
use App\Models\ChatMessage;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use App\Models\LectureFile;
use Illuminate\Support\Str;

class StudentChatController extends Controller
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function index()
    {
        $user = Auth::user();
        $student = $user->student;
        
        $subjects = Subject::where('department_id', $student->department_id)
            ->where('year', $student->year)
            ->where('semester', $student->semester)
            ->get();

        return view('student.chat', compact('student', 'subjects'));
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

    private function generateSmartTitle($firstMessage)
    {
        try {
            $prompt = "Generate a very short title (max 3 words) for this: '$firstMessage'. Return ONLY the title text.";
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
            $selectedFileIds = $request->input('file_ids', []); 

            $isAutoAnalysis = (!$userMessage && !empty($selectedFileIds));

            if ($isAutoAnalysis) {
                $userMessage = "SYSTEM_COMMAND: Analyze these files in English.";
            }

            if (!$userMessage) {
                return response()->json(['reply' => 'Please enter a message.'], 400);
            }

            $lectureContent = "";
            $sourceFilesNames = [];

            if (!empty($selectedFileIds)) {
                $files = LectureFile::whereIn('id', $selectedFileIds)
                                    ->whereNotNull('extracted_text')
                                    ->get();

                foreach ($files as $file) {
                    $lectureContent .= "\n[File: {$file->file_name}]\n" . $file->extracted_text . "\n";
                    $sourceFilesNames[] = $file->file_name;
                }
            }

            // لضبط اللغة
            $hasArabic = preg_match('/\p{Arabic}/u', $userMessage);
            $instruction = (!$hasArabic || str_contains($userMessage, 'SYSTEM_COMMAND')) 
                ? "Respond in ENGLISH ONLY. No Arabic." 
                : "Respond in Arabic.";

            $limitedContext = Str::limit($lectureContent, 6000);
            $finalPrompt = "INSTRUCTION: $instruction\n\nCONTEXT: $limitedContext\n\nUSER: $userMessage";

            $reply = $this->gemini->askGemini($finalPrompt);

            // --- تصحيح منطق المحادثة والـ Sidebar ---
            if (!$conversationId) {
                // إذا لم يرسل الـ Frontend آي دي، ننشئ واحدة جديدة
                $title = $this->generateSmartTitle($userMessage);
                $conversation = Conversation::create([
                    'user_id' => auth()->id(),
                    'title' => $title
                ]);
                $conversationId = $conversation->id;
            } else {
                // إذا كانت موجودة، نحدث الوقت لتظهر أول القائمة
                $existingConv = Conversation::find($conversationId);
                if ($existingConv) {
                    $existingConv->touch(); 
                }
            }

            $displayMessage = $isAutoAnalysis ? "Analyzing the selected files..." : $userMessage;

            // حفظ الرسائل
            ChatMessage::create(['conversation_id' => $conversationId, 'role' => 'user', 'content' => $displayMessage]);
            ChatMessage::create(['conversation_id' => $conversationId, 'role' => 'assistant', 'content' => $reply]);

            return response()->json([
                'reply' => $reply,
                'conversation_id' => $conversationId,
                'source_files' => $sourceFilesNames
            ]);

        } catch (\Exception $e) {
            Log::error('Veloria Error: ' . $e->getMessage());
            return response()->json(['reply' => 'Technical Error: ' . $e->getMessage()], 500);
        }
    }
}