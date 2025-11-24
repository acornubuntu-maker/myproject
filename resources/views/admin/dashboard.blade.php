@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-2xl font-semibold mb-4">Admin Dashboard</h1>
  <p class="mb-6">Welcome, {{ auth()->user()->name }} ({{ auth()->user()->email }})</p>

  <!-- Stats -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
      <p class="text-gray-600 mb-1">Total Users</p>
      <p class="text-3xl" style="color:#123456">{{ $users->count() }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
      <p class="text-gray-600 mb-1">Total Groups</p>
      <p class="text-3xl" style="color:#123456">{{ $groups->count() }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
      <p class="text-gray-600 mb-1">Total Links</p>
      <p class="text-3xl" style="color:#123456">{{ $links->count() }}</p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Users -->
    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="mb-4">Recent Users</h2>
      <div class="space-y-3">
        @foreach($users->take(5) as $u)
          <div class="flex items-center justify-between py-2 border-b last:border-0">
            <div>
              <p>{{ $u->name }}</p>
              <p class="text-sm text-gray-500">{{ $u->email }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm"
                  style="background-color: {{ $u->role === 'admin' ? '#123456' : '#fedcba' }}; color: {{ $u->role === 'admin' ? '#fff' : '#123456' }};">
              {{ $u->role }}
            </span>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Groups Overview -->
    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="mb-4">Groups Overview</h2>
      <div class="space-y-3">
        @foreach($groups as $g)
          <div class="py-3 border-b last:border-0">
            <div class="flex items-center justify-between mb-1">
              <p class="font-medium">{{ $g->name }}</p>
              <span class="text-sm text-gray-500">{{ $g->links->count() }} links</span>
            </div>
            <p class="text-sm text-gray-600">{{ $g->description }}</p>
          </div>
        @endforeach
        @if($groups->isEmpty())
          <p class="text-sm text-gray-500">No groups yet.</p>
        @endif
      </div>
    </div>
  </div>

  <!-- Reports summary (simple, server-side) -->
  <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="mb-4">Users by Role</h2>
      <p>Admins: {{ $users->where('role','admin')->count() }} — Employees: {{ $users->where('role','employee')->count() }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="mb-4">Links by Group (counts)</h2>
      <div class="space-y-2">
        @foreach($groups as $g)
          <div class="flex items-center justify-between">
            <div class="text-sm">{{ $g->name }}</div>
            <div class="text-sm font-medium">{{ $g->links->count() }}</div>
          </div>
        @endforeach
        @if($groups->isEmpty())
          <p class="text-sm text-gray-500">No groups to report.</p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection