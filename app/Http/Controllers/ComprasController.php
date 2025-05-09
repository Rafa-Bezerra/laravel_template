<?php

namespace App\Http\Controllers;

use DateTime; 
use App\Http\Requests\MateriaisUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Models\Materiais;
use App\Models\Compras;
use App\Models\ComprasItens;
use App\Models\Estoque;
use App\Models\Pagamentos;
use App\Models\Empresas;
use App\Models\GruposDeMaterial;
use Carbon\Carbon;

class ComprasController extends Controller
{
    public function index(Request $request): View
    {
        $tittle = 'Compras';
        
        $this->hasPermission('compras',$tittle,true);
        $insert = $this->hasPermission('compras_insert');
        $update = $this->hasPermission('compras_update');
        $delete = $this->hasPermission('compras_delete');
        
        $empresas = Empresas::all();

        return view('compras.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'insert' => $insert,
            'update' => $update,
            'delete' => $delete,
            'empresas' => $empresas,
        ]);
    }

    public function getListagem(Request $request)
    {
        $listagem = Compras::query();
        // dd($request->filtro_empresa_id);
        if ($request->filled('filtro_observacao')) {
            $listagem->where('observacao', 'like', '%' . $request->filtro_observacao . '%');
        }

        if ($request->filled('filtro_data_de') && $request->filled('filtro_data_ate')) {
            $dataDe = Carbon::createFromFormat('d/m/Y', $request->filtro_data_de)->startOfDay();
            $dataAte = Carbon::createFromFormat('d/m/Y', $request->filtro_data_ate)->endOfDay();

            $listagem->whereBetween('data_compra', [$dataDe, $dataAte]);
        }

        if ($request->filled('filtro_data_prazo_de') && $request->filled('filtro_data_prazo_ate')) {
            $dataDe = Carbon::createFromFormat('d/m/Y', $request->filtro_data_prazo_de)->startOfDay();
            $dataAte = Carbon::createFromFormat('d/m/Y', $request->filtro_data_prazo_ate)->endOfDay();

            $listagem->whereBetween('data_entrega', [$dataDe, $dataAte]);
        }

        if ($request->filled('filtro_data_entrega_de') && $request->filled('filtro_data_entrega_ate')) {
            $dataDe = Carbon::createFromFormat('d/m/Y', $request->filtro_data_entrega_de)->startOfDay();
            $dataAte = Carbon::createFromFormat('d/m/Y', $request->filtro_data_entrega_ate)->endOfDay();

            $listagem->whereBetween('data_entrega', [$dataDe, $dataAte]);
        }

        if ($request->filled('filtro_empresa_id')) {
            $listagem->whereHas('empresa', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->filtro_empresa_id . '%');
            });
        }

        return datatables()->of($listagem)
            ->addColumn('empresa_name', function ($orcamento) {
                return $orcamento->empresa->name ?? 'Sem empresa';
            })
            ->toJson();
    }

    public function create(Request $request): View
    {
        $tittle = 'Nova compra';
        
        $empresas = Empresas::all();

        return view('compras.create', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'empresas' => $empresas,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'data_compra' => ['required'],
        ]);
        // dd($request->empresa_id);
        $action = Compras::create([
            "orcamento_id" => $request->orcamento_id,
            "data_compra" => $request->data_compra ? DateTime::createFromFormat('d/m/Y', $request->data_compra)->format('Y-m-d') : null,
            "data_prazo" => $request->data_prazo ? DateTime::createFromFormat('d/m/Y', $request->data_prazo)->format('Y-m-d') : null,
            "data_entrega" => $request->data_entrega ? DateTime::createFromFormat('d/m/Y', $request->data_entrega)->format('Y-m-d') : null,
            "observacao" => $request->observacao,
            "empresa_id" => $request->empresa_id,
        ]);
        
        event(new Registered($action));

        return redirect('/compras/edit/'.$action->id);
    }

    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Editar compra N°'.$id;
        

        $this->hasPermission('compras_update',$tittle,true);
        $itens = $this->hasPermission('compras_itens');
        $pagamentos = $this->hasPermission('compras_pagamentos');

        $empresas = Empresas::all();
        $grupos_de_material = GruposDeMaterial::all();
        $materiais = Materiais::all();
        $data = Compras::findOrFail($id);
        
        return view('compras.edit', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'materiais' => $materiais,
            'data' => $data,
            'itens' => $itens,
            'pagamentos' => $pagamentos,
            'grupos_de_material' => $grupos_de_material,
            'empresas' => $empresas,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'data_compra' => ['required'],
        ]);
        
        $action = Compras::findOrFail($request->id);
        $action->orcamento_id = $request->orcamento_id;
        $action->data_compra = $request->data_compra ? DateTime::createFromFormat('d/m/Y', $request->data_compra)->format('Y-m-d') : null;
        $action->data_prazo = $request->data_prazo ? DateTime::createFromFormat('d/m/Y', $request->data_prazo)->format('Y-m-d') : null;
        $action->data_entrega = $request->data_entrega ? DateTime::createFromFormat('d/m/Y', $request->data_entrega)->format('Y-m-d') : null;
        $action->observacao = $request->observacao;
        $action->empresa_id = $request->empresa_id;
        $action->save();

        $valores = $this->atualiza_total($request->id);

        event(new Registered($action));

        return $valores->toJson();
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        ComprasItens::where('compra_id', $id)->delete();
        Pagamentos::where('compra_id', $id)->delete();
        $data = Compras::findOrFail($id)->delete();
        return redirect(route('compras', absolute: false));
    }

    public function getListagemItens(Request $request)
    {
        $compra_id = $request->input('compra_id');
        $listagem = ComprasItens::where('compra_id', $compra_id);
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
            'item_compra_id' => ['required'],
            'item_data' => ['required', 'string', 'max:255'],
            'item_quantidade' => ['required'],
            'item_preco_unitario' => ['required'],
            'item_valor_total' => ['required'],
        ]);

        $material_id = $request->item_material_id;

        if ($request->item_novo_material == true) {
            $action = Materiais::create([
                "name" => $request->item_name,
                "unidade_de_medida" => $request->item_unidade_de_medida,
                "grupo_de_material_id" => $request->item_grupo_de_material_id,
            ]);
            $material_id = $action->id;
        }
        
        $item_quantidade = desformatarNumerico($request->item_quantidade);
        $item_preco_unitario = desformatarDinheiro($request->item_preco_unitario);
        $item_valor_desconto = desformatarDinheiro($request->item_valor_desconto);
        $item_valor_total = desformatarDinheiro($request->item_valor_total);

        $quantidade = 0;
        $preco = 0;
        if ($request->item_id == null) {
            $quantidade = $item_quantidade;
            $preco = $item_preco_unitario;
            $action = ComprasItens::create([
                "compra_id" => $request->item_compra_id,
                "data" => DateTime::createFromFormat('d/m/Y', $request->item_data)->format('Y-m-d'),
                "material_id" => $material_id,
                "quantidade" => $item_quantidade,
                "preco_unitario" => $item_preco_unitario,
                "valor_desconto" => $item_valor_desconto,
                "valor_total" => $item_valor_total,
                "observacao" => $request->item_observacao,
            ]);
        } else {
            $action = ComprasItens::findOrFail($request->item_id);
            $quantidade = $item_quantidade - $action->quantidade;
            $preco = $item_preco_unitario;
            $action->compra_id = $request->item_compra_id;
            $action->data = DateTime::createFromFormat('d/m/Y', $request->item_data)->format('Y-m-d');
            $action->material_id = $material_id;
            $action->quantidade = $item_quantidade;
            $action->preco_unitario = $item_preco_unitario;
            $action->valor_desconto = $item_valor_desconto;
            $action->valor_total = $item_valor_total;
            $action->observacao = $request->item_observacao;
            $action->save();
        }

        $this->entrada_estoque($material_id, $quantidade, $preco);

        $valores = $this->atualiza_total($request->item_compra_id);

        event(new Registered($action));

        return $valores->toJson();
    }

    public function entrada_estoque($material_id, $quantidade, $valor) {
        $estoque = Estoque::where('material_id', $material_id)->first();

        if(!$estoque) {
            $estoque = Estoque::create([
                "material_id" => $material_id,
                "quantidade" => $quantidade,
                "valor" => $valor,
                "orcamento_id" => null,
            ]);
        } else {
            $estoque->quantidade += $quantidade;
            $estoque->save();
        }

        event(new Registered($estoque));

        return;
    }
    
    public function atualiza_total($compra_id) {
        $itens = ComprasItens::where('compra_id', $compra_id)->get();

        $valor_itens = 0;
        $valor_desconto = 0;
        $valor_total = 0;

        foreach ($itens as $key => $value) {
            $valor_itens += $value->quantidade * $value->preco_unitario;
            $valor_desconto += $value->valor_desconto;
            $valor_total += $value->valor_total;
        }

        $compra = Compras::findOrFail($compra_id);
        $compra->valor_itens = $valor_itens;
        $compra->valor_desconto = $valor_desconto;
        $compra->valor_total = $valor_total;
        $compra->save();

        event(new Registered($compra));
        
        return $compra;
    }

    public function getItem(Request $request, string $id)
    {     
        return ComprasItens::findOrFail($id)->toJson();
    }

    public function deleteItem(Request $request, string $id)
    {
        $item = ComprasItens::findOrFail($id);
        $compra = $item->compra_id;
        $quantidade = $item->quantidade * -1;
        $this->entrada_estoque($item->material_id, $quantidade);
        $item->delete();
        $valores = $this->atualiza_total($compra);
        return $valores->toJson();
    }

    public function getListagemPagamentos(Request $request)
    {
        $compra_id = $request->input('compra_id');
        $listagem = Pagamentos::where('compra_id', $compra_id)->get();
        // dd($listagem);
        return datatables()->of($listagem)->toJson();
    }

    public function submitPagamentos(Request $request)
    {
        $request->validate([
            'pagamento_valor' => ['required'],
            'pagamento_quantidade' => ['required'],
            'pagamento_data' => ['required'],
        ]);
        
        $pagamento_valor = desformatarDinheiro($request->pagamento_valor);

        if ($request->pagamento_id == null) {
            if($request->pagamento_quantidade > 1) {
                $valor_parcela = round($pagamento_valor / $request->pagamento_quantidade, 2);
                $soma_parcelas = $valor_parcela * $request->pagamento_quantidade;
                $resto = round($pagamento_valor - $soma_parcelas, 2);
                $data_pagamento = DateTime::createFromFormat('d/m/Y', $request->pagamento_data);
                for ($i=1; $i <= $request->pagamento_quantidade; $i++) {
                    $valor_parcela = ($i == $request->pagamento_quantidade) ? ($valor_parcela + $resto) : $valor_parcela;
                    $action = Pagamentos::create([
                        "compra_id" => $request->pagamento_compra_id,
                        "tipo_pagamento" => $request->pagamento_tipo_pagamento,
                        "controle" => $request->pagamento_controle,
                        "data" => $data_pagamento->format('Y-m-d'),
                        "valor" => $valor_parcela,
                        "especie" => 'compra',
                        "parcela" => $i,
                    ]);
                    event(new Registered($action));
                    $data_pagamento->modify('+1 month');
                }
            } else {
                $action = Pagamentos::create([
                    "compra_id" => $request->pagamento_compra_id,
                    "tipo_pagamento" => $request->pagamento_tipo_pagamento,
                    "controle" => $request->pagamento_controle,
                    "data" => DateTime::createFromFormat('d/m/Y', $request->pagamento_data)->format('Y-m-d'),
                    "valor" => $pagamento_valor,
                    "especie" => 'venda',
                    "parcela" => 1,
                ]);
                event(new Registered($action));
            }
        } else {
            $action = Pagamentos::findOrFail($request->pagamento_id);
            $action->valor = $pagamento_valor;
            $action->controle = $request->pagamento_controle;
            $action->tipo_pagamento = $request->pagamento_tipo_pagamento;
            $action->data = DateTime::createFromFormat('d/m/Y', $request->pagamento_data)->format('Y-m-d');
            $action->save();
            event(new Registered($action));
        }

        return;
    }

    public function getPagamento(Request $request, string $id)
    {     
        return Pagamentos::findOrFail($id)->toJson();
    }

    public function deletePagamento(Request $request, string $id)
    {
        $pagamento = Pagamentos::findOrFail($id);
        return $pagamento->delete();
    }
}