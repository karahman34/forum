<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if (isset($title))
            {{ $title }} - {{ env('APP_NAME') }}
        @else
            {{ env('APP_NAME') }}
        @endif
    </title>

    {{-- Bulma CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="/css/style.css">
    
    {{-- Icons JS --}}
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    
    {{-- Stack CSS --}}
    @stack('css')
</head>
<body>
    {{-- Top Navigation --}}
    @include('partials.navigations.top')

    <div class="content-section">
        {{-- Content --}}
        @yield('content')
    </div>

    {{-- Custom JS --}}
    <script src="/js/custom.js"></script>

    {{-- Stack JS --}}
    @stack('js')
</body>
</html>