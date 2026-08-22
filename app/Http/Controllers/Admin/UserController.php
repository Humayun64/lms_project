<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // List users, filterable by role
    public function index(Request $request)
    {
        $role = $request->role; // student | instructor | organization | admin | null

        $query = User::query();
        if (in_array($role, ['student', 'instructor', 'organization', 'admin'])) {
            $query->where('role', $role);
        }
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        // Counts for the tabs
        $counts = [
            'all'          => User::count(),
            'student'      => User::where('role', 'student')->count(),
            'instructor'   => User::where('role', 'instructor')->count(),
            'organization' => User::where('role', 'organization')->count(),
            'admin'        => User::where('role', 'admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'role', 'counts'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', 'in:student,instructor,organization,admin'],
            'status'   => ['required', 'in:active,blocked'],
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role'     => ['required', 'in:student,instructor,organization,admin'],
            'status'   => ['required', 'in:active,blocked'],
        ]);

        // Only change password if a new one was entered
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    // Quick block / unblock toggle
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('success', 'You cannot block your own account.');
        }

        $user->update(['status' => $user->status === 'active' ? 'blocked' : 'active']);

        return back()->with('success', 'User status updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('success', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }
}
