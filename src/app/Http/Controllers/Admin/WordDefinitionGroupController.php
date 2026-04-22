<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WordDefinition;
use App\Models\WordDefinitionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class WordDefinitionGroupController extends Controller
{
    public function index()
    {
        $groups = WordDefinitionGroup::withCount('definitions')
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();

        return view('admin.definition-groups.index', compact('groups'));
    }

    public function create()
    {
        return view('admin.definition-groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label'       => ['required', 'string', 'max:100'],
            'slug'        => ['required', 'string', 'max:60', 'alpha_dash', Rule::unique('word_definition_groups', 'slug')],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['boolean'],
        ]);

        $group = WordDefinitionGroup::create([
            'label'       => $validated['label'],
            'slug'        => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'sort_order'  => $validated['sort_order'] ?? 99,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.definition-groups.edit', $group)
            ->with('success', "Group \"{$group->label}\" created. Now add some definitions!");
    }

    public function edit(WordDefinitionGroup $definitionGroup)
    {
        $assigned    = $definitionGroup->definitions;
        $assignedIds = $assigned->pluck('id')->all();

        $available = WordDefinition::whereNotIn('id', $assignedIds)
            ->orderBy('difficulty')
            ->orderBy('word')
            ->get()
            ->groupBy('difficulty');

        return view('admin.definition-groups.edit', compact('definitionGroup', 'assigned', 'available'));
    }

    public function update(Request $request, WordDefinitionGroup $definitionGroup)
    {
        $validated = $request->validate([
            'label'       => ['required', 'string', 'max:100'],
            'slug'        => ['required', 'string', 'max:60', 'alpha_dash', Rule::unique('word_definition_groups', 'slug')->ignore($definitionGroup->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['boolean'],
        ]);

        $definitionGroup->update([
            'label'       => $validated['label'],
            'slug'        => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'sort_order'  => $validated['sort_order'] ?? 99,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Group details updated.');
    }

    public function destroy(WordDefinitionGroup $definitionGroup)
    {
        $label = $definitionGroup->label;
        $definitionGroup->delete();

        return redirect()->route('admin.definition-groups.index')
            ->with('success', "Group \"{$label}\" deleted.");
    }

    public function addDefinitions(Request $request, WordDefinitionGroup $definitionGroup)
    {
        $request->validate([
            'definition_ids'   => ['required', 'array', 'min:1'],
            'definition_ids.*' => ['integer', 'exists:word_definitions,id'],
        ]);

        $definitionGroup->definitions()->syncWithoutDetaching($request->definition_ids);
        $count = count($request->definition_ids);

        return back()->with('success', "{$count} definition(s) added to \"{$definitionGroup->label}\".");
    }

    public function removeDefinition(WordDefinitionGroup $definitionGroup, WordDefinition $definition)
    {
        $definitionGroup->definitions()->detach($definition->id);

        return back()->with('success', "\"{$definition->word}\" removed from the group.");
    }

    public function toggleActive(WordDefinitionGroup $definitionGroup)
    {
        $definitionGroup->update(['is_active' => !$definitionGroup->is_active]);
        $state = $definitionGroup->is_active ? 'enabled' : 'disabled';

        return back()->with('success', "Group \"{$definitionGroup->label}\" {$state}.");
    }
}
