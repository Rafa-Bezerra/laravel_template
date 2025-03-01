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

class ComprasController extends Controller
{
    public function index(Request $request): View
    {
        $tittle = 'Compras';
        
        if (! User::hasPermission('compras')) return view('forbbiden', ['tittle' => $tittle]);

        return view('compras.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
        ]);
    }

    public function getListagem()
    {
        $listagem = Compras::all();
        return datatables()->of($listagem)->toJson();
    }

    public function create(Request $request): View
    {
        $tittle = 'Nova compra';
        if (! User::hasPermission('compras_create')) return view('forbbiden', ['tittle' => $tittle]);

        return view('compras.create', [
            'user' => $request->user(),
            'tittle' => $tittle,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'data_compra' => ['required'],
        ]);
        
        $action = Compras::create([
            "orcamento_id" => $request->orcamento_id,
            "data_compra" => $request->data_compra ? DateTime::createFromFormat('d/m/Y', $request->data_compra)->format('Y-m-d') : null,
            "data_prazo" => $request->data_prazo ? DateTime::createFromFormat('d/m/Y', $request->data_prazo)->format('Y-m-d') : null,
            "data_entrega" => $request->data_entrega ? DateTime::createFromFormat('d/m/Y', $request->data_entrega)->format('Y-m-d') : null,
            "valor_itens" => $request->valor_itens,
            "valor_desconto" => $request->valor_desconto,
            "valor_total" => $request->valor_total,
            "observacao" => $request->observacao,
        ]);
        
        event(new Registered($action));

        return redirect('/compras/edit/'.$action->id);
    }

    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Editar compra N°'.$id;
        if (! User::hasPermission('materiais_edit')) return view('forbbiden', ['tittle' => $tittle]);
        
        $materiais = Materiais::all();
        $data = Compras::findOrFail($id);
        return view('compras.edit', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'materiais' => $materiais,
            'data' => $data,
        ]);
    }

    public function update(Request $request): RedirectResponse
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
        $action->save();

        event(new Registered($action));

        return redirect(route('materiais.index', absolute: false));
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        ComprasItens::where('compra_id', $id)->delete();
        $data = Compras::findOrFail($id)->delete();
        return redirect(route('compras.index', absolute: false));
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
            'item_material_id' => ['required', 'max:255'],
            'item_quantidade' => ['required'],
            'item_preco_unitario' => ['required'],
            'item_valor_total' => ['required'],
        ]);

        $quantidade = 0;
        if ($request->item_id == null) {
            $quantidade = str_replace(['.', ','], ['', '.'], $request->item_quantidade);
            $action = ComprasItens::create([
                "compra_id" => $request->item_compra_id,
                "data" => DateTime::createFromFormat('d/m/Y', $request->item_data)->format('Y-m-d'),
                "material_id" => $request->item_material_id,
                // "quantidade" => str_replace(['.', ','], ['', '.'], $request->item_quantidade),
                // "preco_unitario" => str_replace(['.', ','], ['', '.'], $request->item_preco_unitario),
                // "valor_desconto" => str_replace(['.', ','], ['', '.'], $request->item_valor_desconto),
                // "valor_total" => str_replace(['.', ','], ['', '.'], $request->item_valor_total),
                "quantidade" => $request->item_quantidade,
                "preco_unitario" => $request->item_preco_unitario,
                "valor_desconto" => $request->item_valor_desconto,
                "valor_total" => $request->item_valor_total,
                "observacao" => $request->item_observacao,
            ]);
        } else {
            $action = ComprasItens::findOrFail($request->item_id);
            $quantidade = $request->item_quantidade - $action->quantidade;
            $action->compra_id = $request->item_compra_id;
            $action->data = DateTime::createFromFormat('d/m/Y', $request->item_data)->format('Y-m-d');
            $action->material_id = $request->item_material_id;
            $action->quantidade = $request->item_quantidade;
            $action->preco_unitario = $request->item_preco_unitario;
            $action->valor_desconto = $request->item_valor_desconto;
            $action->valor_total = $request->item_valor_total;
            $action->observacao = $request->item_observacao;
            $action->save();
        }

        $this->entrada_estoque($request->item_material_id, $quantidade);

        $valores = $this->atualiza_total($request->item_compra_id);

        event(new Registered($action));

        return $valores->toJson();
    }

    public function entrada_estoque($material_id, $quantidade) {
        $estoque = Estoque::where('material_id', $material_id)->first();

        if(!$estoque) {
            $estoque = Estoque::create([
                "material_id" => $material_id,
                "quantidade" => $quantidade,
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
        $this->saida_estoque($item->material_id, $quantidade);
        $item->delete();
        $valores = $this->atualiza_total($compra);
        return $valores->toJson();
    }
}