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
use App\Models\Servicos;

class ServicosController extends Controller
{
    public function index(Request $request): View
    {
        $tittle = 'Serviços';
        
        if (! User::hasPermission('servicos')) return view('forbbiden', ['tittle' => $tittle]);

        return view('servicos.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
        ]);
    }

    public function getListagem()
    {
        $listagem = Servicos::all();
        // dd($listagem);
        return datatables()->of($listagem)->toJson();
    }

    public function create(Request $request): View
    {
        $tittle = 'Novo serviço';
        if (! User::hasPermission('servicos_create')) return view('forbbiden', ['tittle' => $tittle]);
        
        return view('servicos.create', [
            'user' => $request->user(),
            'tittle' => $tittle,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $action = Servicos::create([
            "name" => $request->name,
        ]);

        event(new Registered($action));

        return redirect(route('servicos', absolute: false));
    }

    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Editar serviço';
        if (! User::hasPermission('servicos_edit')) return view('forbbiden', ['tittle' => $tittle]);

        $data = Servicos::findOrFail($id);
        return view('servicos.edit', [
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
        
        $action = Servicos::findOrFail($request->id);
        $action->name = $request->name;
        $action->save();

        event(new Registered($action));

        return redirect(route('servicos', absolute: false));
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        $data = Servicos::findOrFail($id)->delete();
        return redirect(route('servicos', absolute: false));
    }
}