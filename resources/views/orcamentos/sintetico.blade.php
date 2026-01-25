<style>
    @media print {
    
        /* ===============================
           CONFIGURAÇÕES GERAIS
           =============================== */
    
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-shadow: none !important;
            font-size: 12px !important;
        }
    
        body {
            background: #ffffff !important;
            color: #111827 !important;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px !important;
            line-height: 1.4;
        }
    
        /* Remove dark mode */
        .dark,
        [class*="dark:"] {
            background: #ffffff !important;
            color: #111827 !important;
        }
    
        /* Elementos não imprimíveis */
        .no-print {
            display: none !important;
        }
    
        /* ===============================
           LAYOUT
           =============================== */
    
        .max-w-7xl {
            max-width: 100% !important;
        }
    
        .py-12 {
            padding: 0 !important;
        }
    
        .shadow,
        .shadow-sm,
        .shadow-md {
            box-shadow: none !important;
        }
    
        .rounded,
        .rounded-lg,
        .sm\:rounded-lg {
            border-radius: 0 !important;
        }
    
        .bg-white,
        .dark\:bg-gray-800 {
            background: #ffffff !important;
            border: 1px solid #d1d5db;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
    
        /* ===============================
           TÍTULOS
           =============================== */
    
        h2 {
            font-size: 16px !important;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 4px;
        }
    
        h3 {
            font-size: 13px !important;
            font-weight: bold;
            margin-bottom: 6px;
            text-transform: uppercase;
            color: #1f2937;
        }
    
        /* ===============================
           TABELAS
           =============================== */
    
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 6px;
            font-size: 12px !important;
            page-break-inside: auto !important;
        }
    
        thead {
            background: #f3f4f6 !important;
        }
    
        th {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px !important;
            color: #374151;
        }
    
        th,
        td {
            border: 1px solid #d1d5db !important;
            padding: 6px 8px !important;
            vertical-align: top;
        }
    
        tfoot td {
            font-weight: bold;
            background: #f9fafb !important;
        }
    
        tr {
            page-break-inside: avoid !important;
        }
    
        /* ===============================
           OVERFLOW
           =============================== */
    
        .overflow-x-auto {
            overflow: visible !important;
        }
    
        /* ===============================
           QUEBRAS
           =============================== */
    
        .page-break {
            page-break-after: always;
        }

        /* Permite quebrar o bloco se necessário */
        .print-section {
            page-break-inside: auto !important;
        }

        /* Nunca separar título da tabela */
        .print-section-title {
            page-break-after: avoid !important;
        }

        /* Evita quebra antes da tabela */
        .print-table {
            page-break-before: avoid !important;
        }
    
    }
</style>
    
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }} — Relatório Sintético
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Orçamento Info --}}
            <div class="bg-white dark:bg-gray-800 dark:text-gray-100 p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informações da obra</h3>
                <p><strong>ID:</strong> {{ $orcamento->id }}</p>
                <p><strong>Cliente:</strong> {{ $orcamento->empresa->name ?? '-' }}</p>
                <p><strong>Endereço:</strong> {{ $orcamento->endereco ? $orcamento->endereco->rua . " - " . $orcamento->endereco->numero : '-' }}</p>
                <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($orcamento->data_venda)->format('d/m/Y') }}</p>
                <p><strong>Prazo:</strong> {{ \Carbon\Carbon::parse($orcamento->data_prazo)->format('d/m/Y') }}</p>
                <p><strong>Orçamento:</strong> R$ {{ number_format($orcamento->valor_orcamento, 2, ',', '.') }}</p>
                <p><strong>Impostos:</strong> {{ number_format($orcamento->valor_impostos, 2, ',', '.') }} %</p>
                <p><strong>Saldo:</strong> R$ {{ number_format($orcamento->valor_saldo, 2, ',', '.') }}</p>
            </div>

            {{-- RESUMO FINANCEIRO --}}
            @php
                $totalGastos = $orcamento_gastos->sum('valor');
                $totalPagamentos = $orcamento_pagamentos->sum('valor');
                $totalComissoes = $orcamento_comissoes->sum('valor_total');
                $totalItens = $orcamento_itens->sum('valor_total');
                $totalServicos = $orcamento_servicos->sum('preco');

                $resultado = $totalPagamentos - $totalGastos - $totalComissoes - $totalItens - $totalServicos;
            @endphp
            <div class="bg-white dark:bg-gray-800 dark:text-gray-100 p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Resumo Financeiro</h3>
                <p><strong>Valor do Orçamento:</strong> R$ {{ number_format($orcamento->valor_orcamento, 2, ',', '.') }}</p>
                <p><strong>Total de Gastos:</strong> R$ {{ number_format($totalGastos, 2, ',', '.') }}</p>
                <p><strong>Total de Materiais:</strong> R$ {{ number_format($totalItens, 2, ',', '.') }}</p>
                <p><strong>Total de Serviços:</strong> R$ {{ number_format($totalServicos, 2, ',', '.') }}</p>
                <p><strong>Total Recebido:</strong> R$ {{ number_format($totalPagamentos, 2, ',', '.') }}</p>
                <p><strong>Total de Comissões:</strong> R$ {{ number_format($totalComissoes, 2, ',', '.') }}</p>
                <p><strong>Resultado Líquido:</strong> R$ {{ number_format($resultado, 2, ',', '.') }}</p>
            </div>

            <div class="no-print mb-6">
                <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Imprimir
                </button>
            </div>
        </div>
    </div>
</x-app-layout>    