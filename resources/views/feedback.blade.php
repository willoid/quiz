@extends('layouts.app')

@section('content')
    <div class="container text-center mt-5">
        @if ($isCorrect)
            <div class="alert alert-success">
                🎉 Correct! Well done!
            </div>
        @else
            <div class="alert alert-danger">
                ❌ Incorrect. The correct answer was: <strong>{{ $correctAnswer }}</strong>
            </div>
        @endif

        <div id="next-button" style="display: none;" class="mt-4">
            @if ($isLastQuestion)
                <form action="{{ route('results') }}" method="GET">
                    <button type="submit" class="btn btn-primary">View Results</button>
                </form>
            @else
                <form action="{{ route('quiz') }}" method="GET">
                    <button type="submit" class="btn btn-primary">Next Question</button>
                </form>
            @endif
        </div>
    </div>

    <script>
        // Show the next button after a delay
        setTimeout(function() {
            document.getElementById('next-button').style.display = 'block';
        }, 3000); // 3 seconds
    </script>
@endsection
