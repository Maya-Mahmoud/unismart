<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected $apiKey;
    // الرابط الخاص بـ Groq
    protected $baseUrl = "https://api.groq.com/openai/v1/chat/completions";

    public function __construct()
    {
        // تأكدي أن المفتاح في ملف الـ .env يبدأ بـ gsk_
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function askGemini($prompt)
    {
        $url = $this->baseUrl;

        $payload = [
            // تغيير الموديل لموديل "خفيف" وذو حدود واسعة (Rate Limit Friendly)
            'model' => 'llama-3.1-8b-instant', 
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are Veloria, a university assistant. Answer directly and concisely."
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 500, 
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
                'connect_timeout' => 5,
                'timeout' => 20, 
            ])->post($url, $payload);

            $data = $response->json();

            if ($response->failed()) {
                // عرض رسالة خطأ واضحة في حال تجاوز الحدود مرة أخرى
                return "تنبيه من سيرفر Groq: " . ($data['error']['message'] ?? 'فشل الاتصال');
            }

            if (isset($data['choices'][0]['message']['content'])) {
                return $data['choices'][0]['message']['content'];
            }

            return 'عذراً، لم أستطع تكوين الرد.';

        } catch (\Exception $e) {
            return "تأخر الاتصال، يرجى المحاولة مرة أخرى.";
        }
    }
}