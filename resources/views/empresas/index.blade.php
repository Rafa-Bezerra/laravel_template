<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">    
                @if ($insert)
                    <div align="right">
                        <x-nav-link :href="route('empresas.create')">{{ __('Nova empresa') }}</x-nav-link>      
                    </div>
                @endif     
                <table id="minhaTabela" class="table table-striped datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Fantasia</th>
                            <th>Pessoa</th>
                            <th>CPF</th>
                            <th>CNPJ</th>
                            <th>Divisão</th>
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
        $('#minhaTabela').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('empresas.json') }}",
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "fantasia" },
                { "data": "pessoa" },
                { "data": "cpf" },
                { "data": "cnpj" },
                { "data": "divisao_name" },
                { 
                    "data": "id", 
                    "render": function (data, type, row) {                    
                        let actions = '';
                        if ({{$update}}) {
                            actions += `<a href="/empresas/edit/${data}">Editar</a> `;
                        }
                        if ({{$delete}}) {
                            actions += `<a href="/empresas/delete/${data}">Excluir</a>`;
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