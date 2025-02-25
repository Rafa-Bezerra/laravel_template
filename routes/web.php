<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MateriaisController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\GruposDeMaterialController;
use App\Http\Controllers\DivisoesController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\EstoqueController;
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

//DIVISÕES
Route::middleware('auth')->group(function () {
    Route::get('/divisoes', [DivisoesController::class, 'index'])->name('divisoes.index');
    Route::get('/divisoes/json', [DivisoesController::class, 'getListagem'])->name('divisoes.json');    
    Route::get('/divisoes/create', [DivisoesController::class, 'create'])->name('divisoes.create');
    Route::post('/divisoes/create', [DivisoesController::class, 'register'])->name('divisoes.insert');
    Route::get('/divisoes/edit/{id}', [DivisoesController::class, 'edit'])->name('divisoes.edit');
    Route::post('/divisoes/edit', [DivisoesController::class, 'update'])->name('divisoes.update');
    Route::get('/divisoes/delete/{id}', [DivisoesController::class, 'delete'])->name('divisoes.delete');
});

//EMPRESAS
Route::middleware('auth')->group(function () {
    Route::get('/empresas', [EmpresasController::class, 'index'])->name('empresas.index');
    Route::get('/empresas/json', [EmpresasController::class, 'getListagem'])->name('empresas.json');    
    Route::get('/empresas/contatos/json', [EmpresasController::class, 'getListagemContatos'])->name('empresas_contatos.json');    
    Route::get('/empresas/enderecos/json', [EmpresasController::class, 'getListagemEnderecos'])->name('empresas_enderecos.json');  
    Route::get('/empresas/create', [EmpresasController::class, 'create'])->name('empresas.create');
    Route::post('/empresas/create', [EmpresasController::class, 'register'])->name('empresas.insert');
    Route::post('/empresas/contatos/submit', [EmpresasController::class, 'submitContatos'])->name('empresas_contatos.submit');  
    Route::post('/empresas/enderecos/submit', [EmpresasController::class, 'submitEnderecos'])->name('empresas_enderecos.submit');  
    Route::get('/empresas/edit/{id}', [EmpresasController::class, 'edit'])->name('empresas.edit');
    Route::post('/empresas/edit', [EmpresasController::class, 'update'])->name('empresas.update');
    Route::get('/empresas/contatos/get/{id}', [EmpresasController::class, 'getContato'])->name('empresas_contatos.get');  
    Route::get('/empresas/enderecos/get/{id}', [EmpresasController::class, 'getEndereco'])->name('empresas_enderecos.get');  
    Route::get('/empresas/delete/{id}', [EmpresasController::class, 'delete'])->name('empresas.delete');
    Route::get('/empresas/contatos/delete/{id}', [EmpresasController::class, 'deleteContatos'])->name('empresas_contatos.delete');   
    Route::get('/empresas/enderecos/delete/{id}', [EmpresasController::class, 'deleteEnderecos'])->name('empresas_enderecos.delete');  
});

//COMPRAS
Route::middleware('auth')->group(function () {
    Route::get('/compras', [ComprasController::class, 'index'])->name('compras');
    Route::get('/compras/json', [ComprasController::class, 'getListagem'])->name('compras.json');    
    Route::get('/compras/itens/json', [ComprasController::class, 'getListagemItens'])->name('compras_itens.json');    
    Route::get('/compras/create', [ComprasController::class, 'create'])->name('compras.create');
    Route::post('/compras/create', [ComprasController::class, 'register'])->name('compras.insert');
    Route::post('/compras/itens/submit', [ComprasController::class, 'submitItens'])->name('compras_itens.submit');  
    Route::get('/compras/edit/{id}', [ComprasController::class, 'edit'])->name('compras.edit');
    Route::get('/compras/itens/get/{id}', [ComprasController::class, 'getItem'])->name('compras_itens.get'); 
    Route::post('/compras/edit', [ComprasController::class, 'update'])->name('compras.update');
    Route::get('/compras/delete/{id}', [ComprasController::class, 'delete'])->name('compras.delete');
    Route::get('/compras/itens/delete/{id}', [ComprasController::class, 'deleteItem'])->name('compras_itens.delete');
});

//ESTOQUE
Route::middleware('auth')->group(function () {
    Route::get('/estoque', [EstoqueController::class, 'index'])->name('estoque');
    Route::get('/estoque/json', [EstoqueController::class, 'getListagem'])->name('estoque.json');    
    Route::get('/estoque/edit/{id}', [EstoqueController::class, 'edit'])->name('estoque.edit');
    Route::post('/estoque/edit', [EstoqueController::class, 'update'])->name('estoque.update');
});

require __DIR__.'/auth.php';
