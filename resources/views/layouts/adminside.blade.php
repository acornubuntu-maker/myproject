<aside class="w-64 bg-white shadow-lg sticky top-16 h-[calc(100vh-4rem)] overflow-auto">
  <div class="p-4">
    <nav class="space-y-2">
      <a href="{{ route('admin.home') }}" class="block px-4 py-3 rounded hover:bg-gray-100">Dashboard</a>
      <a href="{{ route('admin.users') }}" class="block px-4 py-3 rounded hover:bg-gray-100">Users</a>
      <a href="{{ route('admin.groups') }}" class="block px-4 py-3 rounded hover:bg-gray-100">Groups</a>
      <a href="{{ route('admin.links') }}" class="block px-4 py-3 rounded hover:bg-gray-100">Links</a>
      <a href="{{ route('admin.reports') }}" class="block px-4 py-3 rounded hover:bg-gray-100">Reports</a>
    </nav>
  </div>
</aside>