@extends('layouts.app')

@section('content')
  <div class="min-h-screen flex flex-col" style="background-color: #abcdef">
    <main class="flex-1 p-6 max-w-7xl mx-auto w-full">
      <div class="mb-8">
        <h1 class="mb-2 text-2xl font-bold">Welcome back, {{ $user->name }}!</h1>
        <p class="text-gray-700">
          @if($group)
            You have access to {{ $links->count() }} link{{ $links->count() === 1 ? '' : 's' }} from the {{ $group->name }}
            group.
          @else
            You are not assigned to any group yet.
          @endif
        </p>
      </div>

      @if($group && $links->count() > 0)
        <div class="mb-6">
          <div class="relative max-w-md">
            <!-- Search Icon -->
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input id="search" type="text" placeholder="Search links..."
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 shadow-sm"
              style="background-color: #fff" />
          </div>
        </div>

        <div id="linksGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($links as $link)
            <div class="link-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow"
              data-title="{{ strtolower($link->title) }}" data-description="{{ strtolower($link->description ?? '') }}">
              <h3 class="mb-2 text-xl font-semibold">{{ $link->title }}</h3>
              <p class="text-gray-600 mb-4 line-clamp-2 h-12">{{ $link->description }}</p>

              <div class="flex items-center gap-3">
                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                  class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg hover:opacity-90 transition-opacity"
                  style="background-color: #123456">
                  Open Link
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 13v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                  </svg>
                </a>

                <a href="{{ route('user.links.show', $link) }}"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-lg hover:opacity-90 transition-opacity"
                  style="background-color: #fedcba; color: #123456">
                  More
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                  </svg>
                </a>

                @if($link->notes_count > 0)
                  <span
                    class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $link->notes_count }} Notes
                  </span>
                @endif
              </div>
            </div>
          @endforeach
        </div>

        <div id="noResults" class="bg-white rounded-lg shadow-md p-8 text-center hidden">
          <p class="text-gray-600">No links found matching your search.</p>
        </div>

        <script>
          (function () {
            const search = document.getElementById('search');
            const cards = Array.from(document.querySelectorAll('.link-card'));
            const noResults = document.getElementById('noResults');
            const linksGrid = document.getElementById('linksGrid');

            function filter(q) {
              const term = q.trim().toLowerCase();
              let hasResults = false;

              cards.forEach(c => {
                const title = c.dataset.title || '';
                const desc = c.dataset.description || '';
                const show = !term || title.includes(term) || desc.includes(term);
                c.style.display = show ? '' : 'none';
                if (show) hasResults = true;
              });

              if (hasResults) {
                linksGrid.style.display = 'grid';
                noResults.classList.add('hidden');
              } else {
                linksGrid.style.display = 'none';
                noResults.classList.remove('hidden');
              }
            }

            let t;
            search.addEventListener('input', (e) => {
              clearTimeout(t);
              t = setTimeout(() => filter(e.target.value), 120);
            });
          })();
        </script>

      @elseif($group && $links->count() === 0)
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
          <p class="text-gray-600">No links available for your group yet.</p>
        </div>
      @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
          <p class="text-gray-600">Please contact your administrator to be assigned to a group.</p>
        </div>
      @endif
    </main>
  </div>
@endsection