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

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="/css/app.css">
    {{-- Custom JS --}}
    <script defer src="/js/app.js"></script>

    {{-- Stack CSS --}}
    @stack('css')
</head>
<body>
    <div class="is-relative">
        {{-- Top Navigation --}}
        @include('partials.navigations.top')

        <div id="app" class="section">
            {{-- Content --}}
            @yield('content')
        </div>
    </div>

    {{-- Stack JS --}}
    @stack('js')
</body>
</html>