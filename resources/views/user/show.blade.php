@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col" style="background-color: #abcdef">
        <main class="flex-1 p-6 max-w-5xl mx-auto w-full">
            <!-- Back Button -->
            <a href="{{ route('user.home') }}"
                class="inline-flex items-center gap-2 mb-6 px-4 py-2 text-white rounded-lg hover:opacity-90 transition-opacity"
                style="background-color: #123456">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to Dashboard
            </a>

            <!-- Link Details Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <h1 class="mb-4 text-3xl font-bold">{{ $link->title }}</h1>
                        <div class="inline-block px-3 py-1 rounded-full text-sm font-medium mb-4"
                            style="background-color: #fedcba; color: #123456">
                            Company Resource
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-gray-700 font-semibold mb-3">Description</h3>
                    <p class="text-gray-600 leading-relaxed text-lg whitespace-pre-wrap break-words">{{ $link->description }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-gray-700 font-semibold mb-3">Link URL</h3>
                    <div class="flex items-center gap-3 p-4 rounded-lg bg-gray-50">
                        <code class="flex-1 text-gray-700 break-all">{{ $link->url }}</code>
                    </div>
                </div>

                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 px-6 py-3 text-white rounded-lg hover:opacity-90 transition-opacity shadow-md"
                    style="background-color: #123456">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                    Open Link in New Tab
                </a>
            </div>

            <!-- Notes Section -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">My Notes</h2>
                    <button id="open-note-modal"
                        class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg hover:opacity-90 transition-opacity shadow-sm"
                        style="background-color: #123456">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Add Note
                    </button>
                </div>

                @if($link->notes->count() > 0)
                    <div class="space-y-4">
                        @foreach($link->notes as $note)
                            <div class="p-5 rounded-xl border-2 hover:shadow-md transition-shadow relative"
                                style="border-color: #abcdef; background-color: #fafbfc">
                                <div class="flex items-start gap-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-gray-700 mb-3 leading-relaxed whitespace-pre-wrap break-words">{{ $note->content }}</p>
                                        <div class="flex items-center gap-2 text-sm text-gray-500">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            {{ $note->created_at->format('M d, Y, h:i A') }} • {{ $note->user->name }}
                                        </div>
                                    </div>

                                    <!-- Delete Button on the Right -->
                                    <form action="{{ route('user.links.notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this note?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg hover:bg-red-50 transition-colors group" title="Delete note">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center"
                            style="background-color: #abcdef">
                            <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" style="color: #123456">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </div>
                        <p class="text-gray-500 mb-2">No notes yet</p>
                        <p class="text-gray-400 text-sm">Click "Add Note" to create your first note for this link</p>
                    </div>
                @endif
            </div>
        </main>

        <!-- Add Note Modal -->
        <div id="note-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
            <div id="modal-backdrop" class="fixed inset-0 bg-black/50 transition-opacity opacity-0"></div>

            <div id="modal-content"
                class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-8 relative z-10 transform scale-95 opacity-0 transition-all duration-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Note</h2>

                <form action="{{ route('user.links.notes.store', $link) }}" method="POST">
                    @csrf
                    <textarea name="content" placeholder="Write your note here..."
                        class="w-full p-4 border-2 rounded-xl focus:outline-none focus:ring-2 min-h-[160px] resize-none"
                        style="border-color: #abcdef" required autofocus></textarea>

                    <div class="flex gap-3 mt-6">
                        <button type="submit"
                            class="flex-1 px-6 py-3 text-white rounded-lg hover:opacity-90 transition-opacity shadow-md font-semibold"
                            style="background-color: #123456">
                            Save Note
                        </button>
                        <button type="button" id="close-note-modal"
                            class="px-6 py-3 rounded-lg hover:bg-gray-100 transition-colors border-2 font-semibold"
                            style="border-color: #e5e7eb; color: #6b7280">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            (function () {
                const modal = document.getElementById('note-modal');
                const backdrop = document.getElementById('modal-backdrop');
                const content = document.getElementById('modal-content');
                const openBtn = document.getElementById('open-note-modal');
                const closeBtn = document.getElementById('close-note-modal');

                function openModal() {
                    modal.classList.remove('hidden');
                    // small delay to allow transition
                    setTimeout(() => {
                        backdrop.classList.remove('opacity-0');
                        content.classList.remove('scale-95', 'opacity-0');
                        content.classList.add('scale-100', 'opacity-100');
                    }, 10);
                }

                function closeModal() {
                    backdrop.classList.add('opacity-0');
                    content.classList.remove('scale-100', 'opacity-100');
                    content.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                    }, 200);
                }

                openBtn.addEventListener('click', openModal);
                closeBtn.addEventListener('click', closeModal);
                backdrop.addEventListener('click', closeModal);

                // Close on Escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                });
            })();
        </script>
    </div>
@endsection