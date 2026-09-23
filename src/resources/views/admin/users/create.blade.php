<x-admin-layout>
    <x-slot name="title">Invite User</x-slot>

    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
                ← Back to Users
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="font-extrabold text-slate-800">Create &amp; Invite User</h2>
                <p class="text-slate-500 text-sm mt-0.5">A temporary password will be generated. Optionally send an email invitation.</p>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-5"
                  x-data="{ role: '{{ old('role', 'user') }}', sendInvite: {{ old('send_invite') ? 'true' : 'false' }} }">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none
                                @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Username <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-semibold text-sm">@</span>
                            <input type="text" name="username" value="{{ old('username') }}" required
                                class="w-full border border-slate-200 rounded-xl pl-7 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none
                                    @error('username') border-red-400 @enderror">
                        </div>
                        @error('username')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Email
                        <span class="text-slate-400 font-normal">(required to send invitation)</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        x-model.fill="email"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none
                            @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Role <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-3 gap-3">

                        {{-- User --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="user" x-model="role" class="sr-only peer">
                            <div class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-slate-200 text-slate-500
                                        peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700
                                        hover:border-slate-300 transition-colors text-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="font-bold text-sm">User</span>
                                <span class="text-xs font-normal leading-tight">Access to games &amp; progress only</span>
                            </div>
                        </label>

                        {{-- Teacher --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="teacher" x-model="role" class="sr-only peer">
                            <div class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-slate-200 text-slate-500
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700
                                        hover:border-slate-300 transition-colors text-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span class="font-bold text-sm">Teacher</span>
                                <span class="text-xs font-normal leading-tight">Word Lists, Definitions &amp; Game Links</span>
                            </div>
                        </label>

                        {{-- Admin --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="admin" x-model="role" class="sr-only peer">
                            <div class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-slate-200 text-slate-500
                                        peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-700
                                        hover:border-slate-300 transition-colors text-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span class="font-bold text-sm">Admin</span>
                                <span class="text-xs font-normal leading-tight">Full access to admin panel</span>
                            </div>
                        </label>
                    </div>

                    {{-- Role description banner --}}
                    <div x-show="role === 'teacher'" class="mt-3 flex items-start gap-2.5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-semibold">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Teachers can access: Word Lists, Word Definitions, Definition Groups, and Game Links. They cannot manage users or access the dashboard.
                    </div>
                    <div x-show="role === 'admin'" class="mt-3 flex items-start gap-2.5 p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-xs font-semibold">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Admins have full access to all admin panel features including user management.
                    </div>
                </div>

                {{-- Send invitation toggle --}}
                <div class="flex items-center justify-between p-4 rounded-xl border-2 border-slate-200 bg-slate-50"
                     :class="sendInvite ? 'border-indigo-300 bg-indigo-50' : 'border-slate-200 bg-slate-50'">
                    <div>
                        <p class="font-bold text-slate-700 text-sm">Send Email Invitation</p>
                        <p class="text-xs text-slate-500 mt-0.5">Email the user their login credentials. Requires an email address.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="send_invite" value="1" x-model="sendInvite" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-checked:bg-indigo-500 rounded-full transition-colors
                            after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                            after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                            peer-checked:after:translate-x-5"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create User
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                        class="px-6 py-2.5 text-slate-600 hover:text-slate-800 font-semibold text-sm transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
