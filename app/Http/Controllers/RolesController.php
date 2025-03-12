<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolesUpdateRequest;
use App\Models\Roles;
use App\Models\RolesActions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use DataTables;

class RolesController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        $tittle = 'Acessos';
        
        $this->hasPermission('roles',$tittle,true);
        $insert = $this->hasPermission('roles_insert');
        $update = $this->hasPermission('roles_update');
        $delete = $this->hasPermission('roles_delete');
        $actions = $this->hasPermission('roles_actions');
        
        return view('roles.index', [
            'user' => $request->user(),
            'insert' => $insert,
            'update' => $update,
            'actions' => $actions,
            'delete' => $delete,
        ]);
    }

    public function getRoles()
    {
        $roles = Roles::select(['id', 'name']);
        return datatables()->of($roles)->toJson();
    }

    public function create(Request $request): View
    {
        $tittle = 'Novo acesso';
        $this->hasPermission('roles_insert',$tittle,true);
        
        return view('roles.create', [
            'user' => $request->user(),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:'.Roles::class],
        ]);
       
        $role = Roles::create([
            "name" => $request->name,
            "active" => true
        ]);

        event(new Registered($role));

        return redirect(route('roles.index', absolute: false));
    }
    
    public function edit(Request $request, string $id): View
    {
        $tittle = 'Editar acesso';
        $this->hasPermission('roles_update',$tittle,true);

        $data = Roles::findOrFail($id);
        return view('roles.edit', [
            'user' => $request->user(),
            'data' => $data,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        
        $action = Roles::findOrFail($request->id);
        $action->name = $request->name;
        $action->save();

        event(new Registered($action));

        return redirect(route('roles.index', absolute: false));
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        RolesActions::where('role_id', $id)->delete();
        $data = Roles::findOrFail($id)->delete();
        return redirect(route('roles.index', absolute: false));
    }    

    public function actions(Request $request, string $id): View
    {
        $actions = DB::table('actions')->get();
        $roles_actions = DB::table('roles_actions')->where('role_id',$id)->get();
        // dd($roles_actions);
        foreach ($actions as $key => $value) {
            $checked = false;
            foreach ($roles_actions as $ra_key => $ra_value) {
                if($value->id == $ra_value->action_id) $checked = true;
            }
            $actions[$key]->checked = $checked;
        }
        // dd($actions);
        return view('roles.actions', [
            'user' => $request->user(),
            'role_id' => $id,
            'actions' => $actions,
        ]);
    }

    public function update_actions(Request $request): RedirectResponse
    {
        // dd($request->all());
        RolesActions::where('role_id', $request->role_id)->delete();

        foreach ($request->all() as $key => $value) {
            if($key != '_token' && $key != 'role_id') {
                $role = RolesActions::create([
                    "role_id" => $request->role_id,
                    "action_id" => $key,
                ]);
                event(new Registered($role));
            }
        }
        // $action = Roles::findOrFail($request->id);
        // $action->name = $request->name;
        // $action->save();

        return redirect(route('roles.index', absolute: false));
    }
}