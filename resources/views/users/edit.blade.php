@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <div class="d-flex align-items-center">
                    <h3>Edit a User</h3>
                    <div class="ml-auto">
                      
                    </div>
                  </div>
                </div>


                <div class="card-body">
                  {!! Form::open(['route' => ['users.update', $user->id], 'method' => 'put', 'files' => true]) !!}
                  <div class="form-group">
                    {!! Form::label('name', 'User name') !!}
                    {!! Form::text('name', old('name', $user->name), ['class' => 'form-control ' . ($errors->has("name") ? "is-invalid" : "") , 'placeholder' => 'user name', 'id' => 'user_name']) !!}
                    @if ($errors->has('name'))
                      <div class="invalid-feedback">
                        <strong>
                          {{ $errors->first('name') }}
                        </strong>
                      </div>
                    @endif
                  </div>
                  <div class="form-group">
                    {!! Form::label('email', 'User email') !!}
                    {!! Form::text('email', old('email', $user->email), ['class' => 'form-control ' . ($errors->has("email") ? "is-invalid" : ""), 'placeholder' => 'User email', 'id' => 'email']) !!}
                    @if ($errors->has('email'))
                      <div class="invalid-feedback">
                        <strong>
                          {{ $errors->first('email') }}
                        </strong>
                      </div>
                    @endif
                  </div>
                  <div class="form-group">
                    {!! Form::label('image', 'User image') !!}
                    {!! Form::file('image', ['class' => 'form-control']); !!}
                  </div>
                  <div class="form-group">
                    {!! Form::hidden('id', old('id', $user->id)) !!}
                    {!! Form::submit('Edit a user', ['class' => 'btn btn-outline-primary btn-lg', 'id' => 'user_submit']) !!}
                  </div>
                  {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
