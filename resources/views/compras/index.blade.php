<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">@include('compras.partials.filtro')</div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">  
                @if ($insert)          
                    <div align="right">
                        <x-nav-link :href="route('compras.create')">{{ __('Nova compra') }}</x-nav-link>      
                    </div>      
                @endif
                <table id="minhaTabela" class="table table-striped datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome da obra</th>
                            <th>DT. Compra</th>
                            <th>DT. Prazo</th>
                            <th>DT. Entrega</th>
                            <th>VL. Itens</th>
                            <th>VL. Desconto</th>
                            <th>VL. Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    $(document).ready(function () {
        $('#limpar_filtro').on('click', function (e) {
            $('#filtro_observacao').val('');
            $('#filtro_data_de').val('');
            $('#filtro_data_ate').val('');
            $('#filtro_data_entrega_de').val('');
            $('#filtro_data_entrega_ate').val('');
            $('#filtro_data_prazo_de').val('');
            $('#filtro_data_prazo_ate').val('');

            $('#minhaTabela').DataTable().ajax.reload();
        });

        $('#filtroForm').submit(function (e) {
            e.preventDefault();
            $('#minhaTabela').DataTable().ajax.reload();
        });
        
        $('#minhaTabela').DataTable({
            "processing": true,
            "serverSide": true,
            
            ajax: {
                url: "{{ route('compras.json') }}",
                data: function (d) {
                    d.filtro_observacao = $('#filtro_observacao').val();
                    d.filtro_data_de = $('#filtro_data_de').val();
                    d.filtro_data_ate = $('#filtro_data_ate').val();
                    d.filtro_data_entrega_de = $('#filtro_data_entrega_de').val();
                    d.filtro_data_entrega_ate = $('#filtro_data_entrega_ate').val();
                    d.filtro_data_prazo_de = $('#filtro_data_prazo_de').val();
                    d.filtro_data_prazo_ate = $('#filtro_data_prazo_ate').val();
                }
            },

            "columns": [
                { "data": "id" },
                { "data": "observacao" },
                // { "data": "orcamento_id" },
                { "data": "data_compra",
                    "render": function (data, type, row) {
                        return data ? new Date(data).toLocaleDateString('pt-BR') : "";
                    } 
                },
                { "data": "data_prazo",
                    "render": function (data, type, row) {
                        return data ? new Date(data).toLocaleDateString('pt-BR') : "";
                    }  
                },
                { "data": "data_entrega",
                    "render": function (data, type, row) {
                        return data ? new Date(data).toLocaleDateString('pt-BR') : "";
                    } 
                },
                { "data": "valor_itens" },
                { "data": "valor_desconto" },
                { "data": "valor_total" },
                { 
                    "data": "id", 
                    "render": function (data, type, row) {                      
                        let actions = '';
                        if ({{$update}}) {
                            actions += `<a href="/compras/edit/${data}">Editar</a> `;
                        }
                        if ({{$delete}}) {
                            actions += `<a href="/compras/delete/${data}">Excluir</a>`;
                        }
                        return actions.trim();
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });
    });
</script>