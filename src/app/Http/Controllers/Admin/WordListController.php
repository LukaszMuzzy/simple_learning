<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Word;
use App\Models\WordList;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class WordListController extends Controller
{
    public function index()
    {
        $lists = WordList::withCount('words')->orderBy('sort_order')->orderBy('label')->get();

        return view('admin.word-lists.index', compact('lists'));
    }

    public function create()
    {
        return view('admin.word-lists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label'       => ['required', 'string', 'max:100'],
            'slug'        => ['required', 'string', 'max:60', 'alpha_dash', Rule::unique('word_lists', 'slug')],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['boolean'],
        ]);

        $list = WordList::create([
            'slug'        => $validated['slug'],
            'label'       => $validated['label'],
            'description' => $validated['description'] ?? null,
            'sort_order'  => $validated['sort_order'] ?? 0,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.word-lists.edit', $list)
            ->with('success', "Word list \"{$list->label}\" created. Now add some words!");
    }

    public function edit(WordList $wordList)
    {
        $words = $wordList->words()->orderBy('word')->get();

        return view('admin.word-lists.edit', compact('wordList', 'words'));
    }

    public function update(Request $request, WordList $wordList)
    {
        $validated = $request->validate([
            'label'       => ['required', 'string', 'max:100'],
            'slug'        => ['required', 'string', 'max:60', 'alpha_dash', Rule::unique('word_lists', 'slug')->ignore($wordList->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['boolean'],
        ]);

        $wordList->update([
            'slug'        => $validated['slug'],
            'label'       => $validated['label'],
            'description' => $validated['description'] ?? null,
            'sort_order'  => $validated['sort_order'] ?? 0,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'List details updated.');
    }

    public function destroy(WordList $wordList)
    {
        $label = $wordList->label;
        $wordList->delete(); // cascades to words

        return redirect()->route('admin.word-lists.index')
            ->with('success', "Word list \"{$label}\" deleted.");
    }

    /** Bulk-add words from textarea input (one per line or comma-separated). */
    public function addWords(Request $request, WordList $wordList)
    {
        $request->validate([
            'words_input' => ['required', 'string', 'max:10000'],
        ]);

        // Split by newline, comma, or semicolon; clean up whitespace
        $raw   = $request->input('words_input');
        $items = preg_split('/[\n\r,;]+/', $raw);
        $added = 0;

        foreach ($items as $item) {
            $word = trim($item);

            if ($word === '' || mb_strlen($word) > 100) {
                continue;
            }

            $created = Word::firstOrCreate([
                'word_list_id' => $wordList->id,
                'word'         => $word,
            ]);

            if ($created->wasRecentlyCreated) {
                $added++;
            }
        }

        return back()->with('success', "{$added} new word(s) added.");
    }

    public function destroyWord(WordList $wordList, Word $word)
    {
        abort_unless($word->word_list_id === $wordList->id, 403);
        $word->delete();

        return back()->with('success', "\"{$word->word}\" removed.");
    }

    public function toggleActive(WordList $wordList)
    {
        $wordList->update(['is_active' => !$wordList->is_active]);
        $state = $wordList->is_active ? 'enabled' : 'disabled';

        return back()->with('success', "List \"{$wordList->label}\" {$state}.");
    }
}
