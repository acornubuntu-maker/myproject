
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Users Management</h1>
    <button id="open-modal" class="flex items-center gap-2 px-4 py-2 text-white rounded-lg" style="background-color:#123456;">Add User</button>
  </div>

  @if(session('success'))
    <div class="mb-4 text-green-700">{{ session('success') }}</div>
  @endif

  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead style="background-color:#abcdef;">
          <tr>
            <th class="px-6 py-3 text-left">Name</th>
            <th class="px-6 py-3 text-left">Email</th>
            <th class="px-6 py-3 text-left">Role</th>
            <th class="px-6 py-3 text-left">Group</th>
            <th class="px-6 py-3 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
            <td class="px-6 py-4">{{ $user->name }}</td>
            <td class="px-6 py-4">{{ $user->email }}</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 rounded-full text-sm" style="background-color: {{ $user->role === 'admin' ? '#123456' : '#fedcba' }}; color: {{ $user->role === 'admin' ? '#fff' : '#123456' }};">
                {{ $user->role }}
              </span>
            </td>
            <td class="px-6 py-4">{{ $user->group?->name ?? 'N/A' }}</td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <button class="edit-btn px-2 py-1 border rounded text-sm" 
                        data-id="{{ $user->id }}"
                        data-name="{{ e($user->name) }}"
                        data-email="{{ e($user->email) }}"
                        data-role="{{ $user->role }}"
                        data-group="{{ $user->group_id ?? '' }}">
                  Edit
                </button>

                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')" class="inline">
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
  <div class="bg-white rounded-lg w-full max-w-md p-6">
    <h2 id="modal-title" class="text-lg font-semibold mb-4">Add User</h2>

    <form id="user-form" method="POST" action="{{ route('admin.users.store') }}">
      @csrf
      <input type="hidden" name="_method" id="form-method" value="POST">

      <div class="mb-3">
        <label class="block mb-1">Name</label>
        <input name="name" id="name" class="w-full border px-3 py-2 rounded" required>
      </div>

      <div class="mb-3">
        <label class="block mb-1">Email</label>
        <input name="email" id="email" type="email" class="w-full border px-3 py-2 rounded" required>
      </div>

      <div class="mb-3">
        <label class="block mb-1">Password</label>
        <input name="password" id="password" type="password" class="w-full border px-3 py-2 rounded" required>
      </div>

      <div class="mb-3">
        <label class="block mb-1">Role</label>
        <select name="role" id="role" class="w-full border px-3 py-2 rounded">
          <option value="employee">Employee</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="block mb-1">Group</label>
        <select name="group_id" id="group_id" class="w-full border px-3 py-2 rounded">
          <option value="">No Group</option>
          @foreach($groups as $g)
            <option value="{{ $g->id }}">{{ $g->name }}</option>
          @endforeach
        </select>
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
  const form = document.getElementById('user-form');
  const formMethod = document.getElementById('form-method');
  const modalTitle = document.getElementById('modal-title');

  open?.addEventListener('click', () => {
    form.action = "{{ route('admin.users.store') }}";
    formMethod.value = 'POST';
    modalTitle.textContent = 'Add User';
    form.reset();
    modal.classList.remove('hidden');
  });

  close?.addEventListener('click', () => modal.classList.add('hidden'));

  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      const name = btn.dataset.name;
      const email = btn.dataset.email;
      const role = btn.dataset.role;
      const group = btn.dataset.group;

      document.getElementById('name').value = name || '';
      document.getElementById('email').value = email || '';
      document.getElementById('password').value = '';
      document.getElementById('role').value = role || 'employee';
      document.getElementById('group_id').value = group || '';

      form.action = "{{ url('/admin/users') }}/" + id;
      formMethod.value = 'PUT';
      modalTitle.textContent = 'Edit User';
      modal.classList.remove('hidden');
    });
  });

  modal.addEventListener('click', (e) => {
    if (e.target === modal) modal.classList.add('hidden');
  });
</script>
@endsection