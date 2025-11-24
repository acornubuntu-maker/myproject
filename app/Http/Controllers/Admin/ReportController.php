<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Models\Link;

class ReportController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('created_at')->get();
        $groups = Group::with('links')->orderBy('name')->get();
        $links = Link::orderByDesc('created_at')->get();

        return view('admin.reports', compact('users', 'groups', 'links'));
    }
}