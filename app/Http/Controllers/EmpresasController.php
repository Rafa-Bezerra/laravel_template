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
        
        $this->hasPermission('empresas',$tittle,true);
        $insert = $this->hasPermission('empresas_insert');
        $update = $this->hasPermission('empresas_update');
        $delete = $this->hasPermission('empresas_delete');
        // dd($update);
        return view('empresas.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'insert' => $insert,
            'update' => $update,
            'delete' => $delete,
        ]);
    }

    public function getListagem()
    {
        $listagem = Empresas::with('divisao')->get();
        return datatables()->of($listagem)
            ->addColumn('divisao_name', function ($empresa) {
                return $empresa->divisao->name ?? 'Sem divisão';
            })
        ->toJson();
    }

    public function getListagemContatos(Request $request)
    {
        $empresa_id = $request->input('empresa_id');
        $listagem = EmpresasContatos::where('empresa_id', $empresa_id);
        return datatables()->of($listagem)->toJson();
    }

    public function getListagemEnderecos(Request $request)
    {
        $empresa_id = $request->input('empresa_id');
        $listagem = EmpresasEnderecos::where('empresa_id', $empresa_id);
        return datatables()->of($listagem)->toJson();
    }

    public function create(Request $request): View
    {
        $tittle = 'Nova empresa';
        // if (! User::hasPermission('empresas_create')) return view('forbbiden', ['tittle' => $tittle]);

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

        return redirect('/empresas/edit/'.$action->id);
    }

    public function submitContatos(Request $request)
    {
        $request->validate([
            'contato_empresa_id' => ['required'],
            'contato_contato' => ['required', 'string', 'max:255'],
        ]);

        if ($request->contato_id == null) {
            $action = EmpresasContatos::create([
                "empresa_id" => $request->contato_empresa_id,
                "contato" => $request->contato_contato,
                "fone" => $request->contato_fone,
                "email" => $request->contato_email,
                "observacao" => $request->contato_observacao,
            ]);

        } else {
            $action = EmpresasContatos::findOrFail($request->contato_id);
            $action->empresa_id = $request->contato_empresa_id;
            $action->contato = $request->contato_contato;
            $action->fone = $request->contato_fone;
            $action->email = $request->contato_email;
            $action->observacao = $request->contato_observacao;
            $action->save();
        }

        event(new Registered($action));

        return;
    }

    public function submitEnderecos(Request $request)
    {
        $request->validate([
            'endereco_empresa_id' => ['required'],
            'endereco_estado' => ['required', 'max:255'],
        ]);

        if ($request->endereco_id == null) {
            $action = EmpresasEnderecos::create([
                "empresa_id" => $request->endereco_empresa_id,
                "cep" => $request->endereco_cep,
                "estado" => $request->endereco_estado,
                "cidade" => $request->endereco_cidade,
                "rua" => $request->endereco_rua,
                "numero" => $request->endereco_numero,
                "complemento" => $request->endereco_complemento,
                "observacao" => $request->endereco_observacao,
            ]);

        } else {
            $action = EmpresasEnderecos::findOrFail($request->endereco_id);
            $action->empresa_id = $request->endereco_empresa_id;
            $action->cep = $request->endereco_cep;
            $action->estado = $request->endereco_estado;
            $action->cidade = $request->endereco_cidade;
            $action->rua = $request->endereco_rua;
            $action->numero = $request->endereco_numero;
            $action->complemento = $request->endereco_complemento;
            $action->observacao = $request->endereco_observacao;
            $action->save();
        }

        event(new Registered($action));

        return;
    }

    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Editar empresa';
        $this->hasPermission('empresas_update',$tittle,true);

        $contatos = $this->hasPermission('empresas_contatos');
        $enderecos = $this->hasPermission('empresas_enderecos');

        $data = Empresas::findOrFail($id);
        $divisoes = Divisoes::all();

        return view('empresas.edit', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'data' => $data,
            'divisoes' => $divisoes,
            'contatos' => $contatos,
            'enderecos' => $enderecos,
        ]);
    }

    public function getContato(Request $request, string $id)
    {     
        return EmpresasContatos::findOrFail($id)->toJson();
    }

    public function getEndereco(Request $request, string $id)
    {     
        return EmpresasEnderecos::findOrFail($id)->toJson();
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

    public function delete(Request $request, string $id): RedirectResponse
    {
        EmpresasContatos::where('empresa_id', $id)->delete();
        EmpresasEnderecos::where('empresa_id', $id)->delete();
        $data = Empresas::findOrFail($id)->delete();
        return redirect(route('empresas.index', absolute: false));
    }

    public function deleteContatos(Request $request, string $id): RedirectResponse
    {
        return EmpresasContatos::findOrFail($id)->delete();
    }

    public function deleteEnderecos(Request $request, string $id): RedirectResponse
    {
        return EmpresasEnderecos::findOrFail($id)->delete();
    }
}