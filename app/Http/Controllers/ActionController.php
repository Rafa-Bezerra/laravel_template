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
        // $data = DB::table('actions')->where('id',$id)->get();
        $data = Actions::findOrFail($id);
        // dd($data);
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
        // $data = DB::table('actions')->where('id',$id)->get();
        $data = Actions::findOrFail($id)->delete();
        // dd($data);

        return redirect(route('actions.index', absolute: false));
    }
}