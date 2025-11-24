
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', config('app.name'))</title>
    <link rel="stylesheet" href="{{ asset('resources/css/app.css') }}" />
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center">
        {{-- Support both Blade component ($slot) and section-based content --}}
        {!! $slot ?? $__env->yieldContent('content') !!}
    </div>
    <script src="{{ asset('resources/js/app.js') }}"></script>
</body>
</html>
