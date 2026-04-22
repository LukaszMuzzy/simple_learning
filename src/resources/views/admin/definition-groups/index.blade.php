<x-admin-layout>
    <x-slot name="title">Definition Groups</x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-slate-500 text-sm font-semibold">{{ $groups->count() }} {{ Str::plural('group', $groups->count()) }}</p>
        <a href="{{ route('admin.definition-groups.create') }}"
            class="flex items-center space-x-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>New Group</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Group</th>
                        <th class="text-center px-4 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Words</th>
                        <th class="text-center px-4 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Status</th>
                        <th class="text-center px-4 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider hidden md:table-cell">Order</th>
                        <th class="text-right px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($groups as $group)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800">{{ $group->label }}</p>
                            @if($group->description)
                            <p class="text-xs text-slate-400 mt-0.5 max-w-xs truncate">{{ $group->description }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-7 bg-sky-100 text-sky-700 rounded-lg font-extrabold text-sm">
                                {{ $group->definitions_count }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <form method="POST" action="{{ route('admin.definition-groups.toggle-active', $group) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold transition-colors
                                        {{ $group->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                    {{ $group->is_active ? '● Active' : '○ Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-4 text-center hidden md:table-cell text-slate-400 font-semibold text-sm">
                            {{ $group->sort_order }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end space-x-1">
                                <a href="{{ route('admin.definition-groups.edit', $group) }}"
                                    class="p-2 rounded-lg text-indigo-500 hover:bg-indigo-50 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.definition-groups.destroy', $group) }}"
                                    x-data
                                    @submit.prevent="if(confirm('Delete group \"{{ $group->label }}\"? Definitions are not deleted, just unlinked.')) $el.submit()">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 rounded-lg text-red-400 hover:bg-red-50 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                            <div class="text-4xl mb-2">📚</div>
                            <p class="font-semibold">No definition groups yet.</p>
                            <a href="{{ route('admin.definition-groups.create') }}" class="text-indigo-600 hover:underline font-semibold mt-1 inline-block">
                                Create your first group →
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
