<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class QuizController extends Controller
{
    public function quiz(Request $request)
    {
        $sessionToken = Session::get('session_token', $this->createSessionToken());
        Session::put('session_token', $sessionToken);

        $questionNumber = Session::get('questionNumber', 1);
        $score = Session::get('score', 0);

        $quizData = $this->fetchQuizQuestions($sessionToken);

        return view('quiz', [
            'quizData' => $quizData,
            'questionNumber' => $questionNumber,
            'score' => $score
        ]);
    }

    public function submitAnswer(Request $request)
    {
        $request->validate([
            'selected_answer' => 'required',
            'correct_answer' => 'required',
        ]);

        $selected = $request->input('selected_answer');
        $correct = $request->input('correct_answer');
        $isCorrect = $selected === $correct;

        if ($isCorrect) {
            $currentScore = Session::get('score', 0);
            Session::put('score', $currentScore + 1);
        }

        $questionNumber = Session::get('questionNumber', 1);
        Session::put('questionNumber', $questionNumber + 1);

        $isLastQuestion = $questionNumber >= 5;

        return view('feedback', [
            'isCorrect' => $isCorrect,
            'correctAnswer' => $correct,
            'isLastQuestion' => $isLastQuestion
        ]);
    }

    private function createSessionToken()
    {
        // Request a new session token from the API
        $response = Http::get('https://opentdb.com/api_token.php?command=request');
        $data = $response->json();
        return $data['token'] ?? null;
    }

    private function fetchQuizQuestions($sessionToken)
    {
        $response = Http::get("https://opentdb.com/api.php?amount=1&category=12&difficulty=medium&type=multiple&token={$sessionToken}");
        return $response->json();
    }
}
