<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [QuizController::class, 'quiz'])->name('quiz');

Route::get('/results', [QuizController::class, 'results'])->name('results');
