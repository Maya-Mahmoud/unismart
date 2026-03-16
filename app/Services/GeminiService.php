<?php



namespace App\Services;



use Illuminate\Support\Facades\Http;



class GeminiService

{

    protected $apiKey;

    // الرابط الذي يعمل عندك (Flash Latest)

    protected $baseUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent";



    public function __construct()

    {

        $this->apiKey = env('GEMINI_API_KEY');

    }



    public function askGemini($prompt)

    {

        $url = $this->baseUrl . "?key=" . $this->apiKey;



        $payload = [

            'contents' => [

                [

                    'parts' => [

                        [

                            // وضعنا التعليمات في البداية بأسلوب "أمر نظامي" صارم

                            'text' => "System Instructions: You are Veloria, a university assistant. NEVER start your response with 'I am Veloria' or introduce yourself unless specifically asked. Answer the following question directly and in detail using the user's language: " . $prompt

                        ]

                    ]

                ]

            ],

            'generationConfig' => [

                'temperature' => 0.7,

                'maxOutputTokens' => 2048, // لضمان عدم تقطيع الإجابات الطويلة

                'topP' => 0.9,

            ]

        ];



        try {

            // تقليل الـ Timeouts لجعل النظام يستجيب أو يفشل بسرعة بدل الانتظار الطويل

            $response = Http::withOptions([

                'verify' => false,

                'connect_timeout' => 10, 

                'timeout' => 45, 

            ])->post($url, $payload);



            $data = $response->json();



            if ($response->failed()) {

                return "خطأ: " . ($data['error']['message'] ?? 'فشل الاتصال');

            }



            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {

                return $data['candidates'][0]['content']['parts'][0]['text'];

            }



            return 'عذراً، لم أستطع تكوين الرد.';



        } catch (\Exception $e) {

            return "تأخر الاتصال، يرجى المحاولة مرة أخرى.";

        }

    }

}