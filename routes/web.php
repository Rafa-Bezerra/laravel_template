<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MateriaisController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\GruposDeMaterialController;
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

//USUARIOS
Route::middleware('auth')->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/json', [UserController::class, 'getUsuarios'])->name('usuarios.json');
    Route::get('/novo_usuario', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/novo_usuario', [UserController::class, 'register'])->name('register');
    Route::get('/usuarios/edit/{id}', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::post('/usuarios/edit', [UserController::class, 'update'])->name('usuarios.update');
    Route::get('/usuarios/delete/{id}', [UserController::class, 'delete'])->name('usuarios.delete');
    Route::get('/usuarios/roles/{id}', [UserController::class, 'roles'])->name('usuarios.roles');
    Route::post('/usuarios/update_roles', [UserController::class, 'update_roles'])->name('usuarios.update_roles');
});

//ROLES
Route::middleware('auth')->group(function () {
    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
    Route::get('/roles/json', [RolesController::class, 'getRoles'])->name('roles.json');
    Route::get('/roles/actions/{id}', [RolesController::class, 'actions'])->name('roles.actions');
    Route::get('/roles/create', [RolesController::class, 'create'])->name('roles.create');
    Route::post('/roles/create', [RolesController::class, 'register'])->name('roles.insert');
    Route::get('/roles/edit/{id}', [RolesController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/edit', [RolesController::class, 'update'])->name('roles.update');
    Route::get('/roles/delete/{id}', [RolesController::class, 'delete'])->name('roles.delete');
    Route::post('/roles/update_actions', [RolesController::class, 'update_actions'])->name('roles.update_actions');
});

//ACTIONS
Route::middleware('auth')->group(function () {
    Route::get('/actions', [ActionController::class, 'index'])->name('actions.index');
    Route::get('/actions/json', [ActionController::class, 'getActions'])->name('actions.json');    
    Route::get('/actions/create', [ActionController::class, 'create'])->name('actions.create');
    Route::post('/actions/create', [ActionController::class, 'register'])->name('actions.insert');
    Route::get('/actions/edit/{id}', [ActionController::class, 'edit'])->name('actions.edit');
    Route::post('/actions/edit', [ActionController::class, 'update'])->name('actions.update');
    Route::get('/actions/delete/{id}', [ActionController::class, 'delete'])->name('actions.delete');
});

//GRUPOS DE MATERIAL
Route::middleware('auth')->group(function () {
    Route::get('/grupos_de_material', [GruposDeMaterialController::class, 'index'])->name('grupos_de_material.index');
    Route::get('/grupos_de_material/json', [GruposDeMaterialController::class, 'getListagem'])->name('grupos_de_material.json');    
    Route::get('/grupos_de_material/create', [GruposDeMaterialController::class, 'create'])->name('grupos_de_material.create');
    Route::post('/grupos_de_material/create', [GruposDeMaterialController::class, 'register'])->name('grupos_de_material.insert');
    Route::get('/grupos_de_material/edit/{id}', [GruposDeMaterialController::class, 'edit'])->name('grupos_de_material.edit');
    Route::post('/grupos_de_material/edit', [GruposDeMaterialController::class, 'update'])->name('grupos_de_material.update');
    Route::get('/grupos_de_material/delete/{id}', [GruposDeMaterialController::class, 'delete'])->name('grupos_de_material.delete');
});

//GRUPOS DE MATERIAL
Route::middleware('auth')->group(function () {
    Route::get('/materiais', [MateriaisController::class, 'index'])->name('materiais.index');
    Route::get('/materiais/json', [MateriaisController::class, 'getListagem'])->name('materiais.json');    
    Route::get('/materiais/create', [MateriaisController::class, 'create'])->name('materiais.create');
    Route::post('/materiais/create', [MateriaisController::class, 'register'])->name('materiais.insert');
    Route::get('/materiais/edit/{id}', [MateriaisController::class, 'edit'])->name('materiais.edit');
    Route::post('/materiais/edit', [MateriaisController::class, 'update'])->name('materiais.update');
    Route::get('/materiais/delete/{id}', [MateriaisController::class, 'delete'])->name('materiais.delete');
});

require __DIR__.'/auth.php';
