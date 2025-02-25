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
use DataTables;

class EstoqueController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        $tittle = 'Estoque';
        
        if (! User::hasPermission('estoque')) return view('forbbiden', ['tittle' => $tittle]);

        return view('estoque.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
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
    
    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Estoque editar';

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