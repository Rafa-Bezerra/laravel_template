<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Endereços') }}
        </h2>
    </header>

    <form id="enderecoForm">
        @csrf
        <x-text-input id="endereco_id" type="hidden" name="endereco_id"/>
        <x-text-input id="endereco_empresa_id" type="hidden" name="endereco_empresa_id" :value="$data->id"/>      

        <!-- CEP -->
        <div>
            <x-input-label for="endereco_cep" :value="__('CEP')" />
            <x-text-input id="endereco_cep" class="block mt-1 w-full" type="text" name="endereco_cep" :value="old('endereco_cep')" required autofocus autocomplete="endereco_cep" />
            <x-input-error :messages="$errors->get('endereco_cep')" class="mt-2" />
        </div>

        <!-- Estado -->
        <div>
            <x-input-label for="endereco_estado" :value="__('Estado')" />
            <x-text-input id="endereco_estado" class="block mt-1 w-full" type="text" name="endereco_estado" :value="old('fone')"  />
            <x-input-error :messages="$errors->get('endereco_estado')" class="mt-2" />
        </div>

        <!-- Cidade -->
        <div>
            <x-input-label for="endereco_cidade" :value="__('Cidade')" />
            <x-text-input id="endereco_cidade" class="block mt-1 w-full" type="text" name="endereco_cidade" :value="old('endereco_cidade')" />
            <x-input-error :messages="$errors->get('endereco_cidade')" class="mt-2" />
        </div>
        
        <!-- Rua -->
        <div>
            <x-input-label for="endereco_rua" :value="__('Rua')" />
            <x-text-input id="endereco_rua" class="block mt-1 w-full" type="text" name="endereco_rua" :value="old('endereco_rua')" />
            <x-input-error :messages="$errors->get('endereco_rua')" class="mt-2" />
        </div>

        <!-- Número -->
        <div>
            <x-input-label for="endereco_numero" :value="__('Número')" />
            <x-text-input id="endereco_numero" class="block mt-1 w-full" type="text" name="endereco_numero" :value="old('fone')"  />
            <x-input-error :messages="$errors->get('endereco_numero')" class="mt-2" />
        </div>

        <!-- Complemento -->
        <div>
            <x-input-label for="endereco_complemento" :value="__('Complemento')" />
            <x-text-input id="endereco_complemento" class="block mt-1 w-full" type="text" name="endereco_complemento" :value="old('endereco_complemento')" />
            <x-input-error :messages="$errors->get('endereco_complemento')" class="mt-2" />
        </div>

        <!-- Observação -->
        <div>
            <x-input-label for="endereco_observacao" :value="__('Observação')" />
            <x-text-input id="endereco_observacao" class="block mt-1 w-full" type="text" name="endereco_observacao" :value="old('endereco_observacao')" />
            <x-input-error :messages="$errors->get('endereco_observacao')" class="mt-2" />
        </div>
            
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Salvar') }}
            </x-primary-button>
        </div>
    </form>

    <table id="minhaTabelaEnderecos" class="table table-striped datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>CEP</th>
                <th>Estado</th>
                <th>Cidade</th>
                <th>Rua</th>
                <th>Número</th>
                <th>Complemento</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</section>
<script>
    function editarEndereco(id) {
        $.ajax({
            url: "/empresas/enderecos/get/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                $('#endereco_id').val(response.id);
                $('#endereco_cep').val(response.cep);
                $('#endereco_estado').val(response.estado);
                $('#endereco_cidade').val(response.cidade);
                $('#endereco_rua').val(response.rua);
                $('#endereco_numero').val(response.numero);
                $('#endereco_complemento').val(response.complemento);
                $('#endereco_observacao').val(response.observacao);
            }
        });
    }

    function excluirEndereco(id) {
        $.ajax({
            url: "/empresas/enderecos/delete/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            complete: function (response) {
                $('#minhaTabelaEnderecos').DataTable().ajax.reload();
                $('#enderecoForm')[0].reset();
            }
        });
    }

    $(document).ready(function () {
        $('#minhaTabelaEnderecos').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('empresas_enderecos.json') }}",
                "type": "GET",
                "data": function (d) {
                    d.empresa_id = {{ $data->id ?? 'null' }}; 
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "cep" },
                { "data": "estado" },
                { "data": "cidade" },
                { "data": "rua" },
                { "data": "numero" },
                { "data": "complemento" },
                { 
                    "data": "id", 
                    "render": function (data, type, row) {
                        return `<a onclick="editarEndereco(${data})">Editar</a> <a onclick="excluirEndereco(${data})">Excluir</a>`;
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });

        $('#enderecoForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('empresas_enderecos.submit') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                complete: function (response) {
                    $('#minhaTabelaEnderecos').DataTable().ajax.reload();
                    $('#enderecoForm')[0].reset();
                }
            });
        });

        $('#endereco_cep').on('blur', function () {
            let cep = $(this).val().replace(/\D/g, ''); // Remove caracteres não numéricos

            if (cep.length === 8) {
                $.ajax({
                    url: `https://viacep.com.br/ws/${cep}/json/`,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function () {
                        $('#rua, #bairro, #cidade, #estado').val("Buscando...");
                    },
                    success: function (response) {
                        if (!response.erro) {
                            $('#endereco_estado').val(response.uf);
                            $('#endereco_cidade').val(response.localidade);
                            $('#endereco_rua').val(response.logradouro);
                            $('#endereco_numero').focus();
                        } else {
                            alert("CEP não encontrado.");
                            $('#rua, #bairro, #cidade, #estado').val("");
                        }
                    },
                    error: function () {
                        alert("Erro ao buscar o CEP.");
                        $('#rua, #bairro, #cidade, #estado').val("");
                    }
                });
            } else {
                alert("CEP inválido. Digite 8 números.");
            }
        });
    });
</script>