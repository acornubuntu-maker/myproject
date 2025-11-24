<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->loadMissing('group.links'); // needs User::group() and Group::links()
        $group = $user->group;
        $links = $group ? $group->links : collect();

        return view('user.dashboard', compact('user', 'group', 'links'));
    }
}