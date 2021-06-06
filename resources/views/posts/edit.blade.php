@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <div class="d-flex align-items-center">
                    <h3>Edit a Post</h3>
                    <div class="ml-auto">
                      <a class="btn btn-outline-secondary" href="{{ route('posts.index') }}">Back to all Posts</a>
                    </div>
                  </div>
                </div>

                <div class="card-body">
                  {!! Form::open(['route' => ['posts.update', $post->id], 'method' => 'put', 'files' => true]) !!}
                    @include('posts.form', ['btnText' => 'Edit a Post'])
                  {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
