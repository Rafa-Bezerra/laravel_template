<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Gastos') }}
        </h2>
    </header>

    @if ($permissao_gastos)
        <form id="gastosForm">
            @csrf
            <x-text-input id="gasto_id" type="hidden" name="gasto_id"/>
            <x-text-input id="gasto_orcamento_id" type="hidden" name="gasto_orcamento_id" :value="$data->id"/> 
            
            {{-- Tipo de gasto --}}
            <div>
                <x-input-label for="gasto_especie" :value="__('Tipo de gasto')" />
                <x-select-input id="gasto_especie" class="block mt-1 w-full" name="gasto_especie" :value="old('gasto_especie')">
                    <option value="material">Material</option>
                    <option value="pessoas">Pessoal</option>
                </x-select-input>
                <x-input-error :messages="$errors->get('gasto_especie')" class="mt-2" />
            </div>  
                    
            {{-- Tipo de pagamento --}}
            <div>
                <x-input-label for="gasto_tipo_pagamento" :value="__('Tipo de pagamento')" />
                <x-select-input id="gasto_tipo_pagamento" class="block mt-1 w-full" name="gasto_tipo_pagamento" :value="old('gasto_tipo_pagamento')">
                    <option value="boleto">Boleto</option>
                    <option value="crédito">Crédito</option>
                    <option value="débito">Débito</option>
                    <option value="depósito">Depósito</option>
                    <option value="dinheiro">Dinheiro</option>
                    <option value="pix">Pix</option>
                    <option value="transferência">Transferência</option>
                </x-select-input>
                <x-input-error :messages="$errors->get('gasto_tipo_pagamento')" class="mt-2" />
            </div> 
            
            <!-- Valor -->
            <div>
                <x-input-label for="gasto_valor" :value="__('Valor')" />
                <x-money-input id="gasto_valor" class="block mt-1 w-full totalizador" type="text" name="gasto_valor" :value="old('gasto_valor')"  />
                <x-input-error :messages="$errors->get('gasto_valor')" class="mt-2" />
            </div>

            <!-- Data -->
            <div>
                <x-input-label for="gasto_data" :value="__('Data')" />
                <x-text-input id="gasto_data" class="block mt-1 w-full totalizador" type="text" name="gasto_data" :value="old('gasto_data') ? old('gasto_data') : date('d/m/Y')"  />
                <x-input-error :messages="$errors->get('gasto_data')" class="mt-2" />
            </div>
            
            {{-- Controle --}}
            <div>
                <x-input-label for="gasto_controle" :value="__('Controle')" />
                <x-select-input id="gasto_controle" class="block mt-1 w-full" name="gasto_controle" :value="old('gasto_controle')">
                    <option value="pendente">Pendente</option>
                    <option value="pago">Pago</option>
                </x-select-input>
                <x-input-error :messages="$errors->get('gasto_controle')" class="mt-2" />
            </div>
            
            {{-- Controle --}}
            <div>
                <x-input-label for="gasto_banco_id" :value="__('Banco')" />
                <x-select-input id="gasto_banco_id" class="select2 block mt-1 w-full" name="gasto_banco_id" :value="old('gasto_banco_id')">
                    <option></option>
                    @foreach ($bancos as $item)
                        <option value="{{$item->id}}">{{$item->name." - ".$item->agencia."-".$item->conta}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error :messages="$errors->get('gasto_banco_id')" class="mt-2" />
            </div>
            
            <!-- Observação -->
            <div>
                <x-input-label for="gasto_observacao" :value="__('Observação')" />
                <x-text-input id="gasto_observacao" class="block mt-1 w-full totalizador" type="text" name="gasto_observacao" :value="old('gasto_observacao')"  />
                <x-input-error :messages="$errors->get('gasto_observacao')" class="mt-2" />
            </div>


            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-4">
                    {{ __('Salvar') }}
                </x-primary-button>
            </div>
        </form>
    @endif

    <table id="minhaTabelaGastos" class="table table-striped datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Gasto</th>
                <th>Tipo PG</th>
                <th>Valor</th>
                <th>Data</th>
                <th>Controle</th>
                <th>Banco</th>
                <th>Obsevação</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</section>
<script>
    function editarGasto(id) {
        $.ajax({
            url: "/orcamentos/gastos/get/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                $('#gasto_id').val(response.id);
                $('#gasto_especie').val(response.especie);
                $('#gasto_tipo_pagamento').val(response.tipo_pagamento);
                $('#gasto_valor').val(formatMonetario(response.valor));
                $('#gasto_data').val(formatarData(response.data));
                $('#gasto_controle').val(response.controle);
                $('#gasto_banco_id').val(response.banco_id).trigger('change');
                $('#gasto_observacao').val(response.observacao);
            }
        });
    }

    function excluirGasto(id) {
        $.ajax({
            url: "/orcamentos/gastos/delete/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            complete: function (response) {
                $('.datatable').DataTable().ajax.reload();
                $('#gastosForm')[0].reset();        
                
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
        //gasto_data
        let gasto_data = document.getElementById("gasto_data");
        gasto_data.addEventListener("input", function (e) {
            let gasto_data_value = e.target.value.replace(/\D/g, "");
            if (gasto_data_value.length > 2) gasto_data_value = gasto_data_value.substring(0,2) + "/" + gasto_data_value.substring(2);
            if (gasto_data_value.length > 5) gasto_data_value = gasto_data_value.substring(0,5) + "/" + gasto_data_value.substring(5,9);
            e.target.value = gasto_data_value;
        });
    });
    
    $(document).ready(function () {
        $('#minhaTabelaGastos').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('orcamentos_gastos.json') }}",
                "type": "GET",
                "data": function (d) {
                    d.orcamento_id = {{ $data->id ?? 'null' }}; 
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "especie" },
                { "data": "tipo_pagamento" },
                { "data": "valor",
                    "render": function (data) {
                        return formatarMoeda(data);
                    } 
                },
                { "data": "data" ,
                    "render": function (data, type, row) {
                        return data ? new Date(data).toLocaleDateString('pt-BR') : "";
                    } 
                },
                { "data": "controle"},
                { "data": "banco_name"},
                { "data": "observacao"},
                { 
                    "data": "id", 
                    "render": function (data, type, row) {    
                        let permissao_gastos = @json($permissao_gastos);   
                        let actions = '';
                        if (permissao_gastos) {
                            actions += `<a onclick="editarGasto(${data})">Editar</a> <a onclick="excluirGasto(${data})">Excluir</a>`;
                        }
                        return actions.trim();
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });

        $('#gastosForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('orcamentos_gastos.submit') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                complete: function (response) {
                    $('.datatable').DataTable().ajax.reload();
                    $('#gastosForm')[0].reset();                    
                    $('#gasto_banco_id').val('').trigger('change');
                    $('#gasto_id').val('');
                
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