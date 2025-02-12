<style>
    #minhaTabela {
        color: white !important;
    }
    .dataTables_wrapper {
        color: white !important;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Acessos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">    
                <div align="right">
                    <x-nav-link :href="route('roles.create')">{{ __('Novo acesso') }}</x-nav-link>      
                </div>      
                <table id="minhaTabela" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
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
            "ajax": "{{ route('roles.json') }}",
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { 
                    "data": "id", 
                    "render": function (data, type, row) {
                        return `<a href="/roles/editar/${data}">Editar</a>`;
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });
    });
</script>