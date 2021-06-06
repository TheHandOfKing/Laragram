<div class="row comments mt-5">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
          <h3>Your Comment</h3>
        </div>

        <hr>

        {!! Form::open(['route' => ['posts.comments.store', $post->id]]) !!}
          <div class="form-group">
            {!! Form::textarea('body', null, ['class' => 'form-control ' . ($errors->has("body") ? "is-invalid" : "")]) !!}
            @if ($errors->has('body'))
                <div class="invalid-feedback">
                  <strong>{{$errors->first('body')}}</strong>
                </div>
            @endif
          </div>
          <div class="form-group">
            {!! Form::submit('Submit', ['class' => 'btn btn-lg btn-outline-primary']) !!}
          </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>