@extends('layouts.app')

@section('content')
    <user-dashboard :posts="{{ json_encode($posts, true) }}" :user="{{ Auth::user() }}" :favorites="{{ json_encode($favorites, true) }}"></user-dashboard>
@endsection
