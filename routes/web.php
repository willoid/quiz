<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;


Route::get('/', [QuizController::class, 'quiz'])->name('quiz');
Route::post('/submit-answer', [QuizController::class, 'submitAnswer'])->name('submitAnswer');
Route::get('/results', function () {
    $score = session('score');
    return view('results', ['score' => $score]);
})->name('results');
