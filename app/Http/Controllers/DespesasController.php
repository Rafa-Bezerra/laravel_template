<?php

namespace App\Http\Controllers;

use DateTime;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use App\Models\Pagamentos;
use App\Models\Bancos;
use DataTables;

class DespesasController extends Controller
{
    
    public function index(Request $request): View
    {
        $tittle = 'Despesas';
        
        $this->hasPermission('despesas',$tittle,true);
        $insert = $this->hasPermission('despesas_insert');
        $update = $this->hasPermission('despesas_update');
        $delete = $this->hasPermission('despesas_delete');

        return view('despesas.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'insert' => $insert,
            'update' => $update,
            'delete' => $delete,
        ]);
    }

    public function getListagem()
    {
        $listagem = Pagamentos::where('especie','despesa')->with('banco')->get();
        return datatables()->of($listagem)
            ->addColumn('banco_name', function ($pagamento) {
                if ($pagamento->banco) {
                    return "{$pagamento->banco->name} - {$pagamento->banco->agencia}-{$pagamento->banco->conta}";
                }
                return 'Sem banco';
            })
        ->toJson();
    }
    
    public function create(Request $request): View
    {        
               
        $tittle = 'Nova despesa';
        $this->hasPermission('despesas_insert',$tittle,true);
        $bancos = Bancos::all();
        return view('despesas.create', [
            'tittle' => $tittle,
            'user' => $request->user(),
            'bancos' => $bancos,
        ]);
    }    

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'banco_id' => ['required'],
            'valor' => ['required'],
            'data' => ['required'],
        ]);

        if($request->quantidade > 1) {
            $valor_parcela = round($request->valor / $request->quantidade, 2);
            $soma_parcelas = $valor_parcela * $request->quantidade;
            $resto = round($request->valor - $soma_parcelas, 2);
            $data_pagamento = DateTime::createFromFormat('d/m/Y', $request->data);
            for ($i=1; $i <= $request->quantidade; $i++) {
                $valor_parcela = ($i == $request->quantidade) ? ($valor_parcela + $resto) : $valor_parcela;
                $action = Pagamentos::create([
                    "orcamento_id" => $request->orcamento_id,
                    "controle" => $request->controle,
                    "banco_id" => $request->banco_id,
                    "name" => $request->name,
                    "tipo_pagamento" => $request->tipo_pagamento,
                    "data" => $data_pagamento->format('Y-m-d'),
                    "valor" => $valor_parcela,
                    "especie" => 'despesa',
                    "parcela" => $i,
                ]);
                event(new Registered($action));
                $data_pagamento->modify('+1 month');
            }
        } else {
            $action = Pagamentos::create([
                "orcamento_id" => $request->orcamento_id,
                "controle" => $request->controle,
                "banco_id" => $request->banco_id,
                "tipo_pagamento" => $request->tipo_pagamento,
                "name" => $request->name,
                "data" => DateTime::createFromFormat('d/m/Y', $request->data)->format('Y-m-d'),
                "valor" => $request->valor,
                "especie" => 'despesa',
                "parcela" => 1,
            ]);
        }

        event(new Registered($action));

        return redirect(route('despesas', absolute: false));
    }

    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Editar despesa';
        $this->hasPermission('despesas_update',$tittle,true);
        $data = Pagamentos::findOrFail($id);        
        $bancos = Bancos::all();

        return view('despesas.edit', [
            'tittle' => $tittle,
            'user' => $request->user(),
            'data' => $data,
            'bancos' => $bancos,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'banco_id' => ['required'],
            'valor' => ['required'],
            'data' => ['required'],
        ]);
        
        $action = Pagamentos::findOrFail($request->id);
        $action->valor = $request->valor;
        $action->controle = $request->controle;
        $action->banco_id = $request->banco_id;
        $action->tipo_pagamento = $request->tipo_pagamento;
        $action->name = $request->name;
        $action->data = DateTime::createFromFormat('d/m/Y', $request->data)->format('Y-m-d');
        $action->save();

        event(new Registered($action));

        return redirect(route('despesas', absolute: false));
    }

    public function delete(Request $request, string $id)
    {
        $data = Pagamentos::findOrFail($id)->delete();
        return redirect(route('despesas', absolute: false));
    }
}