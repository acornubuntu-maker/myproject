<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Models\Link;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('created_at')->get();
        $groups = Group::with('links')->orderBy('name')->get();
        $links = Link::orderByDesc('created_at')->get();

        return view('admin.dashboard', compact('users', 'groups', 'links'));
    }
}