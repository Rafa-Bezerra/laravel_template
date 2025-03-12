<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Itens') }}
        </h2>
    </header>

    @if ($itens)
        <form id="itensForm">
            @csrf
            <x-text-input id="item_id" type="hidden" name="item_id"/>
            <x-text-input id="item_compra_id" type="hidden" name="item_compra_id" :value="$data->id"/>             

            <!-- DT. Item -->
            <div>
                <x-input-label for="item_data" :value="__('Data')" />
                <x-text-input id="item_data" class="block mt-1 w-full" type="text" name="item_data" :value="old('item_data') ? old('item_data') : date('d/m/Y')"  />
                <x-input-error :messages="$errors->get('item_data')" class="mt-2" />
            </div>   
            
            {{-- Material --}}
            <div>
                <x-input-label for="item_material_id" :value="__('Material')" />
                <x-select-input id="item_material_id" class="block mt-1 w-full" name="item_material_id" :value="old('item_material_id')">
                    <option></option>
                    @foreach ($materiais as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error :messages="$errors->get('item_material_id')" class="mt-2" />
            </div>

            <!-- Quantidade -->
            <div>
                <x-input-label for="item_quantidade" :value="__('Quantidade')" />
                <x-text-input id="item_quantidade" class="block mt-1 w-full totalizador" type="text" name="item_quantidade" :value="old('fone')"  />
                <x-input-error :messages="$errors->get('item_quantidade')" class="mt-2" />
            </div>

            <!-- PR. Unitário -->
            <div>
                <x-input-label for="item_preco_unitario" :value="__('PR. Unitário')" />
                <x-text-input id="item_preco_unitario" class="block mt-1 w-full totalizador" type="text" name="item_preco_unitario" :value="old('item_preco_unitario')" />
                <x-input-error :messages="$errors->get('item_preco_unitario')" class="mt-2" />
            </div>

            <!-- VL. Desconto -->
            <div>
                <x-input-label for="item_valor_desconto" :value="__('VL. Desconto')" />
                <x-text-input id="item_valor_desconto" class="block mt-1 w-full totalizador" type="text" name="item_valor_desconto" :value="old('item_valor_desconto')" />
                <x-input-error :messages="$errors->get('item_valor_desconto')" class="mt-2" />
            </div>

            <!-- VL. Total -->
            <div>
                <x-input-label for="item_valor_total" :value="__('VL. Total')" />
                <x-text-input id="item_valor_total" class="block mt-1 w-full totalizador" type="text" name="item_valor_total" :value="old('item_valor_total')" />
                <x-input-error :messages="$errors->get('item_valor_total')" class="mt-2" />
            </div>

            <!-- Observação -->
            <div>
                <x-input-label for="item_observacao" :value="__('Observação')" />
                <x-text-input id="item_observacao" class="block mt-1 w-full" type="text" name="item_observacao" :value="old('item_observacao')" />
                <x-input-error :messages="$errors->get('item_observacao')" class="mt-2" />
            </div>
            @if ($itens)
                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="ms-4">
                        {{ __('Salvar') }}
                    </x-primary-button>
                </div>
            @endif
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
    function formatarMoeda(valor) {
        if (!valor) return "R$ 0,00"; // Caso o valor seja null ou undefined
        return parseFloat(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    function formatarNumero(valor) {
        if (!valor) return "0"; // Evita exibição de "NaN"
    return parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    }
    
    function formatarData(dataHora) {
        if (!dataHora) return ""; // Se for null ou undefined, retorna string vazia
        let partes = dataHora.split(" "); // Divide a data e hora
        let data = partes[0]; // Pega apenas a parte da data (YYYY-MM-DD)
        let partesData = data.split("-");
        return partesData[2] + "/" + partesData[1] + "/" + partesData[0]; // Converte para DD/MM/YYYY
    }

    function editarItem(id) {
        $.ajax({
            url: "/compras/itens/get/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                $('#item_id').val(response.id);
                $('#item_data').val(formatarData(response.data));
                $('#item_material_id').val(response.material_id);
                $('#item_quantidade').val(response.quantidade);
                $('#item_preco_unitario').val(response.preco_unitario);
                $('#item_valor_desconto').val(response.valor_desconto);
                $('#item_valor_total').val(response.valor_total);
                // $('#item_quantidade, #item_preco_unitario, #item_valor_desconto, #item_valor_total').mask('#.##0,00', {reverse: true}).trigger('input');
            }
        });
    }

    function excluirItem(id) {
        $.ajax({
            url: "/compras/itens/delete/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            complete: function (response) {
                $('#minhaTabelaItens').DataTable().ajax.reload();
                $('#itensForm')[0].reset();           
                
                $('#valor_itens').val(response.responseJSON.valor_itens);
                $('#valor_desconto').val(response.responseJSON.valor_desconto);
                $('#valor_total').val(response.responseJSON.valor_total);
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
        // $('#item_quantidade, #item_preco_unitario, #item_valor_desconto, #item_valor_total').mask('#.##0,00', {reverse: true});
        
        $('#minhaTabelaItens').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('compras_itens.json') }}",
                "type": "GET",
                "data": function (d) {
                    d.compra_id = {{ $data->id ?? 'null' }}; 
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
                        let itens = @json($itens);          
                        let actions = '';
                        if (itens) {
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

        $('#itensForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('compras_itens.submit') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                complete: function (response) {
                    $('#minhaTabelaItens').DataTable().ajax.reload();
                    $('#itensForm')[0].reset();
                                        
                    $('#valor_itens').val(response.responseJSON.valor_itens);
                    $('#valor_desconto').val(response.responseJSON.valor_desconto);
                    $('#valor_total').val(response.responseJSON.valor_total);
                }
            });
        });


        $('.totalizador').on('input', function() {
            // let quantidade = parseFloat($('#item_quantidade').val()) || 0;
            // let preco = parseFloat($('#item_preco_unitario').val().replace('.', '').replace(',', '.')) || 0;
            // let desconto = parseFloat($('#item_valor_desconto').val().replace('.', '').replace(',', '.')) || 0;

            let quantidade = $('#item_quantidade').val();
            let preco = $('#item_preco_unitario').val();
            let desconto = $('#item_valor_desconto').val();

            let total = (quantidade * preco) - desconto;

            // $('#item_valor_total').val(total.toLocaleString('pt-BR', {minimumFractionDigits: 2}));

            $('#item_valor_total').val(total);
        });
    });
</script>