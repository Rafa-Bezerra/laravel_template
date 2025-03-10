<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">    
                <form id="orcamentoForm">
                    @csrf
                    <x-text-input id="id" type="hidden" name="id" :value="old('id') ? old('id') : $data->id"/>
                    
                        <!-- Empresa -->
                        <div>
                            <x-input-label for="empresa_id" :value="__('Empresa')" />
                            <x-select-input id="empresa_id" class="block mt-1 w-full" name="empresa_id" :value="old('empresa_id')">
                                <option></option>
                                @foreach ($empresas as $item)
                                    <option value="{{$item->id}}" @if ($item->id == $data->empresa_id) @selected(true) @endif>{{$item->name}}</option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('empresa_id')" class="mt-2" />
                        </div>
    
                        <!-- Endereço -->
                        <div>
                            <x-input-label for="empresas_endereco_id" :value="__('Endereço')" />
                            <x-select-input id="empresas_endereco_id" class="block mt-1 w-full" name="empresas_endereco_id" :value="old('empresas_endereco_id')">
                                <option></option>
                                @foreach ($empresas_enderecos as $item)
                                    <option value="{{$item->id}}" @if ($item->id == $data->empresas_endereco_id) @selected(true) @endif>{{$item->rua . " - " . $item->numero}}</option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('empresas_endereco_id')" class="mt-2" />
                        </div>
    
                        <!-- Controle -->
                        <div>
                            <x-input-label for="controle" :value="__('Controle')" />
                            <x-select-input id="controle" class="block mt-1 w-full" name="controle" :value="old('controle')">
                                <option value="pendente" @if ($item->id == $data->controle) @selected(true) @endif>Pendente</option>
                                <option value="iniciado" @if ($item->id == $data->controle) @selected(true) @endif>Iniciado</option>
                                <option value="finalizado" @if ($item->id == $data->controle) @selected(true) @endif>Finalizado</option>
                                <option value="cancelado" @if ($item->id == $data->controle) @selected(true) @endif>Cancelado</option>
                            </x-select-input>
                            <x-input-error :messages="$errors->get('controle')" class="mt-2" />
                        </div>

                        <!-- Observação -->
                        <div>
                            <x-input-label for="observacao" :value="__('Observação')" />
                            <x-text-input id="observacao" class="block mt-1 w-full" type="text" name="observacao" :value="old('observacao') ? old('observacao') : $data->observacao" />
                            <x-input-error :messages="$errors->get('observacao')" class="mt-2" />
                        </div>
    
                        <!-- DT. Venda -->
                        <div>
                            <x-input-label for="data_venda" :value="__('DT. Venda')" />
                            <x-text-input id="data_venda" class="block mt-1 w-full" type="text" name="data_venda" :value="old('data_venda') ? old('data_venda') : $data->data_venda"  />
                            <x-input-error :messages="$errors->get('data_venda')" class="mt-2" />
                        </div>
    
                        <!-- DT. Prazo -->
                        <div>
                            <x-input-label for="data_prazo" :value="__('DT. Prazo')" />
                            <x-text-input id="data_prazo" class="block mt-1 w-full" type="text" name="data_prazo" :value="old('data_prazo') ? old('data_prazo') : $data->data_prazo"  />
                            <x-input-error :messages="$errors->get('data_prazo')" class="mt-2" />
                        </div>
    
                        <!-- DT. Entrega -->
                        <div>
                            <x-input-label for="data_entrega" :value="__('DT. Entrega')" />
                            <x-text-input id="data_entrega" class="block mt-1 w-full" type="text" name="data_entrega" :value="old('data_entrega') ? old('data_entrega') : $data->data_entrega"  />
                            <x-input-error :messages="$errors->get('data_entrega')" class="mt-2" />
                        </div>
                        
                        <!-- VL. Itens -->
                        <div>
                            <x-input-label for="valor_itens" :value="__('VL. Itens')" />
                            <x-text-input id="valor_itens" class="block mt-1 w-full" type="text" name="valor_itens" :value="old('valor_itens') ? old('valor_itens') : $data->valor_itens" />
                            <x-input-error :messages="$errors->get('valor_itens')" class="mt-2" />
                        </div>

                        <!-- VL. Serviços -->
                        <div>
                            <x-input-label for="valor_servicos" :value="__('VL. Serviços')" />
                            <x-text-input id="valor_servicos" class="block mt-1 w-full" type="text" name="valor_servicos" :value="old('valor_servicos') ? old('valor_servicos') : $data->valor_servicos" />
                            <x-input-error :messages="$errors->get('valor_servicos')" class="mt-2" />
                        </div>

                        <!-- VL. Desconto -->
                        <div>
                            <x-input-label for="valor_desconto" :value="__('VL. Desconto')" />
                            <x-text-input id="valor_desconto" class="block mt-1 w-full" type="text" name="valor_desconto" :value="old('valor_desconto') ? old('valor_desconto') : $data->valor_desconto" />
                            <x-input-error :messages="$errors->get('valor_desconto')" class="mt-2" />
                        </div>

                        <!-- VL. Impostos -->
                        <div>
                            <x-input-label for="valor_impostos" :value="__('VL. Impostos')" />
                            <x-text-input id="valor_impostos" class="block mt-1 w-full" type="text" name="valor_impostos" :value="old('valor_impostos') ? old('valor_impostos') : $data->valor_impostos" />
                            <x-input-error :messages="$errors->get('valor_impostos')" class="mt-2" />
                        </div>
                        
                        <!-- VL. Total -->
                        <div>
                            <x-input-label for="valor_total" :value="__('VL. Total')" />
                            <x-text-input id="valor_total" class="block mt-1 w-full" type="text" name="valor_total" :value="old('valor_total') ? old('valor_total') : $data->valor_total" />
                            <x-input-error :messages="$errors->get('valor_total')" class="mt-2" />
                        </div>

                        <!-- Orçamento -->
                        <div>
                            <x-input-label for="valor_orcamento" :value="__('Orçamento')" />
                            <x-text-input id="valor_orcamento" class="block mt-1 w-full" type="text" name="valor_orcamento" :value="old('valor_orcamento') ? old('valor_orcamento') : $data->valor_orcamento" />
                            <x-input-error :messages="$errors->get('valor_orcamento')" class="mt-2" />
                        </div>

                        <!-- Saldo -->
                        <div>
                            <x-input-label for="valor_saldo" :value="__('Saldo')" />
                            <x-text-input id="valor_saldo" class="block mt-1 w-full" type="text" name="valor_saldo" :value="old('valor_saldo') ? old('valor_saldo') : $data->valor_saldo" />
                            <x-input-error :messages="$errors->get('valor_saldo')" class="mt-2" />
                        </div>
            
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Salvar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">@include('orcamentos.partials.pagamentos')</div>
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">@include('orcamentos.partials.itens')</div>
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">@include('orcamentos.partials.servicos')</div>
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">@include('orcamentos.partials.comissoes')</div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">@include('orcamentos.partials.socios')</div>
        </div>
    </div>
