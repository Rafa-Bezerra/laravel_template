<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Pagamentos') }}
        </h2>
    </header>

    <form id="pagamentoForm">
        @csrf
        <x-text-input id="pagamento_id" type="hidden" name="pagamento_id"/>
        <x-text-input id="pagamento_orcamento_id" type="hidden" name="pagamento_orcamento_id" :value="$data->id"/>   
        
        <!-- Valor -->
        <div>
            <x-input-label for="pagamento_valor" :value="__('Valor')" />
            <x-text-input id="pagamento_valor" class="block mt-1 w-full totalizador" type="text" name="pagamento_valor" :value="old('pagamento_valor')"  />
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


        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Salvar') }}
            </x-primary-button>
        </div>
    </form>

    <table id="minhaTabelaPagamentos" class="table table-striped datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Valor</th>
                <th>Parcela</th>
                <th>Vencimento</th>
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
                $('#pagamento_valor').val(response.valor);
                $('#pagamento_quantidade').val(1);
                $('#pagamento_data').val(formatarData(response.data));
                $('#pagamento_controle').val(response.controle);
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
                
                $('#valor_itens').val(response.responseJSON.valor_itens);
                $('#valor_desconto').val(response.responseJSON.valor_desconto);
                $('#valor_total').val(response.responseJSON.valor_total);
                $('#valor_servicos').val(response.responseJSON.valor_servicos);
                $('#valor_saldo').val(response.responseJSON.valor_saldo);
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
                { "data": "valor" },
                { "data": "parcela" },
                { "data": "data" ,
                    "render": function (data, type, row) {
                        return data ? new Date(data).toLocaleDateString('pt-BR') : "";
                    } 
                },
                { "data": "controle"},
                { 
                    "data": "id", 
                    "render": function (data, type, row) {
                        return `<a onclick="editarPagamento(${data})">Editar</a> <a onclick="excluirPagamento(${data})">Excluir</a>`;
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
                    $('#pagamento_id').val('');

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