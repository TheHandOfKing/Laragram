@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row comments mt-5">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="card-title">
            <h1>Edit comment for post: {{ $post->title }}</h1>
          </div>
  
          <hr>
  
          {!! Form::open(['route' => ['posts.comments.update', [$post->id, $comment->id]], 'method' => 'patch']) !!}
            <div class="form-group">
              {!! Form::textarea('body', old('body', $comment->body), ['class' => 'form-control ' . ($errors->has("body") ? "is-invalid" : "")]) !!}
              @if ($errors->has('body'))
                  <div class="invalid-feedback">
                    <strong>{{$errors->first('body')}}</strong>
                  </div>
              @endif
            </div>
            <div class="form-group">
              {!! Form::submit('Update', ['class' => 'btn btn-lg btn-outline-primary']) !!}
            </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection