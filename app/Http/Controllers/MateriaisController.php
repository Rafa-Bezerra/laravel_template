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
use App\Models\Materiais;

class MateriaisController extends Controller
{
    public function index(Request $request): View
    {
        $tittle = 'Materiais';
        
        if (! User::hasPermission('materiais')) return view('forbbiden', ['tittle' => $tittle]);

        return view('materiais.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
        ]);
    }

    public function getListagem()
    {
        $listagem = Materiais::all();
        // dd($listagem);
        return datatables()->of($listagem)->toJson();
    }

    public function create(Request $request): View
    {
        $tittle = 'Novo material';
        if (! User::hasPermission('materiais_create')) return view('forbbiden', ['tittle' => $tittle]);

        $grupos_de_material = GruposDeMaterial::all();
        return view('materiais.create', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'grupos_de_material' => $grupos_de_material,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'unidade_de_medida' => ['required', 'string', 'max:255'],
        ]);

        $action = Materiais::create([
            "name" => $request->name,
            "unidade_de_medida" => $request->unidade_de_medida,
            "grupo_de_material_id" => $request->grupo_de_material_id,
        ]);

        event(new Registered($action));

        return redirect(route('materiais.index', absolute: false));
    }

    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Editar material';
        if (! User::hasPermission('materiais_edit')) return view('forbbiden', ['tittle' => $tittle]);
        
        $grupos_de_material = GruposDeMaterial::all();
        $data = Materiais::findOrFail($id);
        return view('materiais.edit', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'grupos_de_material' => $grupos_de_material,
            'data' => $data,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'unidade_de_medida' => ['required', 'string', 'max:255'],
        ]);
        
        $action = Materiais::findOrFail($request->id);
        $action->name = $request->name;
        $action->unidade_de_medida = $request->unidade_de_medida;
        $action->grupo_de_material_id = $request->grupo_de_material_id;
        $action->save();

        event(new Registered($action));

        return redirect(route('materiais.index', absolute: false));
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        $data = Materiais::findOrFail($id)->delete();
        return redirect(route('materiais.index', absolute: false));
    }
}