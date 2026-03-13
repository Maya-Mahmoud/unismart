<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    public function askGemini($prompt)
    {
        $apiKey = "AIzaSyCQ8xDjDCPfvESnBuRqENT_LHDWGma6uCM";
        
        
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . $apiKey;

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);

            $data = $response->json();

            if ($response->failed()) {
                return "النتيجة: " . ($data['error']['message'] ?? 'تحتاج للانتظار قليلاً');
            }

            return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'تم الاتصال لكن الرد فارغ';

        } catch (\Exception $e) {
            return "خطأ بالنظام: " . $e->getMessage();
        }
    }
}