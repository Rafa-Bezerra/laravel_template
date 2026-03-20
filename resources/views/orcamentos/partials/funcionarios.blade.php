<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Funcionários') }}
        </h2>
    </header>

    @if ($permissao_funcionarios)
        <form id="funcionarioForm">
            @csrf
            <x-text-input id="funcionario_id" type="hidden" name="funcionario_id"/>
            <x-text-input id="funcionario_orcamento_id" type="hidden" name="funcionario_orcamento_id" :value="$data->id"/>    
            
            {{-- Empresas --}}
            <div>
                <x-input-label for="funcionario_empresa_id" :value="__('Empresa')" />
                <x-select-input id="funcionario_empresa_id" class="select2 block mt-1 w-full" name="funcionario_empresa_id" :value="old('funcionario_empresa_id')">
                    <option></option>
                    @foreach ($funcionarios as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error :messages="$errors->get('funcionario_empresa_id')" class="mt-2" />
            </div>

            <!-- Quantidade -->
            <div>
                <x-input-label for="funcionario_quantidade" :value="__('Quantidade')" />
                <x-number-input id="funcionario_quantidade" class="block mt-1 w-full totalizador" type="text" name="funcionario_quantidade" :value="old('fone')"  />
                <x-input-error :messages="$errors->get('funcionario_quantidade')" class="mt-2" />
            </div>

            <!-- Valor -->
            <div>
                <x-input-label for="funcionario_valor" :value="__('Valor')" />
                <x-money-input id="funcionario_valor" class="block mt-1 w-full totalizador" type="text" name="funcionario_valor" :value="old('funcionario_valor')" />
                <x-input-error :messages="$errors->get('funcionario_valor')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-4">
                    {{ __('Salvar') }}
                </x-primary-button>
            </div>
        </form>
    @endif

    <table id="minhaTabelaFuncionarios" class="table table-striped datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Empresa</th>
                <th>Quantidade</th>
                <th>Valor</th>
                <th>Total</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</section>
<script>
    function editarFuncionario(id) {
        $.ajax({
            url: "/orcamentos/funcionarios/get/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                $('#funcionario_id').val(response.id);
                $('#funcionario_empresa_id').val(response.empresa_id).trigger('change');
                $('#funcionario_quantidade').val(formatNumerico(response.quantidade));
                $('#funcionario_valor').val(formatMonetario(response.valor));
            }
        });
    }

    function excluirFuncionario(id) {
        $.ajax({
            url: "/orcamentos/funcionarios/delete/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            complete: function (response) {
                $('.datatable').DataTable().ajax.reload();
                $('#funcionarioForm')[0].reset();     
                
                let data = response.responseJSON;

                $('#valor_itens').val(formatMonetario(data.valor_itens));
                $('#valor_desconto').val(formatMonetario(data.valor_desconto));
                $('#valor_total').val(formatMonetario(data.valor_total));
                $('#valor_servicos').val(formatMonetario(data.valor_servicos));
                $('#valor_saldo').val(formatMonetario(data.valor_saldo));
            }
        });
    }
    
    $(document).ready(function () {
        $('#minhaTabelaFuncionarios').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('orcamentos_funcionarios.json') }}",
                "type": "GET",
                "data": function (d) {
                    d.orcamento_id = {{ $data->id ?? 'null' }}; 
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "empresa_name" },
                { "data": "quantidade" },
                { "data": "valor",
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
                        let permissao_funcionarios = @json($permissao_funcionarios);   
                        let actions = '';
                        if (permissao_funcionarios) {
                            actions += `<a onclick="editarFuncionario(${data})">Editar</a> <a onclick="excluirFuncionario(${data})">Excluir</a>`;
                        }
                        return actions.trim();
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });

        $('#funcionarioForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('orcamentos_funcionarios.submit') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                complete: function (response) {
                    $('.datatable').DataTable().ajax.reload();
                    $('#funcionarioForm')[0].reset();
                    $('#funcionario_empresa_id').val('').trigger('change');
                    $('#funcionario_id').val('');
                    $('#funcionario_quantidade').val(0);
                    $('#funcionario_valor').val(0);
                
                    let data = response.responseJSON;

                    $('#valor_itens').val(formatMonetario(data.valor_itens));
                    $('#valor_desconto').val(formatMonetario(data.valor_desconto));
                    $('#valor_total').val(formatMonetario(data.valor_total));
                    $('#valor_servicos').val(formatMonetario(data.valor_servicos));
                    $('#valor_saldo').val(formatMonetario(data.valor_saldo));
                }
            });
        });
    });
</script>