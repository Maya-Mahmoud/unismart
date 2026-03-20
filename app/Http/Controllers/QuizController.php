<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function saveResult(Request $request) {
    \App\Models\QuizResult::updateOrCreate(
        [
            'user_id' => auth()->id(),
            'lecture_file_id' => $request->file_id
        ],
        [
            'score' => $request->score,
            'correct_answers' => $request->correct,
            'total_questions' => $request->total
        ]
    );

    return response()->json(['success' => true]);
}

}
