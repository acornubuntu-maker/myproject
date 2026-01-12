<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('group')->orderByDesc('created_at');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->get();
        $groups = Group::orderBy('name')->get();
        return view('admin.users', compact('users', 'groups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,employee',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users')->with('success', 'User created.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,employee',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User updated.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');

        $imported = 0;
        $skipped = 0;

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            // Assuming first column is email
            $email = trim($data[0] ?? '');

            // Skip invalid emails
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            // Check if exists
            if (User::where('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            // Username is part before @
            $name = explode('@', $email)[0];

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($email), // password is email
                'role' => 'employee',
            ]);

            $imported++;
        }

        fclose($handle);

        return redirect()->route('admin.users')->with('success', "Imported $imported users. Skipped $skipped existing users.");
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted.');
    }
}