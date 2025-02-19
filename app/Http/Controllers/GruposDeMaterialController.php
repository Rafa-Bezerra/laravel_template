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
use App\Models\GruposDeMaterial;

class GruposDeMaterialController extends Controller
{
    public function index(Request $request): View
    {
        $tittle = 'Grupos de Material';
        
        if (! User::hasPermission('grupos_de_material')) return view('forbbiden', ['tittle' => $tittle]);

        return view('grupos_de_material.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
        ]);
    }

    public function getListagem()
    {
        $listagem = GruposDeMaterial::all();
        return datatables()->of($listagem)->toJson();
    }

    public function create(Request $request): View
    {
        $tittle = 'Novo grupo de material';
        if (! User::hasPermission('grupos_de_material_create')) return view('forbbiden', ['tittle' => $tittle]);

        return view('grupos_de_material.create', [
            'user' => $request->user(),
            'tittle' => $tittle,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
       
        $action = GruposDeMaterial::create([
            "name" => $request->name,
        ]);

        event(new Registered($action));

        return redirect(route('grupos_de_material.index', absolute: false));
    }

    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Editar grupos de material';
        if (! User::hasPermission('grupos_de_material_edit')) return view('forbbiden', ['tittle' => $tittle]);
        
        $data = GruposDeMaterial::findOrFail($id);
        return view('grupos_de_material.edit', [
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
        
        $action = GruposDeMaterial::findOrFail($request->id);
        $action->name = $request->name;
        $action->save();

        event(new Registered($action));

        return redirect(route('grupos_de_material.index', absolute: false));
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        $data = GruposDeMaterial::findOrFail($id)->delete();
        return redirect(route('grupos_de_material.index', absolute: false));
    }
}