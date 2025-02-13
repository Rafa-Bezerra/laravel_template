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
        return view('actions.index', [
            'user' => $request->user(),
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
        $roles = DB::table('roles')->where('active',1)->get();
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
}