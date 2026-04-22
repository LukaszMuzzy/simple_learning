<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WordDefinitionController;
use App\Http\Controllers\Admin\WordDefinitionGroupController;
use App\Http\Controllers\Admin\WordListController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/math', function () {
    return view('math.index');
})->name('math.index');

Route::get('/math/addition-subtraction', function () {
    return view('math.addition-subtraction');
})->name('math.addition-subtraction');

Route::get('/math/multiplication', function () {
    return view('math.multiplication');
})->name('math.multiplication');

Route::get('/math/number-bonds', function () {
    return view('math.number-bonds');
})->name('math.number-bonds');

Route::get('/english', function () {
    return view('english.index');
})->name('english.index');

Route::get('/english/spelling', function () {
    return view('english.spelling');
})->name('english.spelling');

Route::get('/english/anagram', function () {
    return view('english.anagram');
})->name('english.anagram');

Route::get('/english/word-definitions', function () {
    return view('english.word-definitions');
})->name('english.word-definitions');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
    Route::delete('/progress/{session}', [ProgressController::class, 'destroy'])->name('progress.destroy');
});

// ── Admin ────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');

    // Word Definitions
    Route::get('/word-definitions', [WordDefinitionController::class, 'index'])->name('word-definitions.index');
    Route::get('/word-definitions/create', [WordDefinitionController::class, 'create'])->name('word-definitions.create');
    Route::post('/word-definitions', [WordDefinitionController::class, 'store'])->name('word-definitions.store');
    Route::get('/word-definitions/{wordDefinition}/edit', [WordDefinitionController::class, 'edit'])->name('word-definitions.edit');
    Route::patch('/word-definitions/{wordDefinition}', [WordDefinitionController::class, 'update'])->name('word-definitions.update');
    Route::delete('/word-definitions/{wordDefinition}', [WordDefinitionController::class, 'destroy'])->name('word-definitions.destroy');

    // Definition Groups
    Route::get('/definition-groups', [WordDefinitionGroupController::class, 'index'])->name('definition-groups.index');
    Route::get('/definition-groups/create', [WordDefinitionGroupController::class, 'create'])->name('definition-groups.create');
    Route::post('/definition-groups', [WordDefinitionGroupController::class, 'store'])->name('definition-groups.store');
    Route::get('/definition-groups/{definitionGroup}/edit', [WordDefinitionGroupController::class, 'edit'])->name('definition-groups.edit');
    Route::patch('/definition-groups/{definitionGroup}', [WordDefinitionGroupController::class, 'update'])->name('definition-groups.update');
    Route::delete('/definition-groups/{definitionGroup}', [WordDefinitionGroupController::class, 'destroy'])->name('definition-groups.destroy');
    Route::post('/definition-groups/{definitionGroup}/definitions', [WordDefinitionGroupController::class, 'addDefinitions'])->name('definition-groups.add-definitions');
    Route::delete('/definition-groups/{definitionGroup}/definitions/{definition}', [WordDefinitionGroupController::class, 'removeDefinition'])->name('definition-groups.remove-definition');
    Route::patch('/definition-groups/{definitionGroup}/toggle-active', [WordDefinitionGroupController::class, 'toggleActive'])->name('definition-groups.toggle-active');

    // Word Lists
    Route::get('/word-lists', [WordListController::class, 'index'])->name('word-lists.index');
    Route::get('/word-lists/create', [WordListController::class, 'create'])->name('word-lists.create');
    Route::post('/word-lists', [WordListController::class, 'store'])->name('word-lists.store');
    Route::get('/word-lists/{wordList}/edit', [WordListController::class, 'edit'])->name('word-lists.edit');
    Route::patch('/word-lists/{wordList}', [WordListController::class, 'update'])->name('word-lists.update');
    Route::delete('/word-lists/{wordList}', [WordListController::class, 'destroy'])->name('word-lists.destroy');
    Route::post('/word-lists/{wordList}/words', [WordListController::class, 'addWords'])->name('word-lists.add-words');
    Route::delete('/word-lists/{wordList}/words/{word}', [WordListController::class, 'destroyWord'])->name('word-lists.destroy-word');
    Route::patch('/word-lists/{wordList}/toggle-active', [WordListController::class, 'toggleActive'])->name('word-lists.toggle-active');
});

require __DIR__.'/auth.php';
