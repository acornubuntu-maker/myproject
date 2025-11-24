@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
  <h1 class="text-2xl font-semibold mb-4">Reports & Analytics</h1>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="mb-4">Users by Role</h2>
      <div style="height:260px"><canvas id="usersByRoleChart"></canvas></div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="mb-4">Links by Group</h2>
      <div style="height:260px"><canvas id="linksByGroupChart"></canvas></div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="mb-4">Users by Group</h2>
      <div style="height:260px"><canvas id="usersByGroupChart"></canvas></div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="mb-4">Summary Statistics</h2>
      <div class="space-y-4">
        <div class="flex items-center justify-between py-3 border-b">
          <span class="text-gray-600">Total Users</span>
          <span class="text-2xl" style="color:#123456">{{ $users->count() }}</span>
        </div>
        <div class="flex items-center justify-between py-3 border-b">
          <span class="text-gray-600">Total Groups</span>
          <span class="text-2xl" style="color:#123456">{{ $groups->count() }}</span>
        </div>
        <div class="flex items-center justify-between py-3 border-b">
          <span class="text-gray-600">Total Links</span>
          <span class="text-2xl" style="color:#123456">{{ $links->count() }}</span>
        </div>
        <div class="flex items-center justify-between py-3 border-b">
          <span class="text-gray-600">Avg Links per Group</span>
          <span class="text-2xl" style="color:#123456">
            {{ $groups->count() > 0 ? number_format($links->count() / $groups->count(), 1) : 0 }}
          </span>
        </div>
        <div class="flex items-center justify-between py-3">
          <span class="text-gray-600">Unassigned Users</span>
          <span class="text-2xl" style="color:#123456">
            {{ $users->whereNull('group_id')->count() + $users->where('group_id','')->count() }}
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  (function () {
    const users = @json($users);
    const groups = @json($groups->map(function($g){
      return ['id'=>$g->id,'name'=>$g->name,'links'=>$g->links->count()];
    }));
    const links = @json($links);

    // keep chart instances on window to avoid recreate loops
    function safeDestroy(chart) {
      if (chart && typeof chart.destroy === 'function') {
        try { chart.destroy(); } catch (e) { /* ignore */ }
      }
    }

    // Users by role (pie)
    (function(){
      const ctx = document.getElementById('usersByRoleChart').getContext('2d');
      safeDestroy(window.usersByRoleChart);
      const usersByRole = [
        users.filter(u => u.role === 'admin').length,
        users.filter(u => u.role === 'employee').length
      ];
      window.usersByRoleChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['Admin','Employee'],
          datasets: [{ data: usersByRole, backgroundColor: ['#123456','#fedcba'] }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { position: 'bottom' } }
        }
      });
    })();

    // Links by group (bar)
    (function(){
      const ctx = document.getElementById('linksByGroupChart').getContext('2d');
      safeDestroy(window.linksByGroupChart);
      window.linksByGroupChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: groups.map(g => g.name),
          datasets: [{ label: 'Links', data: groups.map(g => g.links), backgroundColor: '#123456' }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: { y: { beginAtZero: true, ticks: { precision:0 } } },
          plugins: { legend: { display: false } }
        }
      });
    })();

    // Users by group (bar)
    (function(){
      const ctx = document.getElementById('usersByGroupChart').getContext('2d');
      safeDestroy(window.usersByGroupChart);
      const usersByGroupData = groups.map(g => users.filter(u => String(u.group_id) === String(g.id)).length);
      window.usersByGroupChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: groups.map(g => g.name),
          datasets: [{ label: 'Users', data: usersByGroupData, backgroundColor: '#fedcba' }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: { y: { beginAtZero: true, ticks: { precision:0 } } },
          plugins: { legend: { display: false } }
        }
      });
    })();

    // optional: force a single resize after charts created to stabilize layout
    setTimeout(() => window.dispatchEvent(new Event('resize')), 120);
  })();
</script>
@endsection