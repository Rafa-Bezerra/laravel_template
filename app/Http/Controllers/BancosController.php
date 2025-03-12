<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\Bancos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use DataTables;

class BancosController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        $tittle = 'Bancos';
        
        $this->hasPermission('bancos',$tittle,true);
        $insert = $this->hasPermission('bancos_insert');
        $update = $this->hasPermission('bancos_update');
        $delete = $this->hasPermission('bancos_delete');

        return view('bancos.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'insert' => $insert,
            'update' => $update,
            'delete' => $delete,
        ]);
    }

    public function getListagem()
    {
        $bancos = Bancos::all();
        return datatables()->of($bancos)->toJson();
    }

    public function create(Request $request): View
    {
        return view('bancos.create', [
            'user' => $request->user(),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'agencia' => ['required', 'string', 'max:255'],
            'conta' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'string', 'max:255'],
        ]);
       
        $action = Bancos::create([
            "name" => $request->name,
            "agencia" => $request->agencia,
            "conta" => $request->conta,
            "tipo" => $request->tipo,
        ]);

        event(new Registered($action));

        return redirect(route('bancos', absolute: false));
    }

    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Editar banco';

        $this->hasPermission('bancos_update',$tittle,true);
        
        $data = Bancos::findOrFail($id);
        return view('bancos.edit', [
            'user' => $request->user(),
            'data' => $data,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'agencia' => ['required', 'string', 'max:255'],
            'conta' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'string', 'max:255'],
        ]);
        
        $action = Bancos::findOrFail($request->id);
        $action->name = $request->name;
        $action->agencia = $request->agencia;
        $action->conta = $request->conta;
        $action->tipo = $request->tipo;
        $action->save();

        event(new Registered($action));

        return redirect(route('bancos', absolute: false));
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        $data = Bancos::findOrFail($id)->delete();
        return redirect(route('bancos', absolute: false));
    }
}