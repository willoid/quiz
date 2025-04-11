@extends('layouts.app')
@section('content')
    @php
        $question = html_entity_decode($data['results'][0]['question']);
        $correct = html_entity_decode($data['results'][0]['correct_answer']);
        $incorrect = array_map('html_entity_decode', $data['results'][0]['incorrect_answers']);

        $allAnswers = $incorrect;
        $allAnswers[] = $correct;
        shuffle($allAnswers); // randomize the order
    @endphp

    <h2 class="text-xl font-semibold mb-4">{{ $question }}</h2>

    <form action="{{ route('submitAnswer') }}" method="POST">
        @csrf

        @foreach ($allAnswers as $answer)
            <label class="block p-2 border rounded-lg mb-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                <input type="radio" name="selected_answer" value="{{ $answer }}" class="mr-2" required>
                {{ $answer }}
            </label>
        @endforeach

        <input type="hidden" name="correct_answer" value="{{ $correct }}">

        <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Submit
        </button>
    </form>

@endsection
