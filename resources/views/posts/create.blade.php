@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Create a New Post</h2>
  <form method="POST" action="{{ route('posts.store') }}">
    @csrf
    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" name="title" id="title" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="content">Content</label>
      <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Create Post</button>
  </form>
</div>
@endsection