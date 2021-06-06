@extends('layouts.app')

@section('content')
<div class="container">
    <post :user="{{ Auth::user() }}" :post="{{ $post }}"></post>
    <comments :user="{{ Auth::user() }}" :post=" {{ $post }}"></comments>
</div>
@endsection
