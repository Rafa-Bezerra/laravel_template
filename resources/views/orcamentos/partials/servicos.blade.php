<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Serviços') }}
        </h2>
    </header>

    @if ($permissao_servicos)
        <form id="servicosForm">
            @csrf
            <x-text-input id="servico_id" type="hidden" name="servico_id"/>
            <x-text-input id="servico_orcamento_id" type="hidden" name="servico_orcamento_id" :value="$data->id"/>   
            
            {{-- Serviços --}}
            <div>
                <x-input-label for="servico_servico_id" :value="__('Serviço')" />
                <x-select-input id="servico_servico_id" class="block mt-1 w-full" name="servico_servico_id" :value="old('servico_servico_id')">
                    <option></option>
                    @foreach ($servicos as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error :messages="$errors->get('servico_servico_id')" class="mt-2" />
            </div>

            <!-- Preço -->
            <div>
                <x-input-label for="servico_preco" :value="__('Preço')" />
                <x-text-input id="servico_preco" class="block mt-1 w-full totalizador" type="text" name="servico_preco" :value="old('fone')"  />
                <x-input-error :messages="$errors->get('servico_preco')" class="mt-2" />
            </div>

            <!-- DT. Item -->
            <div>
                <x-input-label for="servico_data" :value="__('Data')" />
                <x-text-input id="servico_data" class="block mt-1 w-full" type="text" name="servico_data" :value="old('servico_data') ? old('servico_data') : date('d/m/Y')"  />
                <x-input-error :messages="$errors->get('servico_data')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-4">
                    {{ __('Salvar') }}
                </x-primary-button>
            </div>
        </form>
    @endif

    <table id="minhaTabelaServicos" class="table table-striped datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Serviço</th>
                <th>Preço</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</section>
<script>
    function editarServico(id) {
        $.ajax({
            url: "/orcamentos/servicos/get/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                $('#servico_id').val(response.id);
                $('#servico_servico_id').val(response.servico_id);
                $('#servico_preco').val(response.preco);
                $('#servico_data').val(formatarData(response.data));
            }
        });
    }

    function excluirServico(id) {
        console.log('a');
        $.ajax({
            url: "/orcamentos/servicos/delete/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            complete: function (response) {
                $('.datatable').DataTable().ajax.reload();
                $('#servicosForm')[0].reset();  
                
                $('#valor_itens').val(response.responseJSON.valor_itens);
                $('#valor_desconto').val(response.responseJSON.valor_desconto);
                $('#valor_total').val(response.responseJSON.valor_total);
                $('#valor_servicos').val(response.responseJSON.valor_servicos);
                $('#valor_saldo').val(response.responseJSON.valor_saldo);
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        //servico_data
        let servico_data = document.getElementById("servico_data");
        servico_data.addEventListener("input", function (e) {
            let servico_data_value = e.target.value.replace(/\D/g, "");
            if (servico_data_value.length > 2) servico_data_value = servico_data_value.substring(0,2) + "/" + servico_data_value.substring(2);
            if (servico_data_value.length > 5) servico_data_value = servico_data_value.substring(0,5) + "/" + servico_data_value.substring(5,9);
            e.target.value = servico_data_value;
        });
        let servico_data_oldValue = "{{ $data->servico_data }}";
        if (servico_data_oldValue) {
            let servico_data_date = new Date(servico_data_oldValue);
            if (!isNaN(servico_data_date)) {
                servico_data.value = servico_data_date.toLocaleDateString("pt-BR");
            }
        }
    });
    
    $(document).ready(function () {
        $('#minhaTabelaServicos').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('orcamentos_servicos.json') }}",
                "type": "GET",
                "data": function (d) {
                    d.orcamento_id = {{ $data->id ?? 'null' }}; 
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "servico_name" },
                { "data": "preco",
                    "render": function (data) {
                        return formatarMoeda(data);
                    } 
                },
                { "data": "data",
                    "render": function (data, type, row) {
                        return data ? new Date(data).toLocaleDateString('pt-BR') : "";
                    } 
                },
                { 
                    "data": "id", 
                    "render": function (data, type, row) {
                        let permissao_servicos = @json($permissao_servicos);   
                        let actions = '';
                        if (permissao_servicos) {
                            actions += `<a onclick="editarServico(${data})">Editar</a> <a onclick="excluirServico(${data})">Excluir</a>`;
                        }
                        return actions.trim();
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });

        $('#servicosForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('orcamentos_servicos.submit') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                complete: function (response) {
                    $('.datatable').DataTable().ajax.reload();
                    $('#servicosForm')[0].reset();
                    $('#servico_id').val('');
                
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