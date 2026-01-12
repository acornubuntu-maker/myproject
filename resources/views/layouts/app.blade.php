<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <nav class="h-16 px-6 flex items-center justify-between shadow-md fixed top-0 left-0 right-0 z-50"
      style="background-color:#123456;">
      <a href="{{ Auth::user()->role === 'admin' ? route('admin.home') : route('user.home') }}"
        class="flex items-center gap-3 text-white hover:opacity-90 transition">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color:#fedcba;">
          <span class="text-xl">🔗</span>
        </div>
        <span class="font-semibold">Company Link Manager</span>
      </a>

      <div class="flex items-center gap-4">
        @auth
          <div class="relative">
            <button id="profile-toggle" class="flex items-center gap-2 p-2 text-white hover:bg-white/10 rounded-lg"
              type="button">
              <div
                class="w-8 h-8 rounded-full overflow-hidden flex items-center justify-center bg-gray-200 border border-gray-300">
                @if(auth()->user()->profile_photo_url)
                  <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}"
                    class="w-full h-full object-cover">
                @else
                  <span style="color:#123456;">👤</span>
                @endif
              </div>
              <div class="text-sm">
                <div>{{ auth()->user()->name }}</div>
                <div class="text-xs text-gray-200">{{ auth()->user()->email }}</div>
              </div>
            </button>

            <div id="profile-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50 hidden">
              <div class="px-4 py-2 border-b">
                <p class="text-gray-900">{{ auth()->user()->name }}</p>
                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
              </div>

              <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
              </form>
            </div>
          </div>
        @endauth
      </div>
    </nav>

    <!-- Page Heading -->
    @isset($header)
      <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          {{ $header }}
        </div>
      </header>
    @endisset

    <!-- Page Content -->
    <div class="flex min-h-[calc(100vh-4rem)] pt-16">
      @auth
        @if(auth()->user()->role === 'admin')
          {{-- include admin sidebar from layouts/adminside.blade.php --}}
          @include('layouts.adminside')
        @endif
      @endauth

      <main class="flex-1 p-6">
        {!! $slot ?? $__env->yieldContent('content') !!}
      </main>
    </div>
  </div>

  <script>
    // profile dropdown toggle + close on outside click
    (function () {
      const toggle = document.getElementById('profile-toggle');
      const menu = document.getElementById('profile-menu');
      if (!toggle || !menu) return;

      toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        menu.classList.toggle('hidden');
      });

      document.addEventListener('click', function () {
        if (!menu.classList.contains('hidden')) {
          menu.classList.add('hidden');
        }
      });

      // optional: close on ESC
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') menu.classList.add('hidden');
      });
    })();
  </script>
</body>

</html>