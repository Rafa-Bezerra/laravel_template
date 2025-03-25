<?php

namespace App\Http\Controllers;

use App\Http\Requests\MateriaisUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Auth\Events\Registered;
use DateTime;
use App\Models\User;
use App\Models\Orcamentos;
use App\Models\Empresas;
use App\Models\EmpresasEnderecos;
use App\Models\Materiais;
use App\Models\OrcamentosItens;
use App\Models\OrcamentosServicos;
use App\Models\Comissoes;
use App\Models\OrcamentosComissoes;
use App\Models\Servicos;
use App\Models\Estoque;
use App\Models\Pagamentos;
use App\Models\OrcamentosSocios;
use App\Models\Bancos;

class OrcamentosController extends Controller
{
    public function index(Request $request): View
    {
        $tittle = 'Obras';
        
        $this->hasPermission('orcamentos',$tittle,true);
        $insert = $this->hasPermission('orcamentos_insert');
        $update = $this->hasPermission('orcamentos_update');
        $delete = $this->hasPermission('orcamentos_delete');

        return view('orcamentos.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'insert' => $insert,
            'update' => $update,
            'delete' => $delete,
        ]);
    }

    public function getListagem()
    {
        $listagem = Orcamentos::all();
        return datatables()->of($listagem)
        ->addColumn('empresa_name', function ($empresa) {
            return $empresa->empresa->name ?? 'Sem empresa';
        })
        ->addColumn('empresas_endereco_descricao', function ($orcamento) {
            return $orcamento->endereco 
                ? $orcamento->endereco->rua . " - " . $orcamento->endereco->numero 
                : 'Sem endereço';
        })
        ->toJson();
    }

    public function create(Request $request): View
    {
        $tittle = 'Novo orçamento';

        $this->hasPermission('orcamentos_insert',$tittle,true);
        
        $empresas = Empresas::all();

        return view('orcamentos.create', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'empresas' => $empresas,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'empresa_id' => ['required'],
            'data_venda' => ['required'],
        ]);

        $action = Orcamentos::create([
            "empresa_id" => $request->empresa_id,
            "empresas_endereco_id" => $request->empresas_endereco_id,
            "data_venda" => DateTime::createFromFormat('d/m/Y', $request->data_venda)->format('Y-m-d'),
            "data_prazo" => DateTime::createFromFormat('d/m/Y', $request->data_prazo)->format('Y-m-d'),
            "observacao" => $request->observacao,
            "valor_orcamento" => $request->valor_orcamento,
            "controle" => 'pendente',
        ]);

        event(new Registered($action));

        return redirect('/orcamentos/edit/'.$action->id);
    }

    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Editar orçamento';

        $this->hasPermission('orcamentos_update',$tittle,true);
        
        $permissao_comissoes = $this->hasPermission('orcamentos_comissoes');
        $permissao_itens = $this->hasPermission('orcamentos_itens');
        $permissao_servicos = $this->hasPermission('orcamentos_servicos');
        $permissao_socios = $this->hasPermission('orcamentos_socios');
        $permissao_pagamentos = $this->hasPermission('orcamentos_pagamentos');

        $data = Orcamentos::findOrFail($id);
        $empresas = Empresas::all();
        $empresas_enderecos = EmpresasEnderecos::where('empresa_id', $data->empresa_id)->get();
        $materiais = Materiais::all();
        $comissoes = Comissoes::all();
        $servicos = Servicos::all();
        $bancos = Bancos::all();
        
        return view('orcamentos.edit', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'data' => $data,
            'empresas' => $empresas,
            'empresas_enderecos' => $empresas_enderecos,
            'materiais' => $materiais,
            'comissoes' => $comissoes,
            'servicos' => $servicos,
            'bancos' => $bancos,
            'permissao_comissoes' => $permissao_comissoes,
            'permissao_itens' => $permissao_itens,
            'permissao_servicos' => $permissao_servicos,
            'permissao_socios' => $permissao_socios,
            'permissao_pagamentos' => $permissao_pagamentos,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'empresa_id' => ['required'],
            'data_venda' => ['required'],
        ]);
        
        $action = Orcamentos::findOrFail($request->id);
        $action->empresa_id = $request->empresa_id;
        $action->empresas_endereco_id = $request->empresas_endereco_id;
        $action->data_venda = $request->data_venda != null ? DateTime::createFromFormat('d/m/Y', $request->data_venda)->format('Y-m-d') : $request->data_venda;
        $action->data_prazo = $request->data_prazo != null ? DateTime::createFromFormat('d/m/Y', $request->data_prazo)->format('Y-m-d') : $request->data_prazo;
        $action->data_entrega = $request->data_entrega != null ? DateTime::createFromFormat('d/m/Y', $request->data_entrega)->format('Y-m-d') : $request->data_entrega;
        $action->observacao = $request->observacao;
        $action->valor_orcamento = $request->valor_orcamento;
        $action->valor_impostos = $request->valor_impostos;
        $action->controle = $request->controle;
        $action->save();

        event(new Registered($action));
        
        $valores = $this->atualiza_total($request->id);
        return $valores->toJson();
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        OrcamentosItens::where('orcamento_id', $id)->delete();
        OrcamentosServicos::where('orcamento_id', $id)->delete();
        OrcamentosComissoes::where('orcamento_id', $id)->delete();
        OrcamentosSocios::where('orcamento_id', $id)->delete();
        Pagamentos::where('orcamento_id', $id)->delete();

        $data = Orcamentos::findOrFail($id)->delete();
        return redirect(route('orcamentos', absolute: false));
    }

    public function getEnderecos(Request $request) {
        $listagem = EmpresasEnderecos::where('empresa_id', $request->empresa_id);
        return datatables()->of($listagem)->toJson();
    }

    public function getListagemItens(Request $request)
    {
        $orcamento_id = $request->input('orcamento_id');
        $listagem = OrcamentosItens::where('orcamento_id', $orcamento_id);
        // dd($listagem->material);
        return datatables()->of($listagem)
            ->addColumn('material_name', function ($item) {
                return $item->material->name ?? 'Sem divisão';
            })
        ->toJson();
    }

    public function submitItens(Request $request)
    {
        $request->validate([
            'item_orcamento_id' => ['required'],
            'item_data' => ['required', 'string', 'max:255'],
            'item_material_id' => ['required', 'max:255'],
            'item_quantidade' => ['required'],
            'item_preco_unitario' => ['required'],
            'item_valor_total' => ['required'],
        ]);

        $quantidade = 0;
        if ($request->item_id == null) {
            // $quantidade = str_replace(['.', ','], ['', '.'], $request->item_quantidade);
            $quantidade = $request->item_quantidade;
            $quantidade_em_estoque = $this->valida_quantidade_em_estoque($request->item_material_id, $quantidade);
            $action = OrcamentosItens::create([
                "orcamento_id" => $request->item_orcamento_id,
                "data" => DateTime::createFromFormat('d/m/Y', $request->item_data)->format('Y-m-d'),
                "material_id" => $request->item_material_id,
                // "quantidade" => str_replace(['.', ','], ['', '.'], $request->item_quantidade),
                // "preco_unitario" => str_replace(['.', ','], ['', '.'], $request->item_preco_unitario),
                // "valor_desconto" => str_replace(['.', ','], ['', '.'], $request->item_valor_desconto),
                // "valor_total" => str_replace(['.', ','], ['', '.'], $request->item_valor_total),
                "quantidade" => $request->item_quantidade,
                "preco_unitario" => $request->item_preco_unitario,
                "valor_desconto" => $request->item_valor_desconto ? $request->item_valor_desconto : 0,
                "valor_total" => $request->item_valor_total,
                "observacao" => $request->item_observacao,
            ]);
        } else {
            $action = OrcamentosItens::findOrFail($request->item_id);
            $quantidade = $request->item_quantidade - $action->quantidade;
            $quantidade_em_estoque = $this->valida_quantidade_em_estoque($request->item_material_id,$quantidade);
            $action->orcamento_id = $request->item_orcamento_id;
            $action->data = DateTime::createFromFormat('d/m/Y', $request->item_data)->format('Y-m-d');
            $action->material_id = $request->item_material_id;
            $action->quantidade = $request->item_quantidade;
            $action->preco_unitario = $request->item_preco_unitario;
            $action->valor_desconto = $request->item_valor_desconto;
            $action->valor_total = $request->item_valor_total;
            $action->observacao = $request->item_observacao;
            $action->save();
        }

        $this->saida_estoque($request->item_material_id, $quantidade);

        $valores = $this->atualiza_total($request->item_orcamento_id);
        
        event(new Registered($action));

        return $valores->toJson();
    }

    public function valida_quantidade_em_estoque($material_id, $quantidade) {
        $estoque = Estoque::where('material_id', $material_id)->first();
        $quantidade_atualizada = $quantidade;
        if ($estoque->quantidade - $quantidade < 0) {
            $excedente = $estoque->quantidade - $quantidade;
            $quantidade_atualizada = $quantidade - $excedente;
        }
        return $quantidade_atualizada;
    }

    public function saida_estoque($material_id, $quantidade) {
        $estoque = Estoque::where('material_id', $material_id)->first();

        $estoque->quantidade -= $quantidade;
        $estoque->save();

        event(new Registered($estoque));

        return;
    }
    
    public function atualiza_total($orcamento_id) {
        
        $valor_itens = OrcamentosItens::where('orcamento_id', $orcamento_id)->sum('valor_total');
        $valor_desconto = OrcamentosItens::where('orcamento_id', $orcamento_id)->sum('valor_desconto');
        $valor_servicos = OrcamentosServicos::where('orcamento_id', $orcamento_id)->sum('preco');
        $valor_total = $valor_itens + $valor_servicos;

        $orcamento = Orcamentos::findOrFail($orcamento_id);
        $valor_total = round($valor_total * (1 + ($orcamento->valor_impostos/100)),2);
        $orcamento->valor_itens = $valor_itens;
        $orcamento->valor_desconto = $valor_desconto;
        $orcamento->valor_total = $valor_total;
        $orcamento->valor_servicos = $valor_servicos;
        $orcamento->valor_saldo = $orcamento->valor_orcamento - $valor_total;
        $orcamento->save();

        event(new Registered($orcamento));

        $this->atualiza_comissoes($orcamento_id);
        
        return $orcamento;
    }

    public function getItem(Request $request, string $id)
    {     
        return OrcamentosItens::findOrFail($id)->toJson();
    }

    public function deleteItem(Request $request, string $id)
    {
        $item = OrcamentosItens::findOrFail($id);
        $orcamento = $item->orcamento_id;
        $quantidade = $item->quantidade * -1;
        $this->saida_estoque($item->material_id, $quantidade);
        $item->delete();
        $valores = $this->atualiza_total($orcamento);
        return $valores->toJson();
    }

    public function getEstoqueMaterial(Request $request)
    {
        $listagem = Estoque::where('material_id', $request->material_id);
        return datatables()->of($listagem)->toJson();
    }

    public function getListagemServicos(Request $request)
    {
        $orcamento_id = $request->input('orcamento_id');
        $listagem = OrcamentosServicos::where('orcamento_id', $orcamento_id);
        // dd($listagem->material);
        return datatables()->of($listagem)
            ->addColumn('servico_name', function ($item) {
                return $item->servico->name ?? 'Sem serviço';
            })
        ->toJson();
    }

    public function submitServicos(Request $request)
    {
        $request->validate([
            'servico_servico_id' => ['required'],
            'servico_preco' => ['required'],
        ]);

        if ($request->servico_id == null) {
            $action = OrcamentosServicos::create([
                "orcamento_id" => $request->servico_orcamento_id,
                "servico_id" => $request->servico_servico_id,
                "preco" => $request->servico_preco,
            ]);
        } else {
            $action = OrcamentosServicos::findOrFail($request->servico_id);
            $action->orcamento_id = $request->servico_orcamento_id;
            $action->servico_id = $request->servico_servico_id;
            $action->preco = $request->servico_preco;
            $action->save();
        }

        event(new Registered($action));

        $this->atualiza_total($request->servico_orcamento_id);

        $valores = $this->atualiza_total($request->servico_orcamento_id);
        return $valores->toJson();
    }

    public function getServico(Request $request, string $id)
    {     
        return OrcamentosServicos::findOrFail($id)->toJson();
    }

    public function deleteServico(Request $request, string $id)
    {
        $servico = OrcamentosServicos::findOrFail($id);
        $orcamento = $servico->orcamento_id;
        $servico->delete();
        $this->atualiza_total($orcamento);

        $valores = $this->atualiza_total($orcamento);
        return $valores->toJson();
    }

    public function getListagemComissoes(Request $request)
    {
        $orcamento_id = $request->input('orcamento_id');
        $listagem = OrcamentosComissoes::where('orcamento_id', $orcamento_id);
        // dd($listagem->material);
        return datatables()->of($listagem)
            ->addColumn('comissao_name', function ($item) {
                return $item->comissao->name ?? 'Sem serviço';
            })
            ->addColumn('empresa_name', function ($item) {
                return $item->empresa->name ?? 'Sem serviço';
            })
        ->toJson();
    }

    public function submitComissoes(Request $request)
    {
        $request->validate([
            'comissao_empresa_id' => ['required'],
            'comissao_comissao_id' => ['required'],
            'comissao_porcentagem' => ['required'],
        ]);

        if ($request->comissao_id == null) {
            $action = OrcamentosComissoes::create([
                "orcamento_id" => $request->comissao_orcamento_id,
                "empresa_id" => $request->comissao_empresa_id,
                "comissao_id" => $request->comissao_comissao_id,
                "porcentagem" => $request->comissao_porcentagem,
                "valor_total" => 0,
            ]);
        } else {
            $action = OrcamentosComissoes::findOrFail($request->comissao_id);
            $action->orcamento_id = $request->comissao_orcamento_id;
            $action->empresa_id = $request->comissao_empresa_id;
            $action->comissao_id = $request->comissao_comissao_id;
            $action->porcentagem = $request->comissao_porcentagem;
            $action->save();
        }

        event(new Registered($action));

        $this->atualiza_total($request->comissao_orcamento_id);

        return;
    }

    public function getComissao(Request $request, string $id)
    {     
        return OrcamentosComissoes::findOrFail($id)->toJson();
    }

    public function deleteComissao(Request $request, string $id)
    {
        $servico = OrcamentosComissoes::findOrFail($id);
        $orcamento = $servico->orcamento_id;
        $servico->delete();
        $this->atualiza_total($orcamento);
        return;
    }

    public function atualiza_comissoes(int $orcamento)
    {
        $listagem = OrcamentosComissoes::where('orcamento_id', $orcamento)->get();
        
        $total_servicos = 0;
        $servicos = OrcamentosServicos::where('orcamento_id', $orcamento)->get();
        foreach ($servicos as $servicos_key => $servicos_value) {
            $total_servicos += $servicos_value->preco;
        }
        
        $total_itens = 0;
        $itens = OrcamentosItens::where('orcamento_id', $orcamento)->get();
        foreach ($itens as $itens_key => $itens_value) {
            $total_itens += $itens_value->valor_total;
        }
        
        foreach ($listagem as $key => $value) {
            $valor_total = 0;
            switch ($value->comissao_id) {
                case 1:
                    $valor_total = $total_servicos * ($value->porcentagem/100);
                    break;
                
                case 2:
                    $valor_total = $total_itens * ($value->porcentagem/100);
                    break;
                
                default:
                    $valor_total = ($total_servicos + $total_itens) * ($value->porcentagem/100);
                    break;
            }
            $value->valor_total = $valor_total;
            $value->save();
        }

        $this->atualiza_socios($orcamento);
    }

    public function getListagemSocios(Request $request)
    {
        $orcamento_id = $request->input('orcamento_id');
        $listagem = OrcamentosSocios::where('orcamento_id', $orcamento_id);
        // dd($listagem->material);
        return datatables()->of($listagem)
            ->addColumn('empresa_name', function ($item) {
                return $item->empresa->name ?? 'Sem serviço';
            })
        ->toJson();
    }

    public function submitSocios(Request $request)
    {
        $request->validate([
            'socio_empresa_id' => ['required'],
            'socio_porcentagem' => ['required'],
        ]);

        if ($request->socio_id == null) {
            $action = OrcamentosSocios::create([
                "orcamento_id" => $request->socio_orcamento_id,
                "empresa_id" => $request->socio_empresa_id,
                "porcentagem" => $request->socio_porcentagem,
                "valor_total" => 0,
            ]);
        } else {
            $action = OrcamentosSocios::findOrFail($request->socio_id);
            $action->orcamento_id = $request->socio_orcamento_id;
            $action->empresa_id = $request->socio_empresa_id;
            $action->porcentagem = $request->socio_porcentagem;
            $action->save();
        }

        event(new Registered($action));

        $this->atualiza_total($request->socio_orcamento_id);

        return;
    }

    public function getSocio(Request $request, string $id)
    {     
        return OrcamentosSocios::findOrFail($id)->toJson();
    }

    public function deleteSocio(Request $request, string $id)
    {
        $servico = OrcamentosSocios::findOrFail($id);
        $orcamento = $servico->orcamento_id;
        $servico->delete();
        $this->atualiza_total($orcamento);
        return;
    }

    public function atualiza_socios(int $orcamento)
    {
        $orcamento = Orcamentos::findOrFail($orcamento);
        
        $total_recebido = Pagamentos::where('orcamento_id', $orcamento->id)->where('controle', 'pago')->sum('valor');
        $lucro = $total_recebido - $orcamento->valor_total;
        $listagem = OrcamentosSocios::where('orcamento_id', $orcamento->id)->get();
        // dd($lucro);
        foreach ($listagem as $key => $value) {
            $value->valor_total = $lucro * ($value->porcentagem/100);
            $value->save();
        }
    }

    public function getListagemPagamentos(Request $request)
    {
        $orcamento_id = $request->input('orcamento_id');
        $listagem = Pagamentos::where('orcamento_id', $orcamento_id)->with('banco')->get();
        // dd($listagem);
        return datatables()->of($listagem)
            ->addColumn('banco_name', function ($pagamento) {
                if ($pagamento->banco) {
                    return "{$pagamento->banco->name} - {$pagamento->banco->agencia}-{$pagamento->banco->conta}";
                }
                return 'Sem banco';
            })
        ->toJson();
    }

    public function submitPagamentos(Request $request)
    {
        $request->validate([
            'pagamento_valor' => ['required'],
            'pagamento_quantidade' => ['required'],
            'pagamento_data' => ['required'],
            'pagamento_banco_id' => ['required'],
        ]);

        if ($request->pagamento_id == null) {
            if($request->pagamento_quantidade > 1) {
                $valor_parcela = round($request->pagamento_valor / $request->pagamento_quantidade, 2);
                $soma_parcelas = $valor_parcela * $request->pagamento_quantidade;
                $resto = round($request->pagamento_valor - $soma_parcelas, 2);
                $data_pagamento = DateTime::createFromFormat('d/m/Y', $request->pagamento_data);
                for ($i=1; $i <= $request->pagamento_quantidade; $i++) {
                    $valor_parcela = ($i == $request->pagamento_quantidade) ? ($valor_parcela + $resto) : $valor_parcela;
                    $action = Pagamentos::create([
                        "orcamento_id" => $request->pagamento_orcamento_id,
                        "controle" => $request->pagamento_controle,
                        "banco_id" => $request->pagamento_banco_id,
                        "tipo_pagamento" => $request->pagamento_tipo_pagamento,
                        "data" => $data_pagamento->format('Y-m-d'),
                        "valor" => $valor_parcela,
                        "especie" => 'venda',
                        "parcela" => $i,
                    ]);
                    event(new Registered($action));
                    $data_pagamento->modify('+1 month');
                }
            } else {
                $action = Pagamentos::create([
                    "orcamento_id" => $request->pagamento_orcamento_id,
                    "controle" => $request->pagamento_controle,
                    "banco_id" => $request->pagamento_banco_id,
                    "tipo_pagamento" => $request->pagamento_tipo_pagamento,
                    "data" => DateTime::createFromFormat('d/m/Y', $request->pagamento_data)->format('Y-m-d'),
                    "valor" => $request->pagamento_valor,
                    "especie" => 'venda',
                    "parcela" => 1,
                ]);
                event(new Registered($action));
            }
        } else {
            $action = Pagamentos::findOrFail($request->pagamento_id);
            $action->valor = $request->pagamento_valor;
            $action->controle = $request->pagamento_controle;
            $action->banco_id = $request->pagamento_banco_id;
            $action->tipo_pagamento = $request->pagamento_tipo_pagamento;
            $action->data = DateTime::createFromFormat('d/m/Y', $request->pagamento_data)->format('Y-m-d');
            $action->save();
            event(new Registered($action));
        }

        $valores = $this->atualiza_total($request->pagamento_orcamento_id);
        return $valores->toJson();
    }

    public function getPagamento(Request $request, string $id)
    {     
        return Pagamentos::findOrFail($id)->toJson();
    }

    public function deletePagamento(Request $request, string $id)
    {
        $pagamento = Pagamentos::findOrFail($id);
        $pagamento->delete();
        $valores = $this->atualiza_total($pagamento->orcamento_id);
        return $valores->toJson();
    }
}