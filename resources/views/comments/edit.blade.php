@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Edit Comment</h2>

  <form action="{{ route('comments.update', [$post->id, $comment->id]) }}" method="POST">
    @csrf
    @method('PATCH')

    <div class="form-group">
      <label for="content">Your Comment</label>
      <textarea id="content" name="content" class="form-control" rows="3" required>{{ old('content', $comment->content) }}</textarea>
    </div>

    <button type="submit" class="btn btn-success">Update Comment</button>
  </form>
</div>
@endsection