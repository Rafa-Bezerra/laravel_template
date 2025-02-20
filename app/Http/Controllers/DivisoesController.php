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
use DataTables;
use App\Models\User;
use App\Models\Divisoes;

class DivisoesController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        $tittle = 'Divisões';
        
        if (! User::hasPermission('divisoes')) return view('forbbiden', ['tittle' => $tittle]);

        return view('divisoes.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
        ]);
    }

    public function getListagem()
    {
        $actions = Divisoes::all();
        return datatables()->of($actions)->toJson();
    }

    public function create(Request $request): View
    {
        $tittle = 'Nova divisão';
        
        if (! User::hasPermission('divisoes_create')) return view('forbbiden', ['tittle' => $tittle]);

        return view('divisoes.create', [
            'user' => $request->user(),
            'tittle' => $tittle,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
       
        $action = Divisoes::create([
            "name" => $request->name,
        ]);

        event(new Registered($action));

        return redirect(route('divisoes.index', absolute: false));
    }

    public function edit(Request $request, string $id): View
    {        
        $tittle = 'Editar divisão';
        
        if (! User::hasPermission('divisoes_edit')) return view('forbbiden', ['tittle' => $tittle]);

        $data = Divisoes::findOrFail($id);
        return view('divisoes.edit', [
            'user' => $request->user(),
            'data' => $data,
            'tittle' => $tittle,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        
        $action = Divisoes::findOrFail($request->id);
        $action->name = $request->name;
        $action->save();

        event(new Registered($action));

        return redirect(route('divisoes.index', absolute: false));
    }

    public function delete(Request $request, string $id): RedirectResponse
    {
        // $data = DB::table('actions')->where('id',$id)->get();
        $data = Divisoes::findOrFail($id)->delete();
        // dd($data);

        return redirect(route('divisoes.index', absolute: false));
    }
}