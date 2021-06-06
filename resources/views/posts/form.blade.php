<div class="form-group">
  {!! Form::label('name', 'post name') !!}
  {!! Form::text('name', old('name', $post->name), ['class' => 'form-control ' . ($errors->has("name") ? "is-invalid" : "") , 'placeholder' => 'post name', 'id' => 'post_name']) !!}
  @if ($errors->has('name'))
    <div class="invalid-feedback">
      <strong>
        {{ $errors->first('name') }}
      </strong>
    </div>
  @endif
</div>
<div class="form-group">
  {!! Form::label('description', 'post description') !!}
  {!! Form::textarea('description', old('description', $post->description), ['class' => 'form-control ' . ($errors->has("description") ? "is-invalid" : ""), 'placeholder' => 'Explain Your post', 'id' => 'post_description']) !!}
  @if ($errors->has('description'))
    <div class="invalid-feedback">
      <strong>
        {{ $errors->first('description') }}
      </strong>
    </div>
  @endif
</div>
<div class="form-group">
  {!! Form::label('location', 'Where did you take this picture?') !!}
  {!! Form::text('location', old('location', $post->location), ['class' => 'form-control']); !!}
</div>
<div class="form-group">
  {!! Form::label('images[]', 'Post image') !!}
  {!! Form::file('images[]', ['multiple' => 'true', 'class' => 'form-control']); !!}
</div>
<div class="form-group">
  {!! Form::submit($btnText, ['class' => 'btn btn-outline-primary btn-lg', 'id' => 'post_submit']) !!}
</div>