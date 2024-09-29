@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <!-- Welcome Section -->
      <div class="card">
        <div class="card-header">{{ __('Welcome to the Project') }}</div>

        <div class="card-body">
          <!-- Display README content (Markdown rendered as HTML) -->
          <div class="mb-4">
            {!! $readme !!}
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection