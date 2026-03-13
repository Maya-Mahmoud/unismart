<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected $apiKey = "AIzaSyDw4L3gAWcBaRMJ5_w4jLLNeYQdy6rBTVs";
    protected $baseUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent";

    public function askGemini($prompt)
    {
        return $this->makeRequest([
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ]);
    }

    // الدالة الجديدة لدعم الصور والملفات
    public function askGeminiWithImage($prompt, $base64Data, $mimeType)
    {
        return $this->makeRequest([
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $base64Data
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }

    protected function makeRequest($payload)
    {
        $url = $this->baseUrl . "?key=" . $this->apiKey;

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            $data = $response->json();

            if ($response->failed()) {
                return "خطأ من Google: " . ($data['error']['message'] ?? 'فشل الاتصال');
            }

            return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'لم يتم استلام رد محتوى';

        } catch (\Exception $e) {
            return "خطأ بالنظام: " . $e->getMessage();
        }
    }
}