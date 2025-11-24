
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col" style="background-color:#abcdef">
  <div class="container mx-auto p-6 max-w-7xl w-full">
    <div class="mb-8">
      <h1 class="mb-2">Welcome back, {{ $user->name }}!</h1>
      <p class="text-gray-700">
        @if($group)
          You have access to {{ $links->count() }} link{{ $links->count() === 1 ? '' : 's' }} from the {{ $group->name }} group.
        @else
          You are not assigned to any group yet.
        @endif
      </p>
    </div>

    @if($group && $links->count() > 0)
      <div class="mb-6">
        <div class="relative max-w-md">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 21l-4.35-4.35"></path></svg>
          <input id="search" type="text" placeholder="Search links..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 shadow-sm" style="background-color:#fff">
        </div>
      </div>

      <div id="linksGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($links as $link)
          <div class="link-card bg-white rounded-lg shadow-md p-6" data-title="{{ strtolower($link->title) }}" data-description="{{ strtolower($link->description ?? '') }}">
            <h3 class="mb-2">{{ $link->title }}</h3>
            <p class="text-gray-600 mb-4">{{ $link->description }}</p>
            <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg" style="background-color:#123456">
              Open Link
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 13v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><path d="M15 3h6v6"></path><path d="M10 14L21 3"></path></svg>
            </a>
          </div>
        @endforeach
      </div>

      <script>
        (function(){
          const search = document.getElementById('search');
          const cards = Array.from(document.querySelectorAll('.link-card'));

          function filter(q){
            const term = q.trim().toLowerCase();
            cards.forEach(c => {
              if(!term) { c.style.display = ''; return; }
              const title = c.dataset.title || '';
              const desc = c.dataset.description || '';
              const show = title.includes(term) || desc.includes(term);
              c.style.display = show ? '' : 'none';
            });
          }

          let t;
          search.addEventListener('input', (e) => {
            clearTimeout(t);
            t = setTimeout(() => filter(e.target.value), 120);
          });
        })();
      </script>
    @elseif($group)
      <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <p class="text-gray-600">No links available for your group yet.</p>
      </div>
    @else
      <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <p class="text-gray-600">Please contact your administrator to be assigned to a group.</p>
      </div>
    @endif
  </div>
</div>
@endsection