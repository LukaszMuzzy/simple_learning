<x-admin-layout>
    <x-slot name="title">Edit User</x-slot>

    <div class="max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
                ← Back to Users
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Edit form --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="font-extrabold text-slate-800">Account Details</h2>
                        <p class="text-slate-500 text-sm mt-0.5">Edit &#64;{{ $user->username }}'s information</p>
                    </div>

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6 space-y-5">
                        @csrf @method('PATCH')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            {{-- Name --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                            </div>

                            {{-- Username --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Username</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-semibold text-sm">@</span>
                                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                                        class="w-full border border-slate-200 rounded-xl pl-7 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Email <span class="text-slate-400 font-normal">(optional)</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                        </div>

                        {{-- Password --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">New Password <span class="text-slate-400 font-normal">(leave blank to keep)</span></label>
                                <input type="password" name="password"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Confirm Password</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                            </div>
                        </div>

                        {{-- Admin toggle --}}
                        <div class="flex items-center justify-between p-4 rounded-xl border-2 {{ $user->is_admin ? 'border-amber-300 bg-amber-50' : 'border-slate-200 bg-slate-50' }}">
                            <div>
                                <p class="font-bold text-slate-700 text-sm">Admin Privileges</p>
                                <p class="text-xs text-slate-500 mt-0.5">Can access this admin panel and manage all users</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer"
                                @if($user->id === auth()->id()) title="Cannot remove your own admin access" @endif>
                                <input type="checkbox" name="is_admin" value="1"
                                    {{ $user->is_admin ? 'checked' : '' }}
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-300 peer-checked:bg-amber-400 rounded-full transition-colors
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                    peer-checked:after:translate-x-5"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <button type="submit"
                                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm">
                                Save Changes
                            </button>
                            <a href="{{ route('admin.users.index') }}"
                                class="px-6 py-2.5 text-slate-600 hover:text-slate-800 font-semibold text-sm transition-colors">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Danger zone --}}
                @if($user->id !== auth()->id())
                <div class="mt-4 bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-red-100">
                        <h2 class="font-extrabold text-red-700">Danger Zone</h2>
                    </div>
                    <div class="p-6 flex items-center justify-between">
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Delete this account</p>
                            <p class="text-xs text-slate-500 mt-0.5">Permanently deletes the user and all their game history. This cannot be undone.</p>
                        </div>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                            x-data
                            @submit.prevent="if(confirm('Permanently delete {{ $user->username }} and all their data?')) $el.submit()">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-sm transition-colors ml-4 flex-shrink-0">
                                Delete User
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar: stats + recent sessions --}}
            <div class="space-y-4">
                {{-- Stats --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <h3 class="font-extrabold text-slate-800 mb-4 text-sm uppercase tracking-wide">Activity</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 font-semibold">Games completed</span>
                            <span class="font-extrabold text-slate-800">{{ $sessionStats['total'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 font-semibold">Questions answered</span>
                            <span class="font-extrabold text-slate-800">{{ number_format($sessionStats['total_q']) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 font-semibold">Overall score</span>
                            <span class="font-extrabold {{ $sessionStats['total_q'] > 0 && round($sessionStats['correct'] / $sessionStats['total_q'] * 100) >= 70 ? 'text-emerald-600' : 'text-slate-800' }}">
                                {{ $sessionStats['total_q'] > 0 ? round($sessionStats['correct'] / $sessionStats['total_q'] * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 font-semibold">Member since</span>
                            <span class="font-extrabold text-slate-800">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Recent sessions --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h3 class="font-extrabold text-slate-800 text-sm uppercase tracking-wide">Recent Games</h3>
                    </div>
                    @if($sessions->count())
                    <div class="divide-y divide-slate-50">
                        @foreach($sessions as $session)
                        @php
                            $pct = $session->total_questions > 0 ? round($session->correct_answers / $session->total_questions * 100) : 0;
                            $typeIcon = ['multiplication' => '×', 'addition_subtraction' => '±', 'spelling' => '📝'][$session->game_type] ?? '?';
                        @endphp
                        <div class="flex items-center justify-between px-5 py-3">
                            <div class="flex items-center space-x-2.5">
                                <span class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center text-xs font-bold text-slate-600">{{ $typeIcon }}</span>
                                <div>
                                    <p class="text-xs font-bold text-slate-700">
                                        {{ $session->total_questions }}Q
                                        @if($session->completed_at) · {{ $session->completed_at->format('d M') }} @endif
                                    </p>
                                    @if(!$session->completed_at)
                                    <p class="text-xs text-slate-400">Abandoned</p>
                                    @endif
                                </div>
                            </div>
                            @if($session->completed_at)
                            <span class="text-xs font-extrabold {{ $pct >= 80 ? 'text-emerald-600' : ($pct >= 60 ? 'text-amber-500' : 'text-red-500') }}">
                                {{ $pct }}%
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="px-5 py-8 text-center text-slate-400 text-sm">No games yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
