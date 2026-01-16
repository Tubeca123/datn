<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    public function askAdmin(Request $request)
    {
        $question = $request->input('question');

        $response = Http::timeout(120)->post('http://127.0.0.1:5003/ask-ai-admin', [
            'question' => $question
        ]);

        if ($response->failed()) {
            Log::error('Flask API Error: ' . $response->body());
            return response()->json(['answer' => 'Không có phản hồi từ AI']);
        }

        $answer = $response->json()['answer'] ?? 'Không có phản hồi';
        $chart = $response->json()['chart'] ?? null;

        Log::info('Flask API Response: ' . $answer);
        Log::info('Flask API Response: ' . json_encode($response->json(), JSON_UNESCAPED_UNICODE));

        $formattedAnswer = nl2br(htmlentities($answer));

        return response()->json(['question' => $question, 'answer' => $formattedAnswer, 'chart' => $chart]);
    }
}
