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

       

        // التحقق من وجود ملف مرفق (صورة أو PDF)

        if ($request->hasFile('attachment')) {

    $file = $request->file('attachment');

    $imageData = base64_encode(file_get_contents($file->getRealPath()));

    $mimeType = $file->getMimeType();

    $reply = $this->gemini->askGeminiWithImage($message, $imageData, $mimeType);

        } else {

            // لو كان الطلب نصياً فقط

            $reply = $this->gemini->askGemini($message);

        }



        return response()->json(['reply' => $reply]);



    } catch (\Exception $e) {

        return response()->json(['reply' => 'System Error: ' . $e->getMessage()], 500);

    }
}    }