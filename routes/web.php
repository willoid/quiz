<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;


Route::get('/', [QuizController::class, 'quiz'])->name('quiz');
Route::get('/results', function () {
    $score = session('score');
    return view('results', ['score' => $score]);
})->name('results');
Route::get('/reset', function () {
    Session::flush(); // Clears all session data
    return redirect()->route('quiz'); // Redirects to the quiz start page
})->name('reset');

Route::post('/submit-answer', [QuizController::class, 'submitAnswer'])->name('submitAnswer');
