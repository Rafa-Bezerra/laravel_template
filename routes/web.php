<?php

use App\Http\Controllers\{
    DashboardController,
    ProfileController,
    RolesController,
    UserController,
    MateriaisController,
    ActionController,
    GruposDeMaterialController,
    DivisoesController,
    EmpresasController,
    ComprasController,
    EstoqueController,
    ServicosController,
    ComissoesController,
    OrcamentosController,
    RelatoriosController,
    PagamentosController,
    DespesasController,
    BancosController
};

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

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

//MATERIAIS
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
    Route::get('/compras/pagamentos/json', [ComprasController::class, 'getListagemPagamentos'])->name('compras_pagamentos.json');
    Route::post('/compras/pagamentos/submit', [ComprasController::class, 'submitPagamentos'])->name('compras_pagamentos.submit');    
    Route::get('/compras/pagamentos/get/{id}', [ComprasController::class, 'getPagamento'])->name('compras_pagamentos.get');
    Route::get('/compras/pagamentos/delete/{id}', [ComprasController::class, 'deletePagamento'])->name('compras_pagamentos.delete'); 
});

//ESTOQUE
Route::middleware('auth')->group(function () {
    Route::get('/estoque', [EstoqueController::class, 'index'])->name('estoque');
    Route::get('/estoque/json', [EstoqueController::class, 'getListagem'])->name('estoque.json');    
    Route::get('/estoque/edit/{id}', [EstoqueController::class, 'edit'])->name('estoque.edit');
    Route::post('/estoque/edit', [EstoqueController::class, 'update'])->name('estoque.update');
    Route::get('/estoque/create', [EstoqueController::class, 'create'])->name('estoque.create');
    Route::post('/estoque/create', [EstoqueController::class, 'register'])->name('estoque.insert');
});

//SERVICOS
Route::middleware('auth')->group(function () {
    Route::get('/servicos', [ServicosController::class, 'index'])->name('servicos');
    Route::get('/servicos/json', [ServicosController::class, 'getListagem'])->name('servicos.json');    
    Route::get('/servicos/create', [ServicosController::class, 'create'])->name('servicos.create');
    Route::post('/servicos/create', [ServicosController::class, 'register'])->name('servicos.insert');
    Route::get('/servicos/edit/{id}', [ServicosController::class, 'edit'])->name('servicos.edit');
    Route::post('/servicos/edit', [ServicosController::class, 'update'])->name('servicos.update');
    Route::get('/servicos/delete/{id}', [ServicosController::class, 'delete'])->name('servicos.delete');
});

//COMISSOES
Route::middleware('auth')->group(function () {
    Route::get('/comissoes', [ComissoesController::class, 'index'])->name('comissoes');
    Route::get('/comissoes/json', [ComissoesController::class, 'getListagem'])->name('comissoes.json');    
    Route::get('/comissoes/create', [ComissoesController::class, 'create'])->name('comissoes.create');
    Route::post('/comissoes/create', [ComissoesController::class, 'register'])->name('comissoes.insert');
    Route::get('/comissoes/edit/{id}', [ComissoesController::class, 'edit'])->name('comissoes.edit');
    Route::post('/comissoes/edit', [ComissoesController::class, 'update'])->name('comissoes.update');
    Route::get('/comissoes/delete/{id}', [ComissoesController::class, 'delete'])->name('comissoes.delete');
});

