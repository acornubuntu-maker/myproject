<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->loadMissing('group.links.notes'); // needs User::group() and Group::links()
        $group = $user->group;
        // Eager load notes count for the dashboard cards
        $links = $group ? $group->links->loadCount('notes') : collect();

        return view('user.dashboard', compact('user', 'group', 'links'));
    }

    public function show(Request $request, \App\Models\Link $link)
    {
        // check if user has access to this link via group
        $user = $request->user();
        if (!$user->group || !$user->group->links->contains($link->id)) {
            abort(403, 'Unauthorized access to this link.');
        }

        $link->load([
            'notes.user' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        return view('user.show', compact('link'));
    }

    public function storeNote(Request $request, \App\Models\Link $link)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $link->notes()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content'),
        ]);

        return redirect()->route('user.links.show', $link)->with('success', 'Note added successfully.');
    }

    public function destroyNote(Request $request, \App\Models\Note $note)
    {
        // Check if user owns the note
        if ($note->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized.');
        }

        $linkId = $note->link_id;
        $note->delete();

        return redirect()->route('user.links.show', $linkId)->with('success', 'Note deleted successfully.');
    }
}