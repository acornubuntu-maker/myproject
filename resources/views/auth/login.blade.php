
@extends('layouts.guest')

@section('content')
<div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" style="background-color: #abcdef;">
  <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md">
    <div class="flex items-center justify-center mb-6">
      <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #123456;">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
    </div>

    <h1 class="text-center mb-2 text-lg font-semibold">Company Link Manager</h1>
    <p class="text-center text-gray-600 mb-6">Sign in to access your dashboard</p>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
      @csrf

      <div>
        <label for="email" class="block mb-2 text-gray-700">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#123456] transition-shadow"
               placeholder="your.email@company.com" />
        @error('email')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label for="password" class="block mb-2 text-gray-700">Password</label>
        <input id="password" name="password" type="password" required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#123456] transition-shadow"
               placeholder="Enter your password" />
        @error('password')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      @if(session('error'))
        <div class="text-red-600 text-center text-sm">{{ session('error') }}</div>
      @endif

      <button type="submit"
              class="w-full py-3 text-white rounded-lg transition-colors hover:opacity-90"
              style="background-color: #123456;">
        Sign In
      </button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600">
      <a href="{{ route('password.request') }}" class="text-indigo-700 hover:underline">Forgot your password?</a>
      <span class="mx-2">·</span>
      <a href="mailto:admin@example.com" class="text-indigo-700 hover:underline">Need help?</a>
    </div>
  </div>
</div>
@endsection