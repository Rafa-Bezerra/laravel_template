<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">    
                <form id="compraForm">
                    @csrf
                    <x-text-input id="id" type="hidden" name="id" :value="old('id') ? old('id') : $data->id"/>
                        
                    <!-- Empresa -->
                    <div>
                        <x-input-label for="empresa_id" :value="__('Empresa')" />
                        <x-select-input id="empresa_id" class="select2 block mt-1 w-full" name="empresa_id" :value="old('empresa_id')">
                            <option></option>
                            @foreach ($empresas as $item)
                                <option value="{{$item->id}}" @if ($item->id == $data->empresa_id) @selected(true) @endif>{{$item->name}}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('empresa_id')" class="mt-2" />
                    </div>

                    <!-- Nome da obra -->
                    <div>
                        <x-input-label for="observacao" :value="__('Nome da obra')" />
                        <x-text-input id="observacao" class="block mt-1 w-full" type="text" name="observacao" :value="old('observacao') ? old('observacao') : $data->observacao" required autofocus autocomplete="observacao" />
                        <x-input-error :messages="$errors->get('observacao')" class="mt-2" />
                    </div>

                    <!-- DT. Compra -->
                    <div>
                        <x-input-label for="data_compra" :value="__('DT. Compra')" />
                        <x-text-input id="data_compra" class="block mt-1 w-full" type="text" name="data_compra" :value="old('data_compra') ? old('data_compra') : $data->data_compra"  />
                        <x-input-error :messages="$errors->get('data_compra')" class="mt-2" />
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

                    <!-- DT. Entrega -->
                    <div>
                        <x-input-label for="valor_itens" :value="__('VL. Itens')" />
                        <x-money-input id="valor_itens" class="moeda block mt-1 w-full" type="text" name="valor_itens" :value="old('valor_itens') ? old('valor_itens') : $data->valor_itens"  />
                        <x-input-error :messages="$errors->get('valor_itens')" class="mt-2" />
                    </div>
                    

                    <!-- DT. Entrega -->
                    <div>
                        <x-input-label for="valor_desconto" :value="__('VL. Desconto')" />
                        <x-money-input id="valor_desconto" class="moeda block mt-1 w-full" type="text" name="valor_desconto" :value="old('valor_desconto') ? old('valor_desconto') : $data->valor_desconto"  />
                        <x-input-error :messages="$errors->get('valor_desconto')" class="mt-2" />
                    </div>
                    

                    <!-- DT. Entrega -->
                    <div>
                        <x-input-label for="valor_total" :value="__('VL. Total')" />
                        <x-money-input id="valor_total" class="moeda block mt-1 w-full" type="text" name="valor_total" :value="old('valor_total') ? old('valor_total') : $data->valor_total"  />
                        <x-input-error :messages="$errors->get('valor_total')" class="mt-2" />
                    </div>
            
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Salvar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">@include('compras.partials.pagamentos')</div>
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">@include('compras.partials.itens')</div>
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        //data_compra
        let data_compra = document.getElementById("data_compra");
        data_compra.addEventListener("input", function (e) {
            let data_compra_value = e.target.value.replace(/\D/g, "");
            if (data_compra_value.length > 2) data_compra_value = data_compra_value.substring(0,2) + "/" + data_compra_value.substring(2);
            if (data_compra_value.length > 5) data_compra_value = data_compra_value.substring(0,5) + "/" + data_compra_value.substring(5,9);
            e.target.value = data_compra_value;
        });
        let data_compra_oldValue = "{{ $data->data_compra }}";
        if (data_compra_oldValue) {
            let data_compra_date = new Date(data_compra_oldValue);
            if (!isNaN(data_compra_date)) {
                data_compra.value = data_compra_date.toLocaleDateString("pt-BR");
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
        $('#compraForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('compras.update') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                complete: function (response) {                    
                    let data = response.responseJSON;

                    $('#valor_itens').val(formatMonetario(data.valor_itens));
                    $('#valor_desconto').val(formatMonetario(data.valor_desconto));
                    $('#valor_total').val(formatMonetario(data.valor_total));
                }
            });
        });
    });
</script>