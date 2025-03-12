<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-x-auto">    
                @if ($insert)
                    <div align="right">
                        <x-nav-link :href="route('orcamentos.create')">{{ __('Novo orçamento') }}</x-nav-link>      
                    </div>  
                @endif    
                <table id="minhaTabela" class="table nowrap table-striped datatable w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Controle</th>
                            <th>Empresa</th>
                            <th>Endereço</th>
                            <th>DT. Venda</th>
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
    function formatarMoeda(valor) {
        if (!valor) return "R$ 0,00"; // Caso o valor seja null ou undefined
        return parseFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    function formatarNumero(valor) {
        if (!valor) return "0"; // Evita exibição de "NaN"
    return parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    }
    $(document).ready(function () {
        $('#minhaTabela').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('orcamentos.json') }}",
            "columns": [
                { "data": "id" },
                { "data": "controle" },
                { "data": "empresa_name" },
                { "data": "empresas_endereco_descricao" },
                { "data": "data_venda",
                    "render": function (data, type, row) {
                        return data ? new Date(data).toLocaleDateString('pt-BR') : "";
                    }  
                },
                { "data": "data_prazo" ,
                    "render": function (data, type, row) {
                        return data ? new Date(data).toLocaleDateString('pt-BR') : "";
                    } 
                },
                { "data": "data_entrega" ,
                    "render": function (data, type, row) {
                        return data ? new Date(data).toLocaleDateString('pt-BR') : "";
                    } 
                },
                { "data": "valor_itens",
                    "render": function (data) {
                        return formatarMoeda(data);
                    }
                },
                { "data": "valor_desconto",
                    "render": function (data) {
                        return formatarMoeda(data);
                    }
                },
                { "data": "valor_total",
                    "render": function (data) {
                        return formatarMoeda(data);
                    }
                },
                { 
                    "data": "id", 
                    "render": function (data, type, row) {               
                        let actions = '';
                        if ({{$update}}) {
                            actions += `<a href="/orcamentos/edit/${data}">Editar</a> `;
                        }
                        if ({{$delete}}) {
                            actions += `<a href="/orcamentos/delete/${data}">Excluir</a>`;
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