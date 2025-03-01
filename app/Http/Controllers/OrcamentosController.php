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

class OrcamentosController extends Controller
{
    public function index(Request $request): View
    {
        $tittle = 'Orçamentos';
        
        if (! User::hasPermission('orcamentos')) return view('forbbiden', ['tittle' => $tittle]);

        return view('orcamentos.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
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
        if (! User::hasPermission('orcamentos_create')) return view('forbbiden', ['tittle' => $tittle]);
        
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
        ]);

        event(new Registered($action));

        return redirect('/orcamentos/edit/'.$action->id);
    }

    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Editar orçamento';
        if (! User::hasPermission('orcamentos_edit')) return view('forbbiden', ['tittle' => $tittle]);

        $data = Orcamentos::findOrFail($id);
        $empresas = Empresas::all();
        $empresas_enderecos = EmpresasEnderecos::where('empresa_id', $data->empresa_id)->get();
        $materiais = Materiais::all();
        $comissoes = Comissoes::all();
        $servicos = Servicos::all();
        // dd($empresas_enderecos);
        return view('orcamentos.edit', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'data' => $data,
            'empresas' => $empresas,
            'empresas_enderecos' => $empresas_enderecos,
            'materiais' => $materiais,
            'comissoes' => $comissoes,
            'servicos' => $servicos,
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
        $action->data_venda = DateTime::createFromFormat('d/m/Y', $request->data_venda)->format('Y-m-d');
        $action->data_prazo = DateTime::createFromFormat('d/m/Y', $request->data_prazo)->format('Y-m-d');
        $action->data_entrega = DateTime::createFromFormat('d/m/Y', $request->data_entrega)->format('Y-m-d');
        $action->observacao = $request->observacao;
        $action->save();

        event(new Registered($action));

        return;
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        OrcamentosItens::where('orcamento_id', $id)->delete();
        OrcamentosServicos::where('orcamento_id', $id)->delete();
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
            $quantidade = str_replace(['.', ','], ['', '.'], $request->item_quantidade);
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

    public function saida_estoque($material_id, $quantidade) {
        $estoque = Estoque::where('material_id', $material_id)->first();

        $estoque->quantidade -= $quantidade;
        $estoque->save();

        event(new Registered($estoque));

        return;
    }
    
    public function atualiza_total($orcamento_id) {
        $itens = OrcamentosItens::where('orcamento_id', $orcamento_id)->get();

        $valor_itens = 0;
        $valor_desconto = 0;
        $valor_total = 0;

        foreach ($itens as $key => $value) {
            $valor_itens += $value->quantidade * $value->preco_unitario;
            $valor_desconto += $value->valor_desconto;
            $valor_total += $value->valor_total;
        }

        $compra = Orcamentos::findOrFail($orcamento_id);
        $compra->valor_itens = $valor_itens;
        $compra->valor_desconto = $valor_desconto;
        $compra->valor_total = $valor_total;
        $compra->save();

        event(new Registered($compra));

        $this->atualiza_comissoes($orcamento_id);
        
        return $compra;
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

        $this->atualiza_comissoes($request->servico_orcamento_id);

        return;
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
        $this->atualiza_comissoes($orcamento);
        return;
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

        $this->atualiza_comissoes($request->comissao_orcamento_id);

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
        $this->atualiza_comissoes($orcamento);
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
    }
}