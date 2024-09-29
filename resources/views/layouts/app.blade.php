<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

  <!-- Scripts -->
  @viteReactRefresh
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
  <div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
          {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side Of Navbar -->
          <ul class="navbar-nav me-auto">
            <!-- Home Link -->
            <li class="nav-item">
              <a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
            </li>
          </ul>

          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ms-auto">
            <!-- Authentication Links -->
            @guest
            @if (Route::has('login'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @endif

            @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
            @endif
            @else
            <!-- Create Post Link -->
            <li class="nav-item">
              <a class="nav-link" href="{{ route('posts.create') }}">{{ __('Create Post') }}</a>
            </li>

            <!-- My Profile Link -->
            <li class="nav-item">
              <a class="nav-link" href="{{ route('users.show', auth()->user()->id) }}">{{ __('My Profile') }}</a>
            </li>

            <!-- Copy Bearer Token Button -->
            <li class="nav-item">
              <button class="nav-link btn btn-link" id="copy-token-btn" onclick="copyBearerToken()">Copy Bearer Token</button>
            </li>

            <!-- User Dropdown -->
            <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}
              </a>

              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </div>
            </li>
            @endguest
          </ul>
        </div>
      </div>
    </nav>

    <main class="py-4">
      @yield('content')
    </main>
  </div>

  <!-- JavaScript Section -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Get token from server-side session if it exists
      const token = "{{ session('token') }}";

      if (token) {
        localStorage.setItem('token', token);
        console.log('Token stored in localStorage');
      }
    });

    // Function to copy the bearer token from localStorage
    function copyBearerToken() {
      const token = localStorage.getItem('token');

      if (!token) {
        alert('No token found');
        return;
      }

      // Copy the token to the clipboard
      const tempInput = document.createElement('textarea');
      tempInput.value = 'Bearer ' + token;
      document.body.appendChild(tempInput);
      tempInput.select();
      document.execCommand('copy');
      document.body.removeChild(tempInput);

      alert('Bearer token copied to clipboard');
    }

    // Clear the token from localStorage on logout
    document.getElementById('logout-form').addEventListener('submit', function() {
      localStorage.removeItem('token');
    });
  </script>
</body>

</html>