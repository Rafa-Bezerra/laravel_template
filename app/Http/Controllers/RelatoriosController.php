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
use DateTime;
use App\Models\User;
use App\Models\Empresas;
use App\Models\Bancos;
use App\Models\Pagamentos;
use App\Models\OrcamentosGastos;

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

    public function recebimentos(Request $request): View
    {
        $tittle = 'Recebimentos';
        
        $this->hasPermission('relatorios_recebimentos',$tittle,true);

        // $data = Pagamentos::with(['banco','orcamento','orcamento.empresa'])->where('especie','venda')->get();

        $empresas = Empresas::all();
        $bancos = Bancos::all();

        return view('relatorios.despesas.recebimentos', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'empresas' => $empresas,
            'bancos' => $bancos,
        ]);
    }

    public function recebimentosAjax(Request $request)
    {
        $query = Pagamentos::with(['banco', 'orcamento.empresa'])->where('especie', 'venda');

        if ($request->filled('filtro_empresa_id')) {
            $query->whereHas('orcamento.empresa', function ($q) use ($request) {
                $q->where('name', $request->filtro_empresa_id);
            });
        }

        if ($request->filled('filtro_data_de')) {
            $query->whereDate('data', '>=', DateTime::createFromFormat('d/m/Y', $request->filtro_data_de)->format('Y-m-d'));
        }

        if ($request->filled('filtro_data_ate')) {
            $query->whereDate('data', '<=', DateTime::createFromFormat('d/m/Y', $request->filtro_data_ate)->format('Y-m-d'));
        }

        if ($request->filled('filtro_banco')) {
            $query->whereHas('banco', function ($q) use ($request) {
                $q->where('name', $request->filtro_banco);
            });
        }

        if ($request->filled('filtro_controle')) {
            $query->where('controle', $request->filtro_controle);
        }

        return DataTables::of($query)
            ->addColumn('empresa', fn($row) => $row->orcamento->empresa->name ?? '')
            ->addColumn('banco', fn($row) => $row->banco->name ?? '')
            ->editColumn('valor', fn($row) => 'R$ ' . number_format($row->valor, 2, ',', '.'))
            ->editColumn('data', fn($row) => \Carbon\Carbon::parse($row->data)->format('d/m/Y'))
            ->toJson();
    }

    public function despesas_vs_recebimentos(Request $request): View
    {
        $tittle = 'Obras por cliente';
        
        $this->hasPermission('relatorios_despesas_vs_recebimentos',$tittle,true);

        $empresas = Empresas::all();
        $bancos = Bancos::all();
        
        return view('relatorios.despesas.despesas_vs_recebimentos', [
            'user' => $request->user(),
            'tittle' => $tittle,
            'empresas' => $empresas,
            'bancos' => $bancos,
        ]);
    }

    public function despesas_recebimentosAjax(Request $request)
    {
        // Pagamentos
        $pagamentos = Pagamentos::with(['banco', 'orcamento.empresa']);

        if ($request->filled('filtro_empresa_id')) {
            $pagamentos->whereHas('orcamento.empresa', function ($q) use ($request) {
                $q->where('name', $request->filtro_empresa_id);
            });
        }

        if ($request->filled('filtro_data_de')) {
            $pagamentos->whereDate('data', '>=', DateTime::createFromFormat('d/m/Y', $request->filtro_data_de)->format('Y-m-d'));
        }

        if ($request->filled('filtro_data_ate')) {
            $pagamentos->whereDate('data', '<=', DateTime::createFromFormat('d/m/Y', $request->filtro_data_ate)->format('Y-m-d'));
        }

        if ($request->filled('filtro_banco')) {
            $pagamentos->whereHas('banco', function ($q) use ($request) {
                $q->where('name', $request->filtro_banco);
            });
        }

        if ($request->filled('filtro_controle')) {
            $pagamentos->where('controle', $request->filtro_controle);
        }

        if ($request->filled('filtro_especie')) {
            $pagamentos->where('especie', $request->filtro_especie);
        }

        $pagamentos = $pagamentos->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'especie' => $item->especie,
                'empresa' => $item->orcamento->empresa->name ?? '',
                'valor' => $item->valor,
                'parcela' => $item->parcela,
                'data' => $item->data,
                'banco' => $item->banco->name ?? '',
                'controle' => $item->controle,
            ];
        });

        // Orcamento Gastos
        $gastos = OrcamentosGastos::with(['banco', 'orcamento.empresa']);

        if ($request->filled('filtro_empresa_id')) {
            $gastos->whereHas('orcamento.empresa', function ($q) use ($request) {
                $q->where('name', $request->filtro_empresa_id);
            });
        }

        if ($request->filled('filtro_data_de')) {
            $gastos->whereDate('data', '>=', DateTime::createFromFormat('d/m/Y', $request->filtro_data_de)->format('Y-m-d'));
        }

        if ($request->filled('filtro_data_ate')) {
            $gastos->whereDate('data', '<=', DateTime::createFromFormat('d/m/Y', $request->filtro_data_ate)->format('Y-m-d'));
        }

        if ($request->filled('filtro_banco')) {
            $gastos->whereHas('banco', function ($q) use ($request) {
                $q->where('name', $request->filtro_banco);
            });
        }

        if ($request->filled('filtro_controle')) {
            $gastos->where('controle', $request->filtro_controle);
        }

        if ($request->filled('filtro_especie')) {
            $gastos->where('especie', $request->filtro_especie);
        }

        $gastos = $gastos->get()->map(function ($item) {
            return [
                'id' => 'G-' . $item->id, // Prefixo para evitar conflito de ID
                'especie' => $item->especie,
                'empresa' => $item->orcamento->empresa->name ?? '',
                'valor' => $item->valor,
                'parcela' => '-', // Não se aplica
                'data' => $item->data,
                'banco' => $item->banco->name ?? '',
                'controle' => $item->controle,
            ];
        });

        // Merge
        $dados = $pagamentos->merge($gastos);

        return DataTables::of($dados)
            ->editColumn('valor', fn($row) => 'R$ ' . number_format($row['valor'], 2, ',', '.'))
            ->editColumn('data', fn($row) => \Carbon\Carbon::parse($row['data'])->format('d/m/Y'))
            ->make(true);
    }
}