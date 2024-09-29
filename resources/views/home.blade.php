@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Latest Posts Section -->
            <div class="card">
                <div class="card-header">{{ __('Latest Posts') }}</div>

                <div class="card-body">
                    @if($posts->isEmpty())
                    <p>{{ __('No posts available.') }}</p>
                    @else
                    @foreach($posts as $post)
                    <div class="post mb-4">
                        <h5><a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a></h5>
                        <p>{{ Str::limit($post->content, 150) }}</p>
                        <small>Posted by {{ $post->user->name }} on {{ $post->created_at->format('F j, Y') }}</small>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection