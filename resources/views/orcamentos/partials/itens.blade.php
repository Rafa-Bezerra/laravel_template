<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Materiais empregados em obra') }}
        </h2>
    </header>

    @if ($permissao_itens)
        <form id="itensForm">
            @csrf
            <x-text-input id="item_id" type="hidden" name="item_id"/>
            <x-text-input id="item_orcamento_id" type="hidden" name="item_orcamento_id" :value="$data->id"/>             

            <!-- DT. Item -->
            <div>
                <x-input-label for="item_data" :value="__('Data')" />
                <x-text-input id="item_data" class="block mt-1 w-full" type="text" name="item_data" :value="old('item_data') ? old('item_data') : date('d/m/Y')"  />
                <x-input-error :messages="$errors->get('item_data')" class="mt-2" />
            </div>   
            
            {{-- Material --}}
            <div>
                <x-input-label for="item_material_id" :value="__('Material')" />
                <x-select-input id="item_material_id" class="select2 block mt-1 w-full" name="item_material_id" :value="old('item_material_id')">
                    <option></option>
                    @foreach ($materiais as $item)
                        <option value="{{$item->id}}">{{$item->id.' - '.$item->name.' || ESTOQUE GERAL: '.$item->disponibilidade_geral.' '.$item->unidade_de_medida.' || ESTOQUE LOCAL: '.$item->disponibilidade_local.' '.$item->unidade_de_medida}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error :messages="$errors->get('item_material_id')" class="mt-2" />
            </div>


            <!-- Quantidade -->
            <div>
                <x-input-label for="item_quantidade" :value="__('Quantidade')" />
                <x-number-input id="item_quantidade" class="block mt-1 w-full totalizador" type="text" name="item_quantidade" :value="old('fone')"  />
                <x-input-error :messages="$errors->get('item_quantidade')" class="mt-2" />
            </div>

            <!-- PR. Unitário -->
            <div>
                <x-input-label for="item_preco_unitario" :value="__('PR. Unitário')" />
                <x-money-input id="item_preco_unitario" class="block mt-1 w-full totalizador" type="text" name="item_preco_unitario" :value="old('item_preco_unitario')" />
                <x-input-error :messages="$errors->get('item_preco_unitario')" class="mt-2" />
            </div>

            <!-- VL. Desconto -->
            <div>
                <x-input-label for="item_valor_desconto" :value="__('VL. Desconto')" />
                <x-money-input id="item_valor_desconto" class="block mt-1 w-full totalizador" type="text" name="item_valor_desconto" :value="old('item_valor_desconto')" />
                <x-input-error :messages="$errors->get('item_valor_desconto')" class="mt-2" />
            </div>

            <!-- VL. Total -->
            <div>
                <x-input-label for="item_valor_total" :value="__('VL. Total')" />
                <x-money-input id="item_valor_total" class="block mt-1 w-full totalizador" type="text" name="item_valor_total" :value="old('item_valor_total')" />
                <x-input-error :messages="$errors->get('item_valor_total')" class="mt-2" />
            </div>

            <!-- Observação -->
            <div>
                <x-input-label for="item_observacao" :value="__('Observação')" />
                <x-text-input id="item_observacao" class="block mt-1 w-full" type="text" name="item_observacao" :value="old('item_observacao')" />
                <x-input-error :messages="$errors->get('item_observacao')" class="mt-2" />
            </div>
                
            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-4">
                    {{ __('Salvar') }}
                </x-primary-button>
            </div>
        </form>
    @endif

    <table id="minhaTabelaItens" class="table table-striped datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Material</th>
                <th>Quantidade</th>
                <th>PR. Unitário</th>
                <th>VL. Desconto</th>
                <th>VL. Total</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</section>
<script>
    function editarItem(id) {
        $.ajax({
            url: "/orcamentos/itens/get/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                $('#item_id').val(response.id);
                $('#item_data').val(formatarData(response.data));
                $('#item_material_id').val(response.material_id).trigger('change');
                $('#item_quantidade').val(formatNumerico(response.quantidade));
                $('#item_preco_unitario').val(formatMonetario(response.preco_unitario));
                $('#item_valor_desconto').val(formatMonetario(response.valor_desconto));
                $('#item_valor_total').val(formatMonetario(response.valor_total));
            }
        });
    }

    function excluirItem(id) {
        console.log('a');
        $.ajax({
            url: "/orcamentos/itens/delete/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            complete: function (response) {
                $('.datatable').DataTable().ajax.reload();
                $('#itensForm')[0].reset();              
                
                let data = response.responseJSON;

                $('#valor_itens').val(formatMonetario(data.valor_itens));
                $('#valor_desconto').val(formatMonetario(data.valor_desconto));
                $('#valor_total').val(formatMonetario(data.valor_total));
                $('#valor_servicos').val(formatMonetario(data.valor_servicos));
                $('#valor_saldo').val(formatMonetario(data.valor_saldo));
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        //item_data
        let item_data = document.getElementById("item_data");
        item_data.addEventListener("input", function (e) {
            let item_data_value = e.target.value.replace(/\D/g, "");
            if (item_data_value.length > 2) item_data_value = item_data_value.substring(0,2) + "/" + item_data_value.substring(2);
            if (item_data_value.length > 5) item_data_value = item_data_value.substring(0,5) + "/" + item_data_value.substring(5,9);
            e.target.value = item_data_value;
        });
        let item_data_oldValue = "{{ $data->item_data }}";
        if (item_data_oldValue) {
            let item_data_date = new Date(item_data_oldValue);
            if (!isNaN(item_data_date)) {
                item_data.value = item_data_date.toLocaleDateString("pt-BR");
            }
        }
    });

    $(document).ready(function () {
        $('#minhaTabelaItens').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('orcamentos_itens.json') }}",
                "type": "GET",
                "data": function (d) {
                    d.orcamento_id = {{ $data->id ?? 'null' }}; 
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "data" ,
                    "render": function (data, type, row) {
                        return data ? new Date(data).toLocaleDateString('pt-BR') : "";
                    } 
                },
                { "data": "material_name" },
                { "data": "quantidade" ,
                    "render": function (data) {
                        return formatarNumero(data);
                    }
                },
                { "data": "preco_unitario",
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
                        let permissao_itens = @json($permissao_itens);   
                        let actions = '';
                        if (permissao_itens) {
                            actions += `<a onclick="editarItem(${data})">Editar</a> <a onclick="excluirItem(${data})">Excluir</a>`;
                        }
                        return actions.trim();
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });

        $('#item_material_id').on('change', function () {
            let materialId = $('#item_material_id').val();
            let empresaId = $('#empresa_id').val();
            $.ajax({
                url: "{{ route('orcamentos_itens.estoque') }}",
                type: "POST",
                data: {
                    material_id: materialId,
                    empresa_id : empresaId,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                complete: function (response) {
                    let data = response.responseJSON.data;

                    $('#item_preco_unitario').val(formatMonetario(data.valor));

                    let quantidade = parseToFloatBr($('#item_quantidade').val());
                    let preco = parseToFloatBr($('#item_preco_unitario').val());
                    let desconto = parseToFloatBr($('#item_valor_desconto').val());

                    let total = (quantidade * preco) - desconto;

                    $('#item_valor_total').val(formatMonetario(total));
                }
            });
        });

        $('#itensForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('orcamentos_itens.submit') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                complete: function (response) {
                    $('.datatable').DataTable().ajax.reload();
                    $('#itensForm')[0].reset();
                    $('#item_material_id').val('').trigger('change');
                    $('#item_id').val('');
                
                    let data = response.responseJSON;

                    $('#valor_itens').val(formatMonetario(data.valor_itens));
                    $('#valor_desconto').val(formatMonetario(data.valor_desconto));
                    $('#valor_total').val(formatMonetario(data.valor_total));
                    $('#valor_servicos').val(formatMonetario(data.valor_servicos));
                    $('#valor_saldo').val(formatMonetario(data.valor_saldo));
                }
            });
        });

        $('.totalizador').on('input', function() {
            let quantidade = parseToFloatBr($('#item_quantidade').val());
            let preco = parseToFloatBr($('#item_preco_unitario').val());
            let desconto = parseToFloatBr($('#item_valor_desconto').val());

            let total = (quantidade * preco) - desconto;

            $('#item_valor_total').val(formatMonetario(total));
        });

        $('#item_quantidade').on('input', function () {
            let quantidade = parseToFloatBr($('#item_quantidade').val());
            let materialId = $('#item_material_id').val();
            let empresaId = $('#empresa_id').val();
            $.ajax({
                url: "{{ route('orcamentos_itens.estoque') }}",
                type: "POST",
                data: {
                    material_id : materialId,
                    empresa_id : empresaId,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function (response) {
                    const data = response.data;
                    const estoqueDisponivel = data.quantidade;
                    const tipoEstoque = data.tipo_estoque;
                    const estoqueEncontrado = data.estoque_encontrado;

                    if (quantidade > estoqueDisponivel) {
                        $('#item_quantidade').val(formatNumerico(estoqueDisponivel));

                        let msg = '';

                        if (!estoqueEncontrado) {
                            msg = 'Não há estoque disponível para este material. A quantidade foi ajustada para 0.';
                        } else {
                            msg = `A quantidade informada excede o estoque disponível (${estoqueDisponivel}). 
                                O valor foi ajustado para o máximo permitido com base no estoque ${tipoEstoque}.`;
                        }

                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            text: msg,
                            confirmButtonText: 'Entendi'
                        });
                    }
                }
            });
        });
    });
</script>