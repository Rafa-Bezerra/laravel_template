<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Models\Roles;
use App\Models\UsersRoles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use DataTables;

class UserController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {        
        $tittle = 'Usuários';
        
        $this->hasPermission('users',$tittle,true);
        $insert = $this->hasPermission('users_insert');
        $update = $this->hasPermission('users_update');
        $delete = $this->hasPermission('users_delete');
        $roles = $this->hasPermission('users_roles');

        return view('usuarios.index', [
            'user' => $request->user(),
            'insert' => $insert,
            'update' => $update,
            'delete' => $delete,
            'roles' => $roles,
        ]);
    }

    public function getUsuarios()
    {
        $usuarios = User::select(['id', 'name', 'email']);
        return datatables()->of($usuarios)->toJson();
    }

    public function create(Request $request): View
    {
        $tittle = 'Novo usuário';
        $this->hasPermission('users_insert',$tittle,true);

        $roles = DB::table('roles')->where('active',1)->get();
        return view('usuarios.create', [
            'user' => $request->user(),
            'roles' => $roles,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        ]);
       
        $user = User::create([
            "name" => $request->name,
            "password" => '12345',
            "email" => $request->email,
            "password_expiration" => date("Y-m-d H:i:s", strtotime("+30 days")),
            "active" => true
        ]);

        event(new Registered($user));

        return redirect(route('usuarios.index', absolute: false));
    }
    
    public function edit(Request $request, string $id): View
    {
        $tittle = 'Editar usuário';
        $this->hasPermission('users_update',$tittle,true);
        
        $data = User::findOrFail($id);
        return view('usuarios.edit', [
            'user' => $request->user(),
            'data' => $data,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        ]);
        
        $action = User::findOrFail($request->id);
        $action->name = $request->name;
        $action->email = $request->email;
        $action->save();

        event(new Registered($action));

        return redirect(route('usuarios.index', absolute: false));
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        UsersRoles::where('user_id', $id)->delete();
        $data = User::findOrFail($id)->delete();
        return redirect(route('usuarios.index', absolute: false));
    }

    public function roles(Request $request, string $id): View
    {
        $roles = DB::table('roles')->get();
        $users_roles = DB::table('users_roles')->where('user_id',$id)->get();
        // dd($roles_actions);
        foreach ($roles as $key => $value) {
            $checked = false;
            foreach ($users_roles as $ra_key => $ra_value) {
                if($value->id == $ra_value->role_id) $checked = true;
            }
            $roles[$key]->checked = $checked;
        }
        // dd($actions);
        return view('usuarios.roles', [
            'user' => $request->user(),
            'user_id' => $id,
            'roles' => $roles,
        ]);
    }

    public function update_roles(Request $request): RedirectResponse
    {
        // dd($request->all());
        UsersRoles::where('user_id', $request->user_id)->delete();

        foreach ($request->all() as $key => $value) {
            if($key != '_token' && $key != 'user_id') {
                $role = UsersRoles::create([
                    "user_id" => $request->user_id,
                    "role_id" => $key,
                ]);
                event(new Registered($role));
            }
        }
        // $action = Roles::findOrFail($request->id);
        // $action->name = $request->name;
        // $action->save();

        return redirect(route('usuarios.index', absolute: false));
    }
}