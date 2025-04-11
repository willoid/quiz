@extends('layouts.app')
@section('content')
    <h2>Your score: {{ session('score') }} / 5</h2>
    <a href="{{ route('reset') }}" class="btn btn-danger mt-3">Restart Quiz</a>
@endsection
