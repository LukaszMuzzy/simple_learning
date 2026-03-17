<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    protected $signature   = 'user:make-admin {username : The username to grant admin access}';
    protected $description = 'Grant admin privileges to a user by their username';

    public function handle(): int
    {
        $username = $this->argument('username');
        $user     = User::where('username', $username)->first();

        if (!$user) {
            $this->error("No user found with username: {$username}");
            return 1;
        }

        if ($user->is_admin) {
            $this->warn("{$user->name} (@{$username}) is already an admin.");
            return 0;
        }

        $user->update(['is_admin' => true]);
        $this->info("✓ {$user->name} (@{$username}) has been granted admin access.");

        return 0;
    }
}
