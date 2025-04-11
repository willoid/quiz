@extends('layouts.app')
@section('content')
    <div class="quiz-container">
        @if(isset($quizData['results']) && count($quizData['results']) > 0)
            @foreach ($quizData['results'] as $index => $questionData)
                @php
                    $question = html_entity_decode($questionData['question']);
                    $correctAnswer = html_entity_decode($questionData['correct_answer']);
                    $incorrectAnswers = array_map('html_entity_decode', $questionData['incorrect_answers']);
                    $allAnswers = array_merge($incorrectAnswers, [$correctAnswer]);
                    shuffle($allAnswers);
                @endphp

                <div class="question-section" id="question-{{ $index + 1 }}">
                    <h2 class="text-xl font-semibold mb-4">{{ $question }}</h2>

                    <form action="{{ route('submitAnswer') }}" method="POST">
                        @csrf
                        @foreach ($allAnswers as $answer)
                            <label class="block p-2 border rounded-lg mb-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                                <input type="radio" name="selected_answer" value="{{ $answer }}" class="mr-2" required>
                                {{ $answer }}
                            </label>
                        @endforeach

                        <input type="hidden" name="correct_answer" value="{{ $correctAnswer }}">
                        <input type="hidden" name="question_index" value="{{ $index }}">

                        <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Submit
                        </button>
                    </form>

                    @if (session('feedback') && session('feedback')['question_index'] == $index)
                        <div class="feedback mt-4">
                            @if (session('feedback')['is_correct'])
                                <p class="text-green-500">Correct! Well done.</p>
                            @else
                                <p class="text-red-500">Incorrect. The correct answer was: {{ session('feedback')['correct_answer'] }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <p>No quiz data available at this time. Please try again later.</p>
        @endif

        @if (session('quiz_completed'))
            <div class="quiz-results mt-8">
                <h3 class="text-lg font-semibold">Quiz Completed!</h3>
                <p>Your Score: {{ session('score') }} out of 5</p>
                <a href="{{ route('quiz') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Restart Quiz
                </a>
            </div>
        @endif
    </div>
@endsection
