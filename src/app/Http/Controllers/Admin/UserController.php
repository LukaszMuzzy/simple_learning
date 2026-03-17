<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('gameSessions');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('username', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        if ($request->input('filter') === 'admins') {
            $query->where('is_admin', true);
        }

        $users = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $sessions = GameSession::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $sessionStats = [
            'total'   => GameSession::where('user_id', $user->id)->whereNotNull('completed_at')->count(),
            'correct' => GameSession::where('user_id', $user->id)->sum('correct_answers'),
            'total_q' => GameSession::where('user_id', $user->id)->sum('total_questions'),
        ];

        return view('admin.users.edit', compact('user', 'sessions', 'sessionStats'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email'    => ['nullable', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['boolean'],
        ]);

        // Prevent removing own admin flag
        if ($user->id === auth()->id() && !($validated['is_admin'] ?? false)) {
            return back()->withErrors(['is_admin' => 'You cannot remove your own admin privileges.']);
        }

        $data = [
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'] ?? '',
            'is_admin' => $request->boolean('is_admin'),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', "User @{$user->username} updated successfully.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['delete' => 'You cannot delete your own account from here.']);
        }

        $name = $user->username;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User @{$name} has been deleted.");
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own admin status.');
        }

        $user->update(['is_admin' => !$user->is_admin]);
        $action = $user->is_admin ? 'granted' : 'revoked';

        return back()->with('success', "Admin access {$action} for @{$user->username}.");
    }
}
