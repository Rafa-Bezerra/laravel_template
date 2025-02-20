<?php

namespace App\Http\Controllers;

use App\Http\Requests\MateriaisUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Models\Empresas;
use App\Models\EmpresasContatos;
use App\Models\EmpresasEnderecos;
use App\Models\Divisoes;

class EmpresasController extends Controller
{
    public function index(Request $request): View
    {
        $tittle = 'Empresas';
        
        if (! User::hasPermission('empresas')) return view('forbbiden', ['tittle' => $tittle]);

        return view('empresas.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
        ]);
    }

    public function getListagem()
    {
        $listagem = Empresas::all();
        return datatables()->of($listagem)->toJson();
    }

    public function create(Request $request): View
    {
        $tittle = 'Nova empresa';
        if (! User::hasPermission('empresas_create')) return view('forbbiden', ['tittle' => $tittle]);

        $divisoes = Divisoes::all();
        return view('empresas.create', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'divisoes' => $divisoes,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'fantasia' => ['required', 'string', 'max:255'],
            'pessoa' => ['required'],
            'divisao_id' => ['string', 'max:255'],
            'observacao' => ['string', 'max:255'],
        ]);

        $action = Empresas::create([
            "name" => $request->name,
            "fantasia" => $request->fantasia,
            "pessoa" => $request->pessoa,
            "cpf" => $request->cpf,
            "cnpj" => $request->cnpj,
            "divisao_id" => $request->divisao_id,
            "observacao" => $request->observacao,
        ]);

        event(new Registered($action));

        return redirect(route('empresas.index', absolute: false));
    }

    public function registerContatos(Request $request)
    {
        $request->validate([
            'empresa_id' => ['required'],
            'contato' => ['required', 'string', 'max:255'],
            'fone' => ['required', 'max:255'],
            'email' => ['string', 'max:255'],
            'observacao' => ['string', 'max:255'],
        ]);

        $action = EmpresasContatos::create([
            "empresa_id" => $request->empresa_id,
            "contato" => $request->contato,
            "fone" => $request->fone,
            "email" => $request->email,
            "observacao" => $request->observacao,
        ]);

        event(new Registered($action));

        return;
    }

    public function registerEnderecos(Request $request)
    {
        $request->validate([
            'empresa_id' => ['required'],
            'cep' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'max:255'],
            'cidade' => ['string', 'max:255'],
            'rua' => ['string', 'max:255'],
            'numero' => ['required', 'max:255'],
            'complemento' => ['string', 'max:255'],
            'observacao' => ['string', 'max:255'],
        ]);

        $action = EmpresasEnderecos::create([
            "empresa_id" => $request->empresa_id,
            "cep" => $request->cep,
            "estado" => $request->estado,
            "cidade" => $request->cidade,
            "rua" => $request->rua,
            "numero" => $request->numero,
            "complemento" => $request->complemento,
            "observacao" => $request->observacao,
        ]);

        event(new Registered($action));

        return;
    }

    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Editar empresa';
        if (! User::hasPermission('empresas_edit')) return view('forbbiden', ['tittle' => $tittle]);
        
        $data = Empresas::findOrFail($id);
        
        return view('empresas.edit', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'data' => $data,
        ]);
    }

    public function update(Request $request)
    {
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'fantasia' => ['required', 'string', 'max:255'],
            'pessoa' => ['required'],
            'cpf' => ['string', 'max:255'],
            'cnpj' => ['string', 'max:255'],
            'divisao_id' => ['string', 'max:255'],
            'observacao' => ['string', 'max:255'],
        ]);
        
        $action = Empresas::findOrFail($request->id);
        $action->name = $request->name;
        $action->fantasia = $request->fantasia;
        $action->pessoa = $request->pessoa;
        $action->cpf = $request->cpf;
        $action->cnpj = $request->cnpj;
        $action->divisao_id = $request->divisao_id;
        $action->observacao = $request->observacao;
        $action->save();

        event(new Registered($action));

        // return redirect(route('empresas.index', absolute: false));
        return;
    }

    public function updateContatos(Request $request)
    {
        
        $request->validate([
            'empresa_id' => ['required'],
            'contato' => ['required', 'string', 'max:255'],
            'fone' => ['required', 'max:255'],
            'email' => ['string', 'max:255'],
            'observacao' => ['string', 'max:255'],
        ]);
        
        $action = EmpresasContatos::findOrFail($request->id);
        $action->empresa_id = $request->empresa_id;
        $action->contato = $request->contato;
        $action->fone = $request->fone;
        $action->email = $request->email;
        $action->observacao = $request->observacao;
        $action->save();

        event(new Registered($action));

        // return redirect(route('empresas.index', absolute: false));
        return;
    }

    public function updateEnderecos(Request $request)
    {
        $request->validate([
            'empresa_id' => ['required'],
            'cep' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'max:255'],
            'cidade' => ['string', 'max:255'],
            'rua' => ['string', 'max:255'],
            'numero' => ['required', 'max:255'],
            'complemento' => ['string', 'max:255'],
            'observacao' => ['string', 'max:255'],
        ]);
        
        $action = EmpresasEnderecos::findOrFail($request->id);
        $action->empresa_id = $request->empresa_id;
        $action->cep = $request->cep;
        $action->estado = $request->estado;
        $action->cidade = $request->cidade;
        $action->rua = $request->rua;
        $action->numero = $request->numero;
        $action->complemento = $request->complemento;
        $action->observacao = $request->observacao;
        $action->save();

        event(new Registered($action));

        // return redirect(route('empresas.index', absolute: false));
        return;
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        EmpresasContatos::where('empresa_id', $id)->delete();
        EmpresasEmpresas::where('empresa_id', $id)->delete();
        $data = Empresas::findOrFail($id)->delete();
        return redirect(route('empresas.index', absolute: false));
    }

    public function deleteContatos(Request $request, string $id): RedirectResponse
    {
        return EmpresasContatos::findOrFail($id)->delete();
    }

    public function deleteEmpresas(Request $request, string $id): RedirectResponse
    {
        return EmpresasEmpresas::findOrFail($id)->delete();
    }
}