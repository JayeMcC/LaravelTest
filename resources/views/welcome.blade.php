@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <div class="jumbotron text-center">
    <h1>Welcome to Laravel Blog</h1>
    <p class="lead">A simple blog application built with Laravel. Share your thoughts, comment, and interact with others.</p>
    @guest
    <p>
      <a class="btn btn-primary btn-lg" href="{{ route('login') }}" role="button">Login</a>
      <a class="btn btn-secondary btn-lg" href="{{ route('register') }}" role="button">Register</a>
    </p>
    @else
    <p>
      <a class="btn btn-primary btn-lg" href="{{ route('posts.index') }}" role="button">View Posts</a>
      <a class="btn btn-secondary btn-lg" href="{{ route('posts.create') }}" role="button">Create Post</a>
    </p>
    @endguest
  </div>

  <div class="row">
    <div class="col-md-12 text-center">
      <h2>Latest Posts</h2>
      <ul class="list-unstyled">
        @foreach ($posts as $post)
        <li>
          <h4><a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a></h4>
          <p>{{ Str::limit($post->content, 100) }} <a href="{{ route('posts.show', $post->id) }}">Read more</a></p>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
@endsection