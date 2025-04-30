<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Comissões') }}
        </h2>
    </header>

    @if ($permissao_comissoes)
        <form id="comissaoForm">
            @csrf
            <x-text-input id="comissao_id" type="hidden" name="comissao_id"/>
            <x-text-input id="comissao_orcamento_id" type="hidden" name="comissao_orcamento_id" :value="$data->id"/>    
            
            {{-- Empresas --}}
            <div>
                <x-input-label for="comissao_empresa_id" :value="__('Empresa')" />
                <x-select-input id="comissao_empresa_id" class="select2 block mt-1 w-full" name="comissao_empresa_id" :value="old('comissao_empresa_id')">
                    <option></option>
                    @foreach ($empresas as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error :messages="$errors->get('comissao_empresa_id')" class="mt-2" />
            </div>
            
            {{-- Comissão --}}
            <div>
                <x-input-label for="comissao_comissao_id" :value="__('Comissão')" />
                <x-select-input id="comissao_comissao_id" class="block mt-1 w-full" name="comissao_comissao_id" :value="old('comissao_comissao_id')">
                    <option></option>
                    @foreach ($comissoes as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error :messages="$errors->get('comissao_comissao_id')" class="mt-2" />
            </div>

            <!-- Porcentagem -->
            <div>
                <x-input-label for="comissao_porcentagem" :value="__('Porcentagem')" />
                <x-text-input id="comissao_porcentagem" class="block mt-1 w-full totalizador" type="text" name="comissao_porcentagem" :value="old('fone')"  />
                <x-input-error :messages="$errors->get('comissao_porcentagem')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-4">
                    {{ __('Salvar') }}
                </x-primary-button>
            </div>
        </form>
    @endif

    <table id="minhaTabelaComissoes" class="table table-striped datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Empresa</th>
                <th>Comissão</th>
                <th>Porcentagem</th>
                <th>VL. Total</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</section>
<script>
    function editarComissao(id) {
        $.ajax({
            url: "/orcamentos/comissoes/get/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                $('#comissao_id').val(response.id);
                $('#comissao_empresa_id').val(response.empresa_id);
                $('#comissao_comissao_id').val(response.comissao_id);
                $('#comissao_porcentagem').val(response.porcentagem);
            }
        });
    }

    function excluirComissao(id) {
        $.ajax({
            url: "/orcamentos/comissoes/delete/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            complete: function (response) {
                $('.datatable').DataTable().ajax.reload();
                $('#comissaoForm')[0].reset();     

                $('#valor_itens').val(response.responseJSON.valor_itens);
                $('#valor_desconto').val(response.responseJSON.valor_desconto);
                $('#valor_total').val(response.responseJSON.valor_total);
                $('#valor_servicos').val(response.responseJSON.valor_servicos);
                $('#valor_saldo').val(response.responseJSON.valor_saldo);   
            }
        });
    }
    
    $(document).ready(function () {
        $('#minhaTabelaComissoes').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('orcamentos_comissoes.json') }}",
                "type": "GET",
                "data": function (d) {
                    d.orcamento_id = {{ $data->id ?? 'null' }}; 
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "empresa_name" },
                { "data": "comissao_name" },
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
                        let permissao_comissoes = @json($permissao_comissoes);          
                        let actions = '';
                        if (permissao_comissoes) {
                            actions += `<a onclick="editarComissao(${data})">Editar</a> <a onclick="excluirComissao(${data})">Excluir</a>`;
                        }
                        return actions.trim();
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });

        $('#comissaoForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('orcamentos_comissoes.submit') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                complete: function (response) {
                    $('.datatable').DataTable().ajax.reload();
                    $('#comissaoForm')[0].reset();
                    $('#comissao_id').val('');

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