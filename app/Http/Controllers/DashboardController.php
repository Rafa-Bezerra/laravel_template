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
use App\Models\Pagamentos;

class DashboardController extends Controller
{
    function index(Request $request) {
        $tittle = 'Home';
        $a_pagar = Pagamentos::where('controle', 'pendente')->where('especie', 'compra')->sum('valor');
        $a_receber = Pagamentos::where('controle', 'pendente')->where('especie', 'venda')->sum('valor');

        $pagamentos = $this->hasPermission('dashboard_pagamentos');
        $movimentacoes = $this->hasPermission('dashboard_movimentacoes');
        
        return view('home.dashboard', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'a_pagar' => 'R$ ' . number_format($a_pagar, 2, ',', '.'),
            'a_receber' => 'R$ ' . number_format($a_receber, 2, ',', '.'),
            'pagamentos' => $pagamentos,
            'movimentacoes' => $movimentacoes,
        ]);
    }
}