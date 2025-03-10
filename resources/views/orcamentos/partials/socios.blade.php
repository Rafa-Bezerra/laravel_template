<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Sócios') }}
        </h2>
    </header>

    <form id="socioForm">
        @csrf
        <x-text-input id="socio_id" type="hidden" name="socio_id"/>
        <x-text-input id="socio_orcamento_id" type="hidden" name="socio_orcamento_id" :value="$data->id"/>    
        
        {{-- Empresas --}}
        <div>
            <x-input-label for="socio_empresa_id" :value="__('Empresa')" />
            <x-select-input id="socio_empresa_id" class="block mt-1 w-full" name="socio_empresa_id" :value="old('socio_empresa_id')">
                <option></option>
                @foreach ($empresas as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </x-select-input>
            <x-input-error :messages="$errors->get('socio_empresa_id')" class="mt-2" />
        </div>

        <!-- Porcentagem -->
        <div>
            <x-input-label for="socio_porcentagem" :value="__('Porcentagem')" />
            <x-text-input id="socio_porcentagem" class="block mt-1 w-full totalizador" type="text" name="socio_porcentagem" :value="old('fone')"  />
            <x-input-error :messages="$errors->get('socio_porcentagem')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Salvar') }}
            </x-primary-button>
        </div>
    </form>

    <table id="minhaTabelaSocios" class="table table-striped datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Empresa</th>
                <th>Porcentagem</th>
                <th>VL. Total</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</section>
<script>
    function editarSocio(id) {
        $.ajax({
            url: "/orcamentos/socios/get/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                $('#socio_id').val(response.id);
                $('#socio_empresa_id').val(response.empresa_id);
                $('#socio_porcentagem').val(response.porcentagem);
            }
        });
    }

    function excluirSocio(id) {
        console.log('a');
        $.ajax({
            url: "/orcamentos/socios/delete/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            complete: function (response) {
                $('.datatable').DataTable().ajax.reload();
                $('#socioForm')[0].reset();     
                
                $('#valor_itens').val(response.responseJSON.valor_itens);
                $('#valor_desconto').val(response.responseJSON.valor_desconto);
                $('#valor_total').val(response.responseJSON.valor_total);
                $('#valor_servicos').val(response.responseJSON.valor_servicos);
                $('#valor_saldo').val(response.responseJSON.valor_saldo);   
            }
        });
    }
    
    $(document).ready(function () {
        $('#minhaTabelaSocios').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('orcamentos_socios.json') }}",
                "type": "GET",
                "data": function (d) {
                    d.orcamento_id = {{ $data->id ?? 'null' }}; 
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "empresa_name" },
                { "data": "porcentagem",
                    "render": function (data) {
                        return formatarNumero(data);
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
                        return `<a onclick="editarSocio(${data})">Editar</a> <a onclick="excluirSocio(${data})">Excluir</a>`;
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });

        $('#socioForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('orcamentos_socios.submit') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                complete: function (response) {
                    $('.datatable').DataTable().ajax.reload();
                    $('#socioForm')[0].reset();
                    $('#socio_id').val('');
                
                    $('#valor_itens').val(response.responseJSON.valor_itens);
                    $('#valor_desconto').val(response.responseJSON.valor_desconto);
                    $('#valor_total').val(response.responseJSON.valor_total);
                    $('#valor_servicos').val(response.responseJSON.valor_servicos);
                    $('#valor_saldo').val(response.responseJSON.valor_saldo);
                }
            });
        });
    });
</script>