<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MateriaisController;
use App\Http\Controllers\ActionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/tables', function () {
    return view('tables');
})->middleware(['auth', 'verified'])->name('tables');

Route::middleware('auth')->group(function () {
    Route::get('/materiais', [MateriaisController::class, 'index'])->name('materiais.index');
    Route::post('/materiais', [MateriaisController::class, 'create'])->name('materiais.create');
    Route::patch('/materiais', [MateriaisController::class, 'update'])->name('materiais.update');
    Route::delete('/materiais', [MateriaisController::class, 'destroy'])->name('materiais.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/json', [UserController::class, 'getUsuarios'])->name('usuarios.json');

    Route::get('/novo_usuario', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/novo_usuario', [UserController::class, 'register'])->name('register');

    Route::patch('/usuarios', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios', [UserController::class, 'destroy'])->name('usuarios.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
    Route::get('/roles/json', [RolesController::class, 'getRoles'])->name('roles.json');
    Route::get('/roles/actions/{id}', [RolesController::class, 'actions'])->name('roles.actions');

    Route::get('/new_role', [RolesController::class, 'create'])->name('roles.create');
    Route::post('/new_role', [RolesController::class, 'register'])->name('roles.insert');
});

Route::middleware('auth')->group(function () {
    Route::get('/actions', [ActionController::class, 'index'])->name('actions.index');
    Route::get('/actions/json', [ActionController::class, 'getActions'])->name('actions.json');

    Route::get('/new_action', [ActionController::class, 'create'])->name('actions.create');
    Route::post('/new_action', [ActionController::class, 'register'])->name('actions.insert');
});

require __DIR__.'/auth.php';
