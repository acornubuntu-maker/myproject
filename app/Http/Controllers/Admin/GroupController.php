<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $query = Group::with('links')->orderByDesc('created_at');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $groups = $query->get();
        $links = Link::orderBy('title')->get();
        return view('admin.groups', compact('groups', 'links'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'link_ids' => 'nullable|array',
            'link_ids.*' => 'integer|exists:links,id',
        ]);

        $group = Group::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'created_by' => Auth::id(),
        ]);

        $group->links()->sync($data['link_ids'] ?? []);

        return redirect()->route('admin.groups')->with('success', 'Group created.');
    }

    public function update(Request $request, Group $group)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'link_ids' => 'nullable|array',
            'link_ids.*' => 'integer|exists:links,id',
        ]);

        $group->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $group->links()->sync($data['link_ids'] ?? []);

        return redirect()->route('admin.groups')->with('success', 'Group updated.');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('admin.groups')->with('success', 'Group deleted.');
    }
}