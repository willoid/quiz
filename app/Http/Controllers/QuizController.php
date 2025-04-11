<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

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
            return redirect()->route('results')->with('score', $score);
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
    { $selected = $request->input('selected_answer');
        $correct = $request->input('correct_answer');

        $isCorrect = $selected === $correct;

        if ($isCorrect) {
            $currentScore = Session::get('score', 0);
            Session::put('score', $currentScore + 1);
        }

        // Increment question number
        $questionNumber = Session::get('questionNumber', 1);
        Session::put('questionNumber', $questionNumber + 1);

        // Determine if it's the last question
        $isLastQuestion = $questionNumber >= 5;

        return view('feedback', [
            'isCorrect' => $isCorrect,
            'correctAnswer' => $correct,
            'isLastQuestion' => $isLastQuestion
        ]);
    }
}
