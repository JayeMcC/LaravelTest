@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $user->name }}'s Posts</h2>

    @if ($posts->isEmpty())
    <p>{{ $user->name }} hasn't created any posts yet.</p>
    @else
    <div class="list-group">
        @foreach ($posts as $post)
        <a href="{{ route('posts.show', $post->id) }}" class="list-group-item list-group-item-action">
            <h5 class="mb-1">{{ $post->title }}</h5>
            <p class="mb-1">{{ Str::limit($post->content, 100) }}</p>
            <small>Posted on {{ $post->created_at->format('F j, Y, g:i a') }}</small>
        </a>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection