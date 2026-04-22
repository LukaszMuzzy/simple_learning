<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WordDefinition;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WordDefinitionController extends Controller
{
    public function index(Request $request)
    {
        $difficulty = $request->query('difficulty', 'all');
        $search     = $request->query('search', '');

        $query = WordDefinition::query()->orderBy('difficulty')->orderBy('word');

        if ($difficulty !== 'all') {
            $query->where('difficulty', $difficulty);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('word', 'like', "%{$search}%")
                  ->orWhere('definition', 'like', "%{$search}%");
            });
        }

        $definitions = $query->get();

        $counts = [
            'all'    => WordDefinition::count(),
            'easy'   => WordDefinition::where('difficulty', 'easy')->count(),
            'medium' => WordDefinition::where('difficulty', 'medium')->count(),
            'hard'   => WordDefinition::where('difficulty', 'hard')->count(),
        ];

        return view('admin.word-definitions.index', compact('definitions', 'difficulty', 'search', 'counts'));
    }

    public function create()
    {
        return view('admin.word-definitions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'word'       => ['required', 'string', 'max:100', Rule::unique('word_definitions', 'word')],
            'definition' => ['required', 'string', 'max:500'],
            'difficulty' => ['required', Rule::in(['easy', 'medium', 'hard'])],
        ]);

        WordDefinition::create($validated);

        return redirect()->route('admin.word-definitions.index')
            ->with('success', "Definition for \"{$validated['word']}\" added.");
    }

    public function edit(WordDefinition $wordDefinition)
    {
        return view('admin.word-definitions.edit', compact('wordDefinition'));
    }

    public function update(Request $request, WordDefinition $wordDefinition)
    {
        $validated = $request->validate([
            'word'       => ['required', 'string', 'max:100', Rule::unique('word_definitions', 'word')->ignore($wordDefinition->id)],
            'definition' => ['required', 'string', 'max:500'],
            'difficulty' => ['required', Rule::in(['easy', 'medium', 'hard'])],
        ]);

        $wordDefinition->update($validated);

        return redirect()->route('admin.word-definitions.index')
            ->with('success', "Definition for \"{$validated['word']}\" updated.");
    }

    public function destroy(WordDefinition $wordDefinition)
    {
        $word = $wordDefinition->word;
        $wordDefinition->delete();

        return back()->with('success', "Definition for \"{$word}\" deleted.");
    }
}
