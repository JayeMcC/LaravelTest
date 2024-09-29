@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 offset-md-2">
      <div class="card">
        <div class="card-header">
          <h2>User Profile</h2>
        </div>

        <div class="card-body">
          <h4>{{ $user->name }}</h4>
          <p><strong>Email:</strong> {{ $user->email }}</p>
          @if ($user->created_at)
          <p><strong>Member Since:</strong> {{ $user->created_at->format('F d, Y') }}</p>
          @endif

        </div>

        <div class="card-footer">
          <a href="{{ route('users.posts', $user->id) }}" class="btn btn-primary">
            View {{ $user->name }}'s Posts
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection