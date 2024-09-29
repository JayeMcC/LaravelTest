@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Edit Post</h2>

  <form action="{{ route('posts.update', $post->id) }}" method="POST">
    @csrf
    @method('PATCH')

    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
    </div>

    <div class="form-group">
      <label for="content">Content</label>
      <textarea id="content" name="content" class="form-control" rows="5" required>{{ old('content', $post->content) }}</textarea>
    </div>

    <button type="submit" class="btn btn-success">Update Post</button>
  </form>
</div>
@endsection