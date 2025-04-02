<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Estoque;
use App\Models\Materiais;
use DataTables;

class EstoqueController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        $tittle = 'Estoque';
        
        $this->hasPermission('estoque',$tittle,true);
        $update = $this->hasPermission('estoque_update');
        $insert = $this->hasPermission('estoque_create');

        return view('estoque.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'insert' => $insert,
            'update' => $update,
        ]);
    }

    public function getListagem()
    {
        $listagem = Estoque::with('material')->get();
        return datatables()->of($listagem)
            ->addColumn('material_name', function ($estoque) {
                return $estoque->material->name ?? 'Sem material';
            })
        ->toJson();
    }
    
    public function create(Request $request): View
    {        
        $tittle = 'Estoque criar';

        $this->hasPermission('estoque_create',$tittle,true);
        
        $materiais = Materiais::leftJoin('estoque', 'materiais.id', '=', 'estoque.material_id')
            ->whereNull('estoque.material_id')
            ->select('materiais.*')
            ->get();

        return view('estoque.create', [
            'user' => $request->user(),
            'materiais' => $materiais,
            'tittle' => $tittle,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'quantidade' => ['required'],
            'material_id' => ['required'],
        ]);
        
        $action = Estoque::create([
            "material_id" => $request->material_id,
            "quantidade" => $request->quantidade,
            "orcamento_id" => null,
        ]);

        event(new Registered($action));

        return redirect(route('estoque', absolute: false));
    }
    
    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Estoque editar';

        $this->hasPermission('estoque_update',$tittle,true);
        
        $data = Estoque::findOrFail($id);

        return view('estoque.edit', [
            'user' => $request->user(),
            'data' => $data,
            'tittle' => $tittle,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'quantidade' => ['required'],
        ]);
        
        $action = Estoque::findOrFail($request->id);
        $action->quantidade = $request->quantidade;
        $action->save();

        event(new Registered($action));

        return redirect(route('estoque', absolute: false));
    }
}