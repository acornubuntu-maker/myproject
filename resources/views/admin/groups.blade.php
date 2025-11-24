@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Groups Management</h1>
    <button id="open-modal" class="flex items-center gap-2 px-4 py-2 text-white rounded-lg" style="background-color:#123456;">Add Group</button>
  </div>

  @if(session('success'))
    <div class="mb-4 text-green-700">{{ session('success') }}</div>
  @endif

  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead style="background-color:#abcdef;">
          <tr>
            <th class="px-6 py-3 text-left">Group Name</th>
            <th class="px-6 py-3 text-left">Description</th>
            <th class="px-6 py-3 text-left">Links</th>
            <th class="px-6 py-3 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($groups as $group)
            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
              <td class="px-6 py-4">{{ $group->name }}</td>
              <td class="px-6 py-4">{{ $group->description }}</td>
              <td class="px-6 py-4">
                <span class="px-3 py-1 rounded-full text-sm" style="background-color:#fedcba;color:#123456;">
                  {{ $group->links->count() }} links
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <button class="edit-btn px-2 py-1 border rounded text-sm" 
                          data-id="{{ $group->id }}"
                          data-name="{{ e($group->name) }}"
                          data-description="{{ e($group->description) }}"
                          data-links="{{ $group->links->pluck('id')->join(',') }}">
                    Edit
                  </button>

                  <form method="POST" action="{{ route('admin.groups.destroy', $group) }}" onsubmit="return confirm('Delete this group?')" class="inline">
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

<!-- Modal -->
<div id="modal" class="fixed inset-0 flex items-center justify-center bg-black/50 hidden z-50">
  <div class="bg-white rounded-lg w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
    <h2 id="modal-title" class="text-lg font-semibold mb-4">Add Group</h2>

    <form id="group-form" method="POST" action="{{ route('admin.groups.store') }}">
      @csrf
      <input type="hidden" name="_method" id="form-method" value="POST">
      <div class="mb-3">
        <label class="block mb-1">Group Name</label>
        <input name="name" id="name" class="w-full border px-3 py-2 rounded" required>
      </div>

      <div class="mb-3">
        <label class="block mb-1">Description</label>
        <textarea name="description" id="description" class="w-full border px-3 py-2 rounded"></textarea>
      </div>

      <div class="mb-3">
        <label class="block mb-1">Assign Links</label>
        <div class="border border-gray-300 rounded-lg p-4 space-y-2 max-h-60 overflow-y-auto">
          @foreach($links as $link)
            <label class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded cursor-pointer">
              <input type="checkbox" name="link_ids[]" value="{{ $link->id }}" class="link-checkbox">
              <div>
                <p class="font-medium">{{ $link->title }}</p>
                <p class="text-sm text-gray-500">{{ $link->description }}</p>
              </div>
            </label>
          @endforeach
        </div>
        @if($links->isEmpty())
          <p class="text-sm text-gray-500">No links yet. Create one from Links page.</p>
        @endif
      </div>

      <div class="flex gap-3 pt-4">
        <button type="button" id="close-modal" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">Cancel</button>
        <button type="submit" class="flex-1 px-4 py-2 text-white rounded-lg" style="background-color:#123456;">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
  const modal = document.getElementById('modal');
  const open = document.getElementById('open-modal');
  const close = document.getElementById('close-modal');
  const form = document.getElementById('group-form');
  const formMethod = document.getElementById('form-method');
  const modalTitle = document.getElementById('modal-title');

  open?.addEventListener('click', () => {
    form.action = "{{ route('admin.groups.store') }}";
    formMethod.value = 'POST';
    modalTitle.textContent = 'Add Group';
    form.reset();
    // uncheck all checkboxes
    document.querySelectorAll('.link-checkbox').forEach(cb => cb.checked = false);
    modal.classList.remove('hidden');
  });

  close?.addEventListener('click', () => modal.classList.add('hidden'));

  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      const name = btn.dataset.name;
      const description = btn.dataset.description;
      const linksCsv = btn.dataset.links; // "1,2,3"

      document.getElementById('name').value = name || '';
      document.getElementById('description').value = description || '';

      // uncheck all then check assigned
      document.querySelectorAll('.link-checkbox').forEach(cb => cb.checked = false);
      if (linksCsv) {
        linksCsv.split(',').forEach(id => {
          const cb = document.querySelector('.link-checkbox[value="'+id+'"]');
          if (cb) cb.checked = true;
        });
      }

      form.action = "{{ url('/admin/groups') }}/" + id;
      formMethod.value = 'PUT';
      modalTitle.textContent = 'Edit Group';
      modal.classList.remove('hidden');
    });
  });

  modal.addEventListener('click', (e) => {
    if (e.target === modal) modal.classList.add('hidden');
  });
</script>
@endsection