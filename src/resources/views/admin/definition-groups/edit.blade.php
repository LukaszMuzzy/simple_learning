<x-admin-layout>
    <x-slot name="title">{{ $definitionGroup->label }}</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.definition-groups.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
            ← Back to Definition Groups
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6 items-start">

        {{-- ── Left: details + add definitions ───────────────────────────────── --}}
        <div class="xl:col-span-2 space-y-4">

            {{-- Group details --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="font-extrabold text-slate-800">Group Details</h2>
                </div>

                <form method="POST" action="{{ route('admin.definition-groups.update', $definitionGroup) }}" class="p-6 space-y-4">
                    @csrf @method('PATCH')

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Display Name</label>
                        <input type="text" name="label" value="{{ old('label', $definitionGroup->label) }}" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $definitionGroup->slug) }}" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Description</label>
                        <textarea name="description" rows="2"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none resize-none">{{ old('description', $definitionGroup->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $definitionGroup->sort_order) }}" min="0"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                        </div>
                        <div class="flex items-end pb-1">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ $definitionGroup->is_active ? 'checked' : '' }}
                                    class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-400">
                                <span class="text-sm font-bold text-slate-700">Active</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-colors">
                        Save Details
                    </button>
                </form>
            </div>

            {{-- Add definitions --}}
            @php $availableCount = $available->flatten()->count(); @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100" x-data="{ search: '', selected: [] }">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="font-extrabold text-slate-800">Add Definitions</h2>
                    <p class="text-slate-500 text-xs mt-0.5">
                        {{ $availableCount }} definition(s) not yet in this group.
                    </p>
                </div>

                @if($availableCount > 0)
                <form method="POST" action="{{ route('admin.definition-groups.add-definitions', $definitionGroup) }}" class="p-6 space-y-3">
                    @csrf

                    {{-- Search filter --}}
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" x-model="search" placeholder="Filter by word…"
                            class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>

                    {{-- Checkboxes grouped by difficulty --}}
                    <div class="max-h-72 overflow-y-auto border border-slate-100 rounded-xl divide-y divide-slate-50">
                        @foreach(['easy' => '⭐ Easy', 'medium' => '⭐⭐ Medium', 'hard' => '⭐⭐⭐ Hard'] as $diff => $diffLabel)
                            @if(isset($available[$diff]) && $available[$diff]->count())
                            @php $diffWords = $available[$diff]->map(fn($d) => "'".addslashes(strtolower($d->word))."'")->join(','); @endphp
                            <div x-show="search === '' || [{{ $diffWords }}].some(w => w.includes(search.toLowerCase()))">
                                <div class="px-4 py-2 bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider sticky top-0">
                                    {{ $diffLabel }}
                                </div>
                                @foreach($available[$diff] as $def)
                                <label
                                    x-show="search === '' || '{{ strtolower($def->word) }}'.includes(search.toLowerCase())"
                                    class="flex items-start gap-3 px-4 py-3 hover:bg-indigo-50 cursor-pointer transition-colors"
                                    :class="selected.includes({{ $def->id }}) ? 'bg-indigo-50' : ''">
                                    <input type="checkbox" name="definition_ids[]" value="{{ $def->id }}"
                                        x-on:change="selected.includes({{ $def->id }}) ? selected.splice(selected.indexOf({{ $def->id }}), 1) : selected.push({{ $def->id }})"
                                        class="mt-0.5 w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-400 flex-shrink-0">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-800">{{ $def->word }}</p>
                                        <p class="text-xs text-slate-400 truncate">{{ $def->definition }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @endif
                        @endforeach
                    </div>

                    <button type="submit" :disabled="selected.length === 0"
                        class="w-full py-2.5 rounded-xl font-bold text-sm transition-all border-2"
                        :class="selected.length > 0
                            ? 'bg-emerald-500 hover:bg-emerald-600 text-white border-emerald-500 shadow-sm'
                            : 'bg-white text-slate-400 border-dashed border-slate-300 cursor-not-allowed'">
                        <span x-text="selected.length > 0 ? '+ Add ' + selected.length + ' selected' : 'Tick words above to add them'">
                            Tick words above to add them
                        </span>
                    </button>
                </form>
                @else
                <div class="p-6 text-center text-slate-400 text-sm">
                    <p>All available definitions are already in this group.</p>
                </div>
                @endif
            </div>

            {{-- Danger zone --}}
            <div class="bg-white rounded-2xl shadow-sm border border-red-100">
                <div class="px-6 py-4 border-b border-red-100">
                    <h2 class="font-extrabold text-red-700 text-sm">Danger Zone</h2>
                </div>
                <div class="p-6">
                    <p class="text-xs text-slate-500 mb-3">Delete this group. The definitions themselves will not be deleted.</p>
                    <form method="POST" action="{{ route('admin.definition-groups.destroy', $definitionGroup) }}"
                        x-data
                        @submit.prevent="if(confirm('Delete group \"{{ $definitionGroup->label }}\"?')) $el.submit()">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-sm transition-colors">
                            Delete Group
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Right: assigned definitions ──────────────────────────────────── --}}
        <div class="xl:col-span-3" x-data="{ search: '' }">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="font-extrabold text-slate-800">Definitions in this Group
                            <span class="ml-2 px-2.5 py-0.5 bg-sky-100 text-sky-700 rounded-full text-xs font-bold">
                                {{ $assigned->count() }}
                            </span>
                        </h2>
                    </div>
                    @if($assigned->count() > 3)
                    <div class="relative flex-1 max-w-xs">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" x-model="search" placeholder="Filter…"
                            class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                    @endif
                </div>

                @if($assigned->count())
                <div class="divide-y divide-slate-50 max-h-[70vh] overflow-y-auto">
                    @foreach($assigned as $def)
                    @php
                        $badge = match($def->difficulty) {
                            'easy'   => 'bg-emerald-100 text-emerald-700',
                            'medium' => 'bg-amber-100 text-amber-700',
                            'hard'   => 'bg-red-100 text-red-700',
                            default  => 'bg-slate-100 text-slate-600',
                        };
                    @endphp
                    <div class="group flex items-start gap-3 px-6 py-4 hover:bg-slate-50 transition-colors"
                        x-show="search === '' || '{{ strtolower($def->word) }}'.includes(search.toLowerCase()) || '{{ strtolower($def->definition) }}'.includes(search.toLowerCase())">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="font-extrabold text-slate-800 text-sm">{{ $def->word }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $badge }}">
                                    {{ match($def->difficulty) { 'easy' => '⭐', 'medium' => '⭐⭐', 'hard' => '⭐⭐⭐', default => '' } }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 truncate">{{ $def->definition }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.definition-groups.remove-definition', [$definitionGroup, $def]) }}"
                            class="opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                            @csrf @method('DELETE')
                            <button type="submit" title="Remove from group"
                                onclick="return confirm('Remove \'{{ $def->word }}\' from this group?')"
                                class="text-slate-300 hover:text-red-400 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                <div class="px-6 pb-4 text-xs text-slate-400 font-semibold">
                    Hover a row and click × to remove it from the group.
                </div>
                @else
                <div class="py-16 text-center text-slate-400">
                    <div class="text-4xl mb-2">📭</div>
                    <p class="font-semibold">No definitions yet.</p>
                    <p class="text-sm mt-1">Use the panel on the left to add definitions to this group.</p>
                </div>
                @endif
            </div>
        </div>

    </div>
</x-admin-layout>
