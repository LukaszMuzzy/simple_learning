<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
        } elseif ($request->input('filter') === 'teachers') {
            $query->where('is_teacher', true)->where('is_admin', false);
        }

        $users = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users'],
            'email'      => ['nullable', 'email', 'max:255', 'unique:users'],
            'role'       => ['required', 'in:user,teacher,admin'],
            'send_invite' => ['boolean'],
        ]);

        $tempPassword = Str::random(12);

        $user = User::create([
            'name'       => $validated['name'],
            'username'   => $validated['username'],
            'email'      => $validated['email'] ?? null,
            'password'   => Hash::make($tempPassword),
            'is_admin'   => $validated['role'] === 'admin',
            'is_teacher' => $validated['role'] === 'teacher',
        ]);

        if ($request->boolean('send_invite') && $user->email) {
            $this->sendInvitationEmail($user, $tempPassword);
            $message = "User @{$user->username} created and invitation sent to {$user->email}.";
        } else {
            $message = "User @{$user->username} created successfully. Temporary password: {$tempPassword}";
        }

        return redirect()->route('admin.users.index')->with('success', $message);
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
            'name'       => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email'      => ['nullable', 'email', 'max:255'],
            'password'   => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_admin'   => ['boolean'],
            'is_teacher' => ['boolean'],
        ]);

        // Prevent removing own admin flag
        if ($user->id === auth()->id() && !($validated['is_admin'] ?? false)) {
            return back()->withErrors(['is_admin' => 'You cannot remove your own admin privileges.']);
        }

        $isAdmin   = $request->boolean('is_admin');
        $isTeacher = $isAdmin ? false : $request->boolean('is_teacher');

        $data = [
            'name'       => $validated['name'],
            'username'   => $validated['username'],
            'email'      => $validated['email'] ?? '',
            'is_admin'   => $isAdmin,
            'is_teacher' => $isTeacher,
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

    private function sendInvitationEmail(User $user, string $tempPassword): void
    {
        $appName  = config('app.name', 'Simple Learning');
        $loginUrl = url('/login');
        $role     = $user->is_admin ? 'Administrator' : ($user->is_teacher ? 'Teacher' : 'User');

        Mail::raw(
            "Hello {$user->name},\n\n"
            . "You have been invited to join {$appName} as a {$role}.\n\n"
            . "Your login details:\n"
            . "  Username: {$user->username}\n"
            . "  Temporary password: {$tempPassword}\n\n"
            . "Please log in and change your password as soon as possible:\n"
            . "{$loginUrl}\n\n"
            . "Best regards,\n{$appName} Team",
            function ($message) use ($user, $appName) {
                $message->to($user->email, $user->name)
                        ->subject("You've been invited to {$appName}");
            }
        );
    }
}
