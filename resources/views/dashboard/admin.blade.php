@extends('layouts.app')

@section('content')
    <admin :posts="{{ json_encode($posts, true) }}" :user="{{ Auth::user() }}" :users="{{ json_encode($users, true) }}" :favorites="{{ json_encode($favorites, true) }}"></admin>
@endsection
