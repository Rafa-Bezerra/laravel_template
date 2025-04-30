<style>
    @media print {
        body {
            background: white;
            color: black;
        }
    
        .no-print {
            display: none !important;
        }
    
        .print\:block {
            display: block !important;
        }
    
        .print\:w-full {
            width: 100% !important;
        }
    
        .print\:text-sm {
            font-size: 0.875rem !important;
        }
    
        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
    
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
        }
    
        thead {
            background-color: #f3f4f6;
        }
    
        .page-break {
            page-break-after: always;
        }
    }
</style>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Orçamento Info --}}
            <div class="bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informações da obra</h3>
                <p><strong>ID:</strong> {{ $orcamento->id }}</p>
                <p><strong>Cliente:</strong> {{ $orcamento->empresa->name ?? '-' }}</p>
                <p><strong>Endereço:</strong> {{ $orcamento->endereco ? $orcamento->endereco->rua . " - " . $orcamento->endereco->numero : '-' }}</p>
                <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($orcamento->data_venda)->format('d/m/Y') }}</p>
                <p><strong>Prazo:</strong> {{ \Carbon\Carbon::parse($orcamento->data_prazo)->format('d/m/Y') }}</p>
                <p><strong>Orçamento:</strong> R$ {{ number_format($orcamento->valor_orcamento, 2, ',', '.') }}</p>
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
                            <th class="px-4 py-2">Valor</th>
                            <th class="px-4 py-2">Data</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orcamento_pagamentos as $pagamento)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="px-4 py-2">{{ $pagamento->banco->name ? $pagamento->banco->name.' - '.$pagamento->banco->agencia.' | '.$pagamento->banco->conta : '-' }}</td>
                                <td class="px-4 py-2">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($pagamento->data)->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ ucfirst($pagamento->controle) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="no-print mb-6">
                <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Imprimir
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    $(document).ready(function () {
       
    });
</script>