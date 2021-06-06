@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card-header">
          <div class="d-flex align-items-center">
            <h3>All Posts</h3>
            <div class="ml-auto">
              <a class="btn btn-outline-secondary" href="{{ route('posts.create') }}">Create a Post</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          @include('layouts.messages')

          @forelse ($posts as $post)
            @include('posts.excerpt')
          @empty
              <div class="alert alert-warning">
                <strong>Sorry</strong>, no posts are available at this moment
              </div>
          @endforelse
          <div class="justify-content-center row">
            {{ $posts->links("pagination::bootstrap-4") }}
          </div>
        </div>
      </div>
  </div>
</div>
@endsection