<style>
    .print-only {
        display: none;
    }

    @media print {
    
        /* ===============================
           CONFIGURAÇÕES GERAIS
           =============================== */
        .print-only {
            display: block !important;
            margin-top: 40px;
        }

        .assinaturas {
            display: flex;
            justify-content: space-between;
            gap: 40px;
            margin-top: 60px;
        }

        .assinatura {
            width: 45%;
            text-align: center;
            font-size: 12px;
        }

        .assinatura .linha {
            border-top: 1px solid #111827;
            margin-bottom: 6px;
        }

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
        <div class="flex items-center gap-3">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __($tittle) }} — Relatório Analítico
            </h2>
        </div>
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

            {{-- Itens --}}
            <div class="bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Materiais da obra</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-2">Material</th>
                                <th class="px-4 py-2">Quantidade</th>
                                <th class="px-4 py-2">Valor Unitário</th>
                                <th class="px-4 py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            @foreach($orcamento_itens as $item)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="px-4 py-2">{{ $item->material->name ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ number_format($item->quantidade, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2">R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2">R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            @php
                                $totalItens = $orcamento_itens->sum('valor_total');
                            @endphp

                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right font-bold px-4 py-2">Total dos Itens:</td>
                                    <td class="px-4 py-2 font-bold">R$ {{ number_format($totalItens, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Serviços --}}
            <div class="bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Serviços realizados</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-2">Serviço</th>
                                <th class="px-4 py-2">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orcamento_servicos as $servico)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="px-4 py-2">{{ $servico->servico->name ?? '-' }}</td>
                                    <td class="px-4 py-2">R$ {{ number_format($servico->preco, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            @php
                                $totalServicos = $orcamento_servicos->sum('preco');
                            @endphp
                            
                            <tfoot>
                                <tr>
                                    <td class="text-right font-bold px-4 py-2">Total dos Serviços:</td>
                                    <td class="px-4 py-2 font-bold">R$ {{ number_format($totalServicos, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Gastos --}}
            <div class="bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg print-section">
                <h3 class="print-section-title text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Gastos realizados</h3>
                <div class="overflow-x-auto print-table">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-2">Data</th>
                                <th class="px-4 py-2">Banco</th>
                                <th class="px-4 py-2">Controle</th>
                                <th class="px-4 py-2">OBS.</th>
                                <th class="px-4 py-2">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $gasto_inicial = '';
                            @endphp
                            @foreach($orcamento_gastos as $gasto)
                                @if ($gasto_inicial != $gasto->especie) 
                                    @php
                                        $gasto_inicial = $gasto->especie;
                                    @endphp
                                    <tr class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        <td colspan="5" style="padding-left: 20px;">
                                            @if ($gasto_inicial == 'pessoas')
                                                GASTO COM PESSOAL
                                            @else
                                                GASTO COM MATERIAIS
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($gasto->data)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ $gasto->banco->name ? $gasto->banco->name.' - '.$gasto->banco->agencia.' | '.$gasto->banco->conta : '-' }}</td>
                                    <td class="px-4 py-2">{{ $gasto->controle ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $gasto->observacao ?? '-' }}</td>
                                    <td class="px-4 py-2">R$ {{ number_format($gasto->valor, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            @php
                                $totalGastos = $orcamento_gastos->sum('valor');
                            @endphp
                            
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right font-bold px-4 py-2">Total dos Gastos:</td>
                                    <td class="px-4 py-2 font-bold">R$ {{ number_format($totalGastos, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Comissões --}}
            <div class="bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Comissões</h3>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-2">Empresa</th>
                            <th class="px-4 py-2">Comissão</th>
                            <th class="px-4 py-2">Porcentagem</th>
                            <th class="px-4 py-2">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orcamento_comissoes as $comissao)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="px-4 py-2">{{ $comissao->empresa->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $comissao->comissao->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $comissao->porcentagem }}%</td>
                                <td class="px-4 py-2">R$ {{ number_format($comissao->valor_total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Sócios --}}
            <div class="bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Sócios</h3>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-2">Empresa</th>
                            <th class="px-4 py-2">Participação</th>
                            <th class="px-4 py-2">Resultado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orcamento_socios as $socio)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="px-4 py-2">{{ $socio->empresa->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $socio->porcentagem }}%</td>
                                <td class="px-4 py-2">R$ {{ number_format($socio->valor_total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagamentos --}}
            <div class="bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Recebimentos</h3>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-2">Banco</th>
                            <th class="px-4 py-2">Data</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orcamento_pagamentos as $pagamento)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="px-4 py-2">{{ $pagamento->banco->name ? $pagamento->banco->name.' - '.$pagamento->banco->agencia.' | '.$pagamento->banco->conta : '-' }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($pagamento->data)->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ ucfirst($pagamento->controle) }}</td>
                                <td class="px-4 py-2">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    @php
                        $totalPagamentos = $orcamento_pagamentos->sum('valor');
                    @endphp
                    
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right font-bold px-4 py-2">Total dos Pagamentos:</td>
                            <td class="px-4 py-2 font-bold">R$ {{ number_format($totalPagamentos, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
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

            {{-- ASSINATURAS --}}
            <div class="print-only">
                <div class="assinaturas">
                    <div class="assinatura">
                        <div class="linha"></div>
                        <strong>Responsável pela Obra</strong><br>
                        Assinatura
                    </div>
            
                    <div class="assinatura">
                        <div class="linha"></div>
                        <strong>Cliente</strong><br>
                        Assinatura
                    </div>
                </div>
            </div>

            <div class="no-print mb-6">
                <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Imprimir
                </button>
            </div>
        </div>
    </div>
</x-app-layout>