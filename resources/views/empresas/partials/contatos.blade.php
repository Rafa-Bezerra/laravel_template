<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Contatos') }}
        </h2>
    </header>

    <form id="contatosForm">
        @csrf
        <x-text-input id="contato_id" type="hidden" name="contato_id"/>
        <x-text-input id="contato_empresa_id" type="hidden" name="contato_empresa_id" :value="$data->id"/>        
            
        <!-- Name -->
        <div>
            <x-input-label for="contato_contato" :value="__('Nome')" />
            <x-text-input id="contato_contato" class="block mt-1 w-full" type="text" name="contato_contato" :value="old('contato_contato')" required autofocus autocomplete="contato_contato" />
            <x-input-error :messages="$errors->get('contato_contato')" class="mt-2" />
        </div>

        <!-- Fone -->
        <div>
            <x-input-label for="contato_fone" :value="__('Fone')" />
            <x-text-input id="contato_fone" class="block mt-1 w-full" type="text" name="contato_fone" :value="old('fone')"  />
            <x-input-error :messages="$errors->get('contato_fone')" class="mt-2" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="contato_email" :value="__('Email')" />
            <x-text-input id="contato_email" class="block mt-1 w-full" type="email" name="contato_email" :value="old('contato_email')" />
            <x-input-error :messages="$errors->get('contato_email')" class="mt-2" />
        </div>

        <!-- Observação -->
        <div>
            <x-input-label for="contato_observacao" :value="__('Observação')" />
            <x-text-input id="contato_observacao" class="block mt-1 w-full" type="text" name="contato_observacao" :value="old('contato_observacao')" />
            <x-input-error :messages="$errors->get('contato_observacao')" class="mt-2" />
        </div>
            
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Salvar') }}
            </x-primary-button>
        </div>
    </form>

    <table id="minhaTabelaContatos" class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Fone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</section>
<script>
    function editarContato(id) {
        $.ajax({
            url: "/empresas/contatos/get/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                $('#contato_id').val(response.id);
                $('#contato_contato').val(response.contato);
                $('#contato_fone').val(response.fone);
                $('#contato_email').val(response.email);
                $('#contato_observacao').val(response.observacao);
            }
        });
    }

    function excluirContato(id) {
        $.ajax({
            url: "/empresas/contatos/delete/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            complete: function (response) {
                $('#minhaTabelaContatos').DataTable().ajax.reload();
                $('#contatosForm')[0].reset();
            }
        });
    }

    $(document).ready(function () {
        $('#minhaTabelaContatos').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('empresas_contatos.json') }}",
                "type": "GET",
                "data": function (d) {
                    d.empresa_id = {{ $data->id ?? 'null' }}; 
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "contato" },
                { "data": "fone" },
                { "data": "email" },
                { 
                    "data": "id", 
                    "render": function (data, type, row) {
                        return `<a onclick="editarContato(${data})">Editar</a> <a onclick="excluirContato(${data})">Excluir</a>`;
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });

        $('#contatosForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('empresas_contatos.submit') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                complete: function (response) {
                    $('#minhaTabelaContatos').DataTable().ajax.reload();
                    $('#contatosForm')[0].reset();
                }
            });
        });
    });
</script>