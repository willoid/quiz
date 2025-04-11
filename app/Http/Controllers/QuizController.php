<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QuizController extends Controller
{
    public function quiz(Request $request)
    {
        if (!Session::has('questionNumber')) {
            Session::put('questionNumber', 1);
            Session::put('score', 0);
        }

        $questionNumber = Session::get('questionNumber');

        if ($questionNumber > 5) {
            $score = Session::get('score');
            // Optionally: Reset session for a new game
            Session::forget('questionNumber');
            Session::forget('score');
            return view('results', ['score' => $score]);
        }

        // Get the next question from the API
        $response = Http::get("https://opentdb.com/api.php?amount=1&category=12&difficulty=medium&type=multiple");

        if ($response->successful()) {
            $quizData = $response->json();
            return view('quiz', [
                'data' => $quizData,
                'questionNumber' => $questionNumber
            ]);
        } else {
            return view('quiz', [
                'data' => null,
                'error' => 'Quizfragen konnten nicht abgerufen werden'
            ]);
        }
    }

    public function submitAnswer(Request $request)
    {
        $correct = $request->input('correct'); // true or false from frontend

        if ($correct) {
            $currentScore = Session::get('score', 0);
            Session::put('score', $currentScore + 1);
        }

        // Increment question number
        $questionNumber = Session::get('questionNumber', 1);
        Session::put('questionNumber', $questionNumber + 1);

        return redirect()->route('/'); // Assuming route is named 'quiz'
    }
}