</x-app-layout>
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
    document.addEventListener("DOMContentLoaded", function () {
        //data_venda
        let data_venda = document.getElementById("data_venda");
        data_venda.addEventListener("input", function (e) {
            let data_venda_value = e.target.value.replace(/\D/g, "");
            if (data_venda_value.length > 2) data_venda_value = data_venda_value.substring(0,2) + "/" + data_venda_value.substring(2);
            if (data_venda_value.length > 5) data_venda_value = data_venda_value.substring(0,5) + "/" + data_venda_value.substring(5,9);
            e.target.value = data_venda_value;
        });
        let data_venda_oldValue = "{{ $data->data_venda }}";
        if (data_venda_oldValue) {
            let data_venda_date = new Date(data_venda_oldValue);
            if (!isNaN(data_venda_date)) {
                data_venda.value = data_venda_date.toLocaleDateString("pt-BR");
            }
        }

        //data_prazo
        let data_prazo = document.getElementById("data_prazo");
        data_prazo.addEventListener("input", function (e) {
            let data_prazo_value = e.target.value.replace(/\D/g, "");
            if (data_prazo_value.length > 2) data_prazo_value = data_prazo_value.substring(0,2) + "/" + data_prazo_value.substring(2);
            if (data_prazo_value.length > 5) data_prazo_value = data_prazo_value.substring(0,5) + "/" + data_prazo_value.substring(5,9);
            e.target.value = data_prazo_value;
        });
        let data_prazo_oldValue = "{{ $data->data_prazo }}";
        if (data_prazo_oldValue) {
            let data_prazo_date = new Date(data_prazo_oldValue);
            if (!isNaN(data_prazo_date)) {
                data_prazo.value = data_prazo_date.toLocaleDateString("pt-BR");
            }
        }

        //data_entrega
        let data_entrega = document.getElementById("data_entrega");
        data_entrega.addEventListener("input", function (e) {
            let data_entrega_value = e.target.value.replace(/\D/g, "");
            if (data_entrega_value.length > 2) data_entrega_value = data_entrega_value.substring(0,2) + "/" + data_entrega_value.substring(2);
            if (data_entrega_value.length > 5) data_entrega_value = data_entrega_value.substring(0,5) + "/" + data_entrega_value.substring(5,9);
            e.target.value = data_entrega_value;
        });
        let data_entrega_oldValue = "{{ $data->data_entrega }}";
        if (data_entrega_oldValue) {
            let data_entrega_date = new Date(data_entrega_oldValue);
            if (!isNaN(data_entrega_date)) {
                data_entrega.value = data_entrega_date.toLocaleDateString("pt-BR");
            }
        }
    });

    $(document).ready(function () {        
        $('#orcamentoForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('orcamentos.update') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                complete: function (response) {
                    $('#valor_itens').val(response.responseJSON.valor_itens);
                    $('#valor_desconto').val(response.responseJSON.valor_desconto);
                    $('#valor_total').val(response.responseJSON.valor_total);
                    $('#valor_servicos').val(response.responseJSON.valor_servicos);
                    $('#valor_saldo').val(response.responseJSON.valor_saldo);
                }
            });
        });

        $('#empresa_id').on('change', function () {
            let empresaId = $(this).val();
            $.ajax({
                url: "{{ route('orcamentos.getEnderecos') }}",
                type: "POST",
                data: {
                    empresa_id : empresaId,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                complete: function (response) {
                    console.log(response.responseJSON.data);
                    let selectEndereco = $('#empresas_endereco_id');
                    selectEndereco.empty().append('<option></option>'); 
                    response.responseJSON.data.forEach(element => {
                        selectEndereco.append(`<option value="${element.id}">${element.rua + " - " + element.numero}</option>`);
                    });
                }
            });
        });
    });
</script>