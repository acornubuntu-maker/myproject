<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    // list
    public function index(Request $request)
    {
        $query = Link::orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            });
        }

        $links = $query->get();
        return view('admin.links', compact('links'));
    }

    // store
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'url' => 'required|url|max:2048',
        ]);

        $data['created_by'] = Auth::id();

        $link = Link::create($data);

        return redirect()->route('admin.links')->with('success', 'Link created.');
    }

    // update
    public function update(Request $request, Link $link)
    {
        $data = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'url' => 'required|url|max:2048',
        ]);

        $link->update($data);

        return redirect()->route('admin.links')->with('success', 'Link updated.');
    }

    // destroy
    public function destroy(Link $link)
    {
        $link->delete();
        return redirect()->route('admin.links')->with('success', 'Link deleted.');
    }
}