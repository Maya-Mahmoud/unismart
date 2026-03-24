<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudentChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');
        
        // API key from services configuration
        $apiKey = config('services.ai.key'); 

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo', // Or your preferred model
            'messages' => [
                [
                    'role' => 'system', 
                    'content' => 'You are an academic assistant for the UniSmart platform. Help the student with schedules and explanations, but do not generate quizzes for them.'
                ],
                ['role' => 'user', 'content' => $userMessage],
            ],
        ]);

        return response()->json([
            'reply' => $response->json()['choices'][0]['message']['content']
        ]);
    }
}