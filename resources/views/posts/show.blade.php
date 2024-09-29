@extends('layouts.app')

@section('content')
<div class="container">
  <h2>{{ $post->title }}</h2>
  <p>{{ $post->content }}</p>

  <p><strong>Posted by:</strong> {{ $post->user->name }}</p>

  <!-- Edit and Delete post options -->
  @if (auth()->check() && auth()->user()->id === $post->user_id)
  <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">Edit Post</a>

  <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">Delete Post</button>
  </form>
  @endif

  <hr>
  <h4>Comments</h4>
  @foreach($post->comments as $comment)
  <div class="comment">
    <p>{{ $comment->content }}</p>
    <p><strong>Commented by:</strong> {{ $comment->user->name }}</p>

    <!-- Edit and Delete comment options -->
    @if (auth()->check() && auth()->user()->id === $comment->user_id)
    <a href="{{ route('comments.edit', [$post->id, $comment->id]) }}" class="btn btn-primary">Edit Comment</a>

    <form action="{{ route('comments.destroy', [$post->id, $comment->id]) }}" method="POST" style="display:inline;">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger">Delete Comment</button>
    </form>
    @endif
  </div>
  @endforeach

  <hr>
  <h4>Add a Comment</h4>
  <form method="POST" action="{{ route('comments.store', $post->id) }}">
    @csrf

    <div class="form-group">
      <label for="content">Your Comment:</label>
      <textarea id="content" name="content" class="form-control" rows="3" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Add Comment</button>
  </form>
</div>
@endsection