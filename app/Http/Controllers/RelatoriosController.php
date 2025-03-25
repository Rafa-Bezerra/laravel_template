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
use App\Models\Empresas;

class RelatoriosController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        $tittle = 'Relatórios';
        
        $this->hasPermission('relatorios',$tittle,true);

        return view('relatorios.index', [
            'user' => $request->user(),
            'tittle' => $tittle,
        ]);
    }

    public function orcamentos_por_cliente(Request $request): View
    {
        $tittle = 'Obras por cliente';
        
        $this->hasPermission('relatorios_orcamentos_por_cliente',$tittle,true);

        $data = Empresas::with(['orcamentos.endereco'])->get();
        
        return view('relatorios.orcamentos.orcamentos_por_cliente', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'data' => $data,
        ]);
    }
}