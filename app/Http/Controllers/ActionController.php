<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\Actions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use DataTables;

class ActionController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        $tittle = 'Ações';
        
        $this->hasPermission('actions',$tittle,true);
        $insert = $this->hasPermission('actions_insert');
        $update = $this->hasPermission('actions_update');
        $delete = $this->hasPermission('actions_delete');

        return view('actions.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'insert' => $insert,
            'update' => $update,
            'delete' => $delete,
        ]);
    }

    public function role(): HasOne
    {
        return $this->hasOne(Roles::class);
    }

    public function getActions()
    {
        $actions = Actions::select(['id', 'name', 'route']);
        return datatables()->of($actions)->toJson();
    }

    public function create(Request $request): View
    {
        return view('actions.create', [
            'user' => $request->user(),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'route' => ['required', 'string', 'lowercase', 'max:255'],
        ]);
       
        $action = Actions::create([
            "name" => $request->name,
            "route" => $request->route
        ]);

        event(new Registered($action));

        return redirect(route('actions.index', absolute: false));
    }

    public function edit(Request $request, string $id): View
    {        
               
        $tittle = 'Editar ação';
        $this->hasPermission('actions_update',$tittle,true);
        $data = Actions::findOrFail($id);
        return view('actions.edit', [
            'user' => $request->user(),
            'data' => $data,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'route' => ['required', 'string', 'lowercase', 'max:255'],
        ]);
        
        $action = Actions::findOrFail($request->id);
        $action->name = $request->name;
        $action->route = $request->route;
        $action->save();

        event(new Registered($action));

        return redirect(route('actions.index', absolute: false));
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        $data = Actions::findOrFail($id)->delete();
        return redirect(route('actions.index', absolute: false));
    }
}