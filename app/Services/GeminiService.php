<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl = "https://api.groq.com/openai/v1/chat/completions";

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function askGemini($prompt)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
                'timeout' => 60, 
            ])->post($this->baseUrl, [
                'model' => 'llama-3.3-70b-versatile', // الموديل الأقوى اللي بيتحمل 15 سؤال
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are Veloria, an academic assistant. Generate 15 multiple-choice questions in English based on the text. Format them clearly for PDF export."
                    ],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.3,
                'max_tokens' => 2000, 
            ]);

            $data = $response->json();

            if (isset($data['choices'][0]['message']['content'])) {
                return $data['choices'][0]['message']['content'];
            }

            return "السيرفر مشغول، جربي مرة تانية.";

        } catch (\Exception $e) {
            return "فشل الاتصال: " . $e->getMessage();
        }
    }
}