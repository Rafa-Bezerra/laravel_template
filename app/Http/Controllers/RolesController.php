<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolesUpdateRequest;
use App\Models\Roles;
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
        return view('roles.index', [
            'user' => $request->user(),
        ]);
    }

    public function getRoles()
    {
        $roles = Roles::select(['id', 'name']);
        return datatables()->of($roles)->toJson();
    }

    public function create(Request $request): View
    {
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
    

    public function actions(Request $request, string $id): View
    {
        $actions = DB::table('actions')->get();
        $roles_actions = DB::table('roles_actions')->where('role_id',$id)->get();
        dd($actions);
        return view('roles.actions', [
            'user' => $request->user(),
        ]);
    }
}