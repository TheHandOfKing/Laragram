<div style="max-width: 500px;justify-content: center;margin: 0 auto;" class="media post d-flex flex-column mb-5 border-bottom">
  <div class="d-flex flex-column counters" style="width: 100%">
    <h3 class="mt-0"><a href="{{ $post->url }}">{{ $post->name }}</a></h3>
    <div class="image border">
      <a href="{{ $post->url }}">
      <img src="{{ $post->images }}" style="width: 100%; object-fit: contain; height: 300px;" alt="">
      </a>
    </div>
    <div class="like d-flex mt-2">
      <strong>{{ $post->likes_count }}</strong> {{'likes'}}
      <strong class="ml-4">{{ $post->comments_count }}</strong> {{'comments'}}
      <strong class="ml-4">{{$post->views}}</strong>{{ ' views'}}
    </div>
  </div>
  <div class="media-body">
    <div class="d-flex align-items-center">
      <div class="ml-auto d-flex">
        @can ('update', $post)
          <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-outline-info mr-3">Edit</a>
        @endcan

        @can ('delete', $post)
        {!! Form::open(['route' => ['posts.destroy', $post->id], 'method' => 'delete', 'class' => 'form-delete']) !!}
          {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-outline-danger', 'onclick' => 'return confirm("Are you sure?")']) !!}
        {!! Form::close() !!}
        @endcan
      </div>
    </div>
    <small class="text-muted">{{ $post->created_date }}</small>
    <div class="excerpt mb-3">
      {{$post->user->name}}: {{ $post->excerpt(200) }}
    </div>
  </div>
</div>