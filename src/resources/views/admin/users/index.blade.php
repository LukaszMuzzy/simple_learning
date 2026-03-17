<x-admin-layout>
    <x-slot name="title">Users</x-slot>

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex-1 flex gap-2">
            <div class="relative flex-1 max-w-xs">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search name, username, email…"
                    class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none bg-white">
            </div>
            <select name="filter" onchange="this.form.submit()"
                class="border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-600 focus:ring-2 focus:ring-indigo-400 outline-none bg-white">
                <option value="" {{ !request('filter') ? 'selected' : '' }}>All users</option>
                <option value="admins" {{ request('filter') === 'admins' ? 'selected' : '' }}>Admins only</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-colors">
                Search
            </button>
            @if(request('search') || request('filter'))
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-300 transition-colors">
                Clear
            </a>
            @endif
        </form>

        <p class="text-sm text-slate-500 font-semibold flex-shrink-0">
            {{ $users->total() }} {{ Str::plural('user', $users->total()) }}
        </p>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">User</th>
                        <th class="text-left px-4 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider hidden sm:table-cell">Email</th>
                        <th class="text-center px-4 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Role</th>
                        <th class="text-center px-4 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider hidden md:table-cell">Games</th>
                        <th class="text-left px-4 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider hidden lg:table-cell">Joined</th>
                        <th class="text-right px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0
                                    {{ $user->is_admin ? 'bg-amber-100 text-amber-700' : 'bg-indigo-100 text-indigo-600' }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-400">&#64;{{ $user->username }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-slate-500 hidden sm:table-cell">
                            {{ $user->email ?: '—' }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($user->is_admin)
                                <span class="inline-flex items-center px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">
                                    ⭐ Admin
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-semibold">
                                    User
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center hidden md:table-cell">
                            <span class="font-bold text-slate-700">{{ $user->game_sessions_count }}</span>
                        </td>
                        <td class="px-4 py-4 text-slate-400 text-xs hidden lg:table-cell">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end space-x-2">
                                {{-- Toggle admin --}}
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        title="{{ $user->is_admin ? 'Revoke admin' : 'Make admin' }}"
                                        class="p-2 rounded-lg transition-colors {{ $user->is_admin ? 'text-amber-500 hover:bg-amber-50' : 'text-slate-400 hover:bg-slate-100 hover:text-amber-500' }}">
                                        <svg class="w-4 h-4" fill="{{ $user->is_admin ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif

                                {{-- Edit --}}
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="p-2 rounded-lg text-indigo-500 hover:bg-indigo-50 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                {{-- Delete --}}
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                    x-data
                                    @submit.prevent="if(confirm('Delete {{ $user->username }}? This will also delete all their game history.')) $el.submit()"
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 rounded-lg text-red-400 hover:bg-red-50 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                            <div class="text-4xl mb-2">👤</div>
                            <p class="font-semibold">No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