//ORCAMENTOS
Route::middleware('auth')->group(function () {
    Route::get('/orcamentos', [OrcamentosController::class, 'index'])->name('orcamentos');
    Route::get('/orcamentos/json', [OrcamentosController::class, 'getListagem'])->name('orcamentos.json'); 
    Route::post('/orcamentos/empresas/enderecos/json', [OrcamentosController::class, 'getEnderecos'])->name('orcamentos.getEnderecos');    
    Route::get('/orcamentos/create', [OrcamentosController::class, 'create'])->name('orcamentos.create');
    Route::post('/orcamentos/create', [OrcamentosController::class, 'register'])->name('orcamentos.insert');    
    Route::get('/orcamentos/edit/{id}', [OrcamentosController::class, 'edit'])->name('orcamentos.edit');
    Route::post('/orcamentos/edit', [OrcamentosController::class, 'update'])->name('orcamentos.update');    
    Route::get('/orcamentos/delete/{id}', [OrcamentosController::class, 'delete'])->name('orcamentos.delete');    
    Route::get('/orcamentos/itens/json', [OrcamentosController::class, 'getListagemItens'])->name('orcamentos_itens.json');
    Route::post('/orcamentos/itens/estoque', [OrcamentosController::class, 'getEstoqueMaterial'])->name('orcamentos_itens.estoque');
    Route::post('/orcamentos/itens/submit', [OrcamentosController::class, 'submitItens'])->name('orcamentos_itens.submit');    
    Route::get('/orcamentos/itens/get/{id}', [OrcamentosController::class, 'getItem'])->name('orcamentos_itens.get');
    Route::get('/orcamentos/itens/delete/{id}', [OrcamentosController::class, 'deleteItem'])->name('orcamentos_itens.delete');      
    Route::get('/orcamentos/servicos/json', [OrcamentosController::class, 'getListagemServicos'])->name('orcamentos_servicos.json');
    Route::post('/orcamentos/servicos/submit', [OrcamentosController::class, 'submitServicos'])->name('orcamentos_servicos.submit');    
    Route::get('/orcamentos/servicos/get/{id}', [OrcamentosController::class, 'getServico'])->name('orcamentos_servicos.get');
    Route::get('/orcamentos/servicos/delete/{id}', [OrcamentosController::class, 'deleteServico'])->name('orcamentos_servicos.delete');     
    Route::get('/orcamentos/comissoes/json', [OrcamentosController::class, 'getListagemComissoes'])->name('orcamentos_comissoes.json');
    Route::post('/orcamentos/comissoes/submit', [OrcamentosController::class, 'submitComissoes'])->name('orcamentos_comissoes.submit');    
    Route::get('/orcamentos/comissoes/get/{id}', [OrcamentosController::class, 'getComissao'])->name('orcamentos_comissoes.get');
    Route::get('/orcamentos/comissoes/delete/{id}', [OrcamentosController::class, 'deleteComissao'])->name('orcamentos_comissoes.delete');      
    Route::get('/orcamentos/socios/json', [OrcamentosController::class, 'getListagemSocios'])->name('orcamentos_socios.json');
    Route::post('/orcamentos/socios/submit', [OrcamentosController::class, 'submitSocios'])->name('orcamentos_socios.submit');    
    Route::get('/orcamentos/socios/get/{id}', [OrcamentosController::class, 'getSocio'])->name('orcamentos_socios.get');
    Route::get('/orcamentos/socios/delete/{id}', [OrcamentosController::class, 'deleteSocio'])->name('orcamentos_socios.delete');          
    Route::get('/orcamentos/pagamentos/json', [OrcamentosController::class, 'getListagemPagamentos'])->name('orcamentos_pagamentos.json');
    Route::post('/orcamentos/pagamentos/submit', [OrcamentosController::class, 'submitPagamentos'])->name('orcamentos_pagamentos.submit');    
    Route::get('/orcamentos/pagamentos/get/{id}', [OrcamentosController::class, 'getPagamento'])->name('orcamentos_pagamentos.get');
    Route::get('/orcamentos/pagamentos/delete/{id}', [OrcamentosController::class, 'deletePagamento'])->name('orcamentos_pagamentos.delete');  
});

//RELATÓRIOS
Route::middleware('auth')->group(function () {
    Route::get('/relatorios', [RelatoriosController::class, 'index'])->name('relatorios');
    Route::get('/relatorios/orcamentos_por_cliente', [RelatoriosController::class, 'orcamentos_por_cliente'])->name('orcamentos_por_cliente');
    Route::get('/relatorios/compras_por_material', [RelatoriosController::class, 'compras_por_material'])->name('compras_por_material');
    Route::get('/relatorios/movimentacoes_de_estoque', [RelatoriosController::class, 'movimentacoes_de_estoque'])->name('movimentacoes_de_estoque');
});

//RELATÓRIOS
Route::middleware('auth')->group(function () {
    Route::get('/pagamentos/json', [PagamentosController::class, 'getListagem'])->name('pagamentos.json');
});

//BANCOS
Route::middleware('auth')->group(function () {
    Route::get('/bancos', [BancosController::class, 'index'])->name('bancos');
    Route::get('/bancos/json', [BancosController::class, 'getListagem'])->name('bancos.json');    
    Route::get('/bancos/create', [BancosController::class, 'create'])->name('bancos.create');
    Route::post('/bancos/create', [BancosController::class, 'register'])->name('bancos.insert');
    Route::get('/bancos/edit/{id}', [BancosController::class, 'edit'])->name('bancos.edit');
    Route::post('/bancos/edit', [BancosController::class, 'update'])->name('bancos.update');
    Route::get('/bancos/delete/{id}', [BancosController::class, 'delete'])->name('bancos.delete');
});

//DESPESAS
Route::middleware('auth')->group(function () {
    Route::get('/despesas', [DespesasController::class, 'index'])->name('despesas');
    Route::get('/despesas/json', [DespesasController::class, 'getListagem'])->name('despesas.json');    
    Route::get('/despesas/create', [DespesasController::class, 'create'])->name('despesas.create');
    Route::post('/despesas/create', [DespesasController::class, 'register'])->name('despesas.insert');
    Route::get('/despesas/edit/{id}', [DespesasController::class, 'edit'])->name('despesas.edit');
    Route::post('/despesas/edit', [DespesasController::class, 'update'])->name('despesas.update');
    Route::get('/despesas/delete/{id}', [DespesasController::class, 'delete'])->name('despesas.delete');
});

require __DIR__.'/auth.php';
