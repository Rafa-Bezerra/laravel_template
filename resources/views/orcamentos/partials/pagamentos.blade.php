<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Recebimentos') }}
        </h2>
    </header>

    @if ($permissao_pagamentos)
        <form id="pagamentoForm">
            @csrf
            <x-text-input id="pagamento_id" type="hidden" name="pagamento_id"/>
            <x-text-input id="pagamento_orcamento_id" type="hidden" name="pagamento_orcamento_id" :value="$data->id"/>   
                    
            {{-- Tipo de pagamento --}}
            <div>
                <x-input-label for="pagamento_tipo_pagamento" :value="__('Tipo de pagamento')" />
                <x-select-input id="pagamento_tipo_pagamento" class="block mt-1 w-full" name="pagamento_tipo_pagamento" :value="old('pagamento_tipo_pagamento')">
                    <option value="boleto">Boleto</option>
                    <option value="crédito">Crédito</option>
                    <option value="débito">Débito</option>
                    <option value="depósito">Depósito</option>
                    <option value="dinheiro">Dinheiro</option>
                    <option value="pix">Pix</option>
                    <option value="transferência">Transferência</option>
                </x-select-input>
                <x-input-error :messages="$errors->get('pagamento_tipo_pagamento')" class="mt-2" />
            </div> 
            
            <!-- Valor -->
            <div>
                <x-input-label for="pagamento_valor" :value="__('Valor')" />
                <x-money-input id="pagamento_valor" class="block mt-1 w-full totalizador" type="text" name="pagamento_valor" :value="old('pagamento_valor')"  />
                <x-input-error :messages="$errors->get('pagamento_valor')" class="mt-2" />
            </div>
            
            <!-- Parcelas -->
            <div>
                <x-input-label for="pagamento_quantidade" :value="__('Parcelas')" />
                <x-text-input id="pagamento_quantidade" class="block mt-1 w-full totalizador" type="text" name="pagamento_quantidade" :value="old('pagamento_quantidade') ? old('pagamento_quantidade') : 1"   />
                <x-input-error :messages="$errors->get('pagamento_quantidade')" class="mt-2" />
            </div>

            <!-- Vencimento -->
            <div>
                <x-input-label for="pagamento_data" :value="__('Vencimento')" />
                <x-text-input id="pagamento_data" class="block mt-1 w-full totalizador" type="text" name="pagamento_data" :value="old('pagamento_data')"  />
                <x-input-error :messages="$errors->get('pagamento_data')" class="mt-2" />
            </div>
            
            {{-- Controle --}}
            <div>
                <x-input-label for="pagamento_controle" :value="__('Controle')" />
                <x-select-input id="pagamento_controle" class="block mt-1 w-full" name="pagamento_controle" :value="old('pagamento_controle')">
                    <option value="pendente">Pendente</option>
                    <option value="pago">Pago</option>
                    <option value="cancelado">Cancelado</option>
                </x-select-input>
                <x-input-error :messages="$errors->get('pagamento_controle')" class="mt-2" />
            </div>
            
            {{-- Controle --}}
            <div>
                <x-input-label for="pagamento_banco_id" :value="__('Banco')" />
                <x-select-input id="pagamento_banco_id" class="select2 block mt-1 w-full" name="pagamento_banco_id" :value="old('pagamento_banco_id')">
                    <option></option>
                    @foreach ($bancos as $item)
                        <option value="{{$item->id}}">{{$item->name." - ".$item->agencia."-".$item->conta}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error :messages="$errors->get('pagamento_banco_id')" class="mt-2" />
            </div>


            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-4">
                    {{ __('Salvar') }}
                </x-primary-button>
            </div>
        </form>
    @endif

    <table id="minhaTabelaPagamentos" class="table table-striped datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo PG</th>
                <th>Valor</th>
                <th>Parcela</th>
                <th>Vencimento</th>
                <th>Banco</th>
                <th>Controle</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</section>
<script>
    function editarPagamento(id) {
        $.ajax({
            url: "/orcamentos/pagamentos/get/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                $('#pagamento_id').val(response.id);
                $('#pagamento_valor').val(formatMonetario(response.valor));
                $('#pagamento_quantidade').val(1);
                $('#pagamento_data').val(formatarData(response.data));
                $('#pagamento_controle').val(response.controle);
                $('#pagamento_banco_id').val(response.banco_id).trigger('change');
                $('#pagamento_tipo_pagamento').val(response.tipo_pagamento);
            }
        });
    }

    function excluirPagamento(id) {
        $.ajax({
            url: "/orcamentos/pagamentos/delete/"+id,
            type: "GET",
            data: $(this).serialize(),
            dataType: "json",
            complete: function (response) {
                $('.datatable').DataTable().ajax.reload();
                $('#pagamentoForm')[0].reset();
                
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
        //pagamento_data
        let pagamento_data = document.getElementById("pagamento_data");
        pagamento_data.addEventListener("input", function (e) {
            let pagamento_data_value = e.target.value.replace(/\D/g, "");
            if (pagamento_data_value.length > 2) pagamento_data_value = pagamento_data_value.substring(0,2) + "/" + pagamento_data_value.substring(2);
            if (pagamento_data_value.length > 5) pagamento_data_value = pagamento_data_value.substring(0,5) + "/" + pagamento_data_value.substring(5,9);
            e.target.value = pagamento_data_value;
        });
    });
    
    $(document).ready(function () {
        $('#minhaTabelaPagamentos').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('orcamentos_pagamentos.json') }}",
                "type": "GET",
                "data": function (d) {
                    d.orcamento_id = {{ $data->id ?? 'null' }}; 
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "tipo_pagamento" },
                { "data": "valor",
                    "render": function (data) {
                        return formatarMoeda(data);
                    } 
                },
                { "data": "parcela" },
                { "data": "data" ,
                    "render": function (data, type, row) {
                        return data ? new Date(data).toLocaleDateString('pt-BR') : "";
                    } 
                },
                { "data": "banco_name"},
                { "data": "controle"},
                { 
                    "data": "id", 
                    "render": function (data, type, row) {    
                        let permissao_pagamentos = @json($permissao_pagamentos);   
                        let actions = '';
                        if (permissao_pagamentos) {
                            actions += `<a onclick="editarPagamento(${data})">Editar</a> <a onclick="excluirPagamento(${data})">Excluir</a>`;
                        }
                        return actions.trim();
                    }
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });

        $('#pagamentoForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('orcamentos_pagamentos.submit') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                complete: function (response) {
                    $('.datatable').DataTable().ajax.reload();
                    $('#pagamentoForm')[0].reset();
                    $('#pagamento_banco_id').val('').trigger('change');
                    $('#pagamento_id').val('');
                
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