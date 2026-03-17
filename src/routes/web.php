<?php

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

Route::get('/english', function () {
    return view('english.index');
})->name('english.index');

Route::get('/english/spelling', function () {
    return view('english.spelling');
})->name('english.spelling');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
});

require __DIR__.'/auth.php';
