<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeminiService; // تأكد من هذا السطر تماماً

class ChatController extends Controller
{
    protected $gemini;

    // تأكد من وجود شرطتين سفليتين __ وليس واحدة _
    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function handleChat(Request $request)
{
    try {
        $message = $request->input('message');
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            
            // تحويل الصورة لـ Base64 عشان يفهمها الذكاء الاصطناعي
            $imageData = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getMimeType();

            // استدعاء الدالة اللي عرفناها بالسيرفس فوق
            $reply = $this->gemini->askGeminiWithImage($message, $imageData, $mimeType);
        } else {
            $reply = $this->gemini->askGemini($message);
        }

        return response()->json(['reply' => $reply]);
    } catch (\Exception $e) {
        // هاد السطر رح يحكي لنا شو المشكلة بالضبط لو صار فشل
        return response()->json(['reply' => 'System Error: ' . $e->getMessage()], 500);
    }
}    }
