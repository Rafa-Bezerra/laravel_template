<style>
    @media print {
        .no-print {
            display: none !important;
        }
    
        .dataTables_length,
        .dataTables_filter,
        .dataTables_info,
        .dataTables_paginate {
            display: none !important;
        }
    
        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
    
        th, td {
            border: 1px solid #ccc !important;
            padding: 4px 6px !important;
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

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg no-print">@include('relatorios.despesas.partials.filtro_recebimentos')</div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-x-auto">    
                <table id="minhaTabela" class="table table-striped datatable w-full">
                    <thead>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Empresa</th>
                        <th class="px-4 py-2">Valor</th>
                        <th class="px-4 py-2">Parcela</th>
                        <th class="px-4 py-2">Vencimento</th>
                        <th class="px-4 py-2">Banco</th>
                        <th class="px-4 py-2">Controle</th>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="2" style="text-align: right;">Resultado:</th>
                            <th id="total_valor"></th>
                            <th colspan="4"></th>
                        </tr>
                    </tfoot>
                </table>                    
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    $(document).ready(function () {
        $('#limpar_filtro').on('click', function (e) {
            $('#filtro_data_de').val('');
            $('#filtro_data_ate').val('');
            $('#filtro_banco').val('');
            $('#filtro_controle').val('');
            $('#filtro_empresa_id').val('').trigger('change');
            $('#filtro_especie').val('');

            $('#minhaTabela').DataTable().ajax.reload();
        });
        
        $('#filtroForm').submit(function (e) {
            e.preventDefault();
            $('#minhaTabela').DataTable().ajax.reload();
        });

        $('#minhaTabela').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('recebimentos.ajax') }}",
                type: 'GET',
                data: function (d) {
                    d.filtro_empresa_id = $('#filtro_empresa_id').val();
                    d.filtro_data_de = $('#filtro_data_de').val();
                    d.filtro_data_ate = $('#filtro_data_ate').val();
                    d.filtro_banco = $('#filtro_banco').val();
                    d.filtro_controle = $('#filtro_controle').val();
                    d.filtro_especie = $('#filtro_especie').val();
                }
            },
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'empresa', name: 'empresa' },
                { data: 'valor', name: 'valor' },
                { data: 'parcela', name: 'parcela' },
                { data: 'data', name: 'data' },
                { data: 'banco', name: 'banco' },
                { data: 'controle', name: 'controle' },
            ],
            order: [[0, 'desc']],
            footerCallback: function (row, data, start, end, display) {
                let total = 0;

                data.forEach(function (item) {
                    let valor = parseFloat(item.valor.replace(/[R$\.\s]/g, '').replace(',', '.')) || 0;
                    total += valor;
                });

                $('#total_valor').html(
                    'R$ ' + total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                );
            }
        });
    });
</script>