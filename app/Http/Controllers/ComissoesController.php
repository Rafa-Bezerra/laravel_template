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
use App\Models\Comissoes;

class ComissoesController extends Controller
{
    public function index(Request $request): View
    {
        $tittle = 'Serviços';
        
        $this->hasPermission('comissoes',$tittle,true);
        $insert = $this->hasPermission('comissoes_insert');
        $update = $this->hasPermission('comissoes_update');
        $delete = $this->hasPermission('comissoes_delete');

        return view('comissoes.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'insert' => $insert,
            'update' => $update,
            'delete' => $delete,
        ]);
    }

    public function getListagem()
    {
        $listagem = Comissoes::all();
        // dd($listagem);
        return datatables()->of($listagem)->toJson();
    }

    public function create(Request $request): View
    {
        $tittle = 'Nova comissão';
        
        
        return view('comissoes.create', [
            'user' => $request->user(),
            'tittle' => $tittle,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $action = Comissoes::create([
            "name" => $request->name,
        ]);

        event(new Registered($action));

        return redirect(route('comissoes', absolute: false));
    }

    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Editar serviço';

        $this->hasPermission('comissoes_update',$tittle,true);

        $data = Comissoes::findOrFail($id);
        return view('comissoes.edit', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'data' => $data,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        
        $action = Comissoes::findOrFail($request->id);
        $action->name = $request->name;
        $action->save();

        event(new Registered($action));

        return redirect(route('comissoes', absolute: false));
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        $data = Comissoes::findOrFail($id)->delete();
        return redirect(route('comissoes', absolute: false));
    }
}