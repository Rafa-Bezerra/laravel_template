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
use App\Models\Pagamentos;
use DataTables;

class PagamentosController extends Controller
{

    public function getListagem()
    {
        $listagem = Pagamentos::with('banco')->get();
        return datatables()->of($listagem)
            ->addColumn('banco_name', function ($pagamento) {
                if ($pagamento->banco) {
                    return "{$pagamento->banco->name} - {$pagamento->banco->agencia}-{$pagamento->banco->conta}";
                }
                return 'Sem banco';
            })
        ->toJson();
    }
}