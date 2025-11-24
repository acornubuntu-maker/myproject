
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Links Management</h1>
    <button id="open-modal" class="flex items-center gap-2 px-4 py-2 text-white rounded-lg" style="background-color:#123456;">Add Link</button>
  </div>

  @if(session('success'))
    <div class="mb-4 text-green-700">{{ session('success') }}</div>
  @endif

  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead style="background-color:#abcdef;">
          <tr>
            <th class="px-6 py-3 text-left">Title</th>
            <th class="px-6 py-3 text-left">Description</th>
            <th class="px-6 py-3 text-left">URL</th>
            <th class="px-6 py-3 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($links as $link)
            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
              <td class="px-6 py-4">{{ $link->title }}</td>
              <td class="px-6 py-4">{{ $link->description }}</td>
              <td class="px-6 py-4">
                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="hover:underline" style="color:#123456;">
                  {{ $link->url }}
                </a>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <button class="edit-btn px-2 py-1 border rounded text-sm" data-id="{{ $link->id }}" data-title="{{ e($link->title) }}" data-description="{{ e($link->description) }}" data-url="{{ e($link->url) }}">Edit</button>

                  <form method="POST" action="{{ route('admin.links.destroy', $link) }}" onsubmit="return confirm('Delete this link?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="px-2 py-1 border rounded text-sm text-red-600">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal (simple) -->
<div id="modal" class="fixed inset-0 flex items-center justify-center bg-black/50 hidden z-50">
  <div class="bg-white rounded-lg w-full max-w-lg p-6">
    <h2 id="modal-title" class="text-lg font-semibold mb-4">Add Link</h2>

    <form id="link-form" method="POST" action="{{ route('admin.links.store') }}">
      @csrf
      <input type="hidden" name="_method" id="form-method" value="POST">
      <div class="mb-3">
        <label class="block mb-1">Title</label>
        <input name="title" id="title" class="w-full border px-3 py-2 rounded" required>
      </div>
      <div class="mb-3">
        <label class="block mb-1">Description</label>
        <textarea name="description" id="description" class="w-full border px-3 py-2 rounded"></textarea>
      </div>
      <div class="mb-3">
        <label class="block mb-1">URL</label>
        <input name="url" id="url" type="url" class="w-full border px-3 py-2 rounded" required>
      </div>

      <div class="flex justify-end gap-2">
        <button type="button" id="close-modal" class="px-4 py-2 border rounded">Cancel</button>
        <button type="submit" class="px-4 py-2 text-white rounded" style="background-color:#123456;">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
  // modal open/close
  const modal = document.getElementById('modal');
  const open = document.getElementById('open-modal');
  const close = document.getElementById('close-modal');
  const form = document.getElementById('link-form');
  const formMethod = document.getElementById('form-method');
  const modalTitle = document.getElementById('modal-title');

  open?.addEventListener('click', () => {
    form.action = "{{ route('admin.links.store') }}";
    formMethod.value = 'POST';
    modalTitle.textContent = 'Add Link';
    form.reset();
    modal.classList.remove('hidden');
  });

  close?.addEventListener('click', () => modal.classList.add('hidden'));

  // edit buttons
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      const title = btn.dataset.title;
      const description = btn.dataset.description;
      const url = btn.dataset.url;

      document.getElementById('title').value = title;
      document.getElementById('description').value = description;
      document.getElementById('url').value = url;

      form.action = "{{ url('/admin/links') }}/" + id;
      formMethod.value = 'PUT';
      modalTitle.textContent = 'Edit Link';
      modal.classList.remove('hidden');
    });
  });

  // close modal on backdrop click
  modal.addEventListener('click', (e) => {
    if (e.target === modal) modal.classList.add('hidden');
  });
</script>
@endsection