<?php

namespace Database\Seeders;

use App\Models\Actions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateAction extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles_actions')->delete(); // Remove registros dependentes
        DB::table('actions')->delete(); // Agora pode deletar sem erros
        DB::statement('ALTER TABLE actions AUTO_INCREMENT = 1;'); // Reseta o ID

        Actions::insert([
            ['name' => 'Ações','route' => 'actions'],
            ['name' => 'Ações - inserir','route' => 'actions_insert'],
            ['name' => 'Ações - editar','route' => 'actions_update'],
            ['name' => 'Ações - excluir','route' => 'actions_delete'],
            ['name' => 'Comissões','route' => 'comissoes'],
            ['name' => 'Comissões - inserir','route' => 'comissoes_insert'],
            ['name' => 'Comissões - editar','route' => 'comissoes_update'],
            ['name' => 'Comissões - excluir','route' => 'comissoes_delete'],
            ['name' => 'Compras','route' => 'compras'],
            ['name' => 'Compras - inserir','route' => 'compras_insert'],
            ['name' => 'Compras - editar','route' => 'compras_update'],
            ['name' => 'Compras - excluir','route' => 'compras_delete'],
            ['name' => 'Compras - itens','route' => 'compras_itens'],
            ['name' => 'Compras - pagamentos','route' => 'compras_pagamentos'],
            ['name' => 'Divisões','route' => 'divisoes'],
            ['name' => 'Divisões - inserir','route' => 'divisoes_insert'],
            ['name' => 'Divisões - editar','route' => 'divisoes_update'],
            ['name' => 'Divisões - excluir','route' => 'divisoes_delete'],
            ['name' => 'Empresas','route' => 'empresas'],
            ['name' => 'Empresas - inserir','route' => 'empresas_insert'],
            ['name' => 'Empresas - editar','route' => 'empresas_update'],
            ['name' => 'Empresas - excluir','route' => 'empresas_delete'],
            ['name' => 'Empresas - contatos','route' => 'empresas_contatos'],
            ['name' => 'Empresas - contatos','route' => 'empresas_enderecos'],
            ['name' => 'Estoque','route' => 'estoque'],
            ['name' => 'Estoque - editar','route' => 'estoque_update'],
            ['name' => 'Grupos de material','route' => 'grupos_de_material'],
            ['name' => 'Grupos de material - inserir','route' => 'grupos_de_material_insert'],
            ['name' => 'Grupos de material - editar','route' => 'grupos_de_material_update'],
            ['name' => 'Grupos de material - excluir','route' => 'grupos_de_material_delete'],
            ['name' => 'Materiais','route' => 'materiais'],
            ['name' => 'Materiais - inserir','route' => 'materiais_insert'],
            ['name' => 'Materiais - editar','route' => 'materiais_update'],
            ['name' => 'Materiais - excluir','route' => 'materiais_delete'],
            ['name' => 'Orçamentos','route' => 'orcamentos'],
            ['name' => 'Orçamentos - inserir','route' => 'orcamentos_insert'],
            ['name' => 'Orçamentos - editar','route' => 'orcamentos_update'],
            ['name' => 'Orçamentos - excluir','route' => 'orcamentos_delete'],
            ['name' => 'Orçamentos - comissoes','route' => 'orcamentos_comissoes'],
            ['name' => 'Orçamentos - itens','route' => 'orcamentos_itens'],
            ['name' => 'Orçamentos - serviços','route' => 'orcamentos_servicos'],
            ['name' => 'Orçamentos - sócios','route' => 'orcamentos_socios'],
            ['name' => 'Orçamentos - pagamentos','route' => 'orcamentos_pagamentos'],
            ['name' => 'Relatórios','route' => 'relatorios'],
            ['name' => 'Relatório - Orçamentos por cliente','route' => 'relatorios_orcamentos_por_cliente'],
            ['name' => 'Acessos','route' => 'roles'],
            ['name' => 'Acessos - inserir','route' => 'roles_insert'],
            ['name' => 'Acessos - editar','route' => 'roles_update'],
            ['name' => 'Acessos - excluir','route' => 'roles_delete'],
            ['name' => 'Acessos - ações','route' => 'roles_actions'],
            ['name' => 'Serviços','route' => 'servicos'],
            ['name' => 'Serviços - inserir','route' => 'servicos_insert'],
            ['name' => 'Serviços - editar','route' => 'servicos_update'],
            ['name' => 'Serviços - excluir','route' => 'servicos_delete'],
            ['name' => 'Usuários','route' => 'users'],
            ['name' => 'Usuários - inserir','route' => 'users_insert'],
            ['name' => 'Usuários - editar','route' => 'users_update'],
            ['name' => 'Usuários - excluir','route' => 'users_delete'],
            ['name' => 'Usuários - acessos','route' => 'users_roles'],
            ['name' => 'Bancos','route' => 'bancos'],
            ['name' => 'Bancos - inserir','route' => 'bancos_insert'],
            ['name' => 'Bancos - editar','route' => 'bancos_update'],
            ['name' => 'Bancos - excluir','route' => 'bancos_delete'],
            ['name' => 'Dashboard - pagamentos','route' => 'dashboard_pagamentos'],
            ['name' => 'Dashboard - movimentações','route' => 'dashboard_movimentacoes'],
        ]);
    }
}
