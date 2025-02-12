<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Models\Roles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use DataTables;

class UserController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        return view('usuarios.index', [
            'user' => $request->user(),
        ]);
    }

    public function role(): HasOne
    {
        return $this->hasOne(Roles::class);
    }

    public function getUsuarios()
    {
        $usuarios = User::select(['id', 'name', 'email']);
        return datatables()->of($usuarios)->toJson();
    }

    public function create(Request $request): View
    {
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        ]);
       
        $user = User::create([
            "name" => $request->name,
            "password" => null,
            "email" => $request->email,
            "password_expiration" => date("Y-m-d H:i:s", strtotime("+30 days")),
            "active" => true
        ]);

        event(new Registered($user));

        return redirect(route('usuarios.index', absolute: false));
    }
}