<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">    
                <form method="POST" action="{{ route('despesas.update') }}">
                    @csrf
                    <x-text-input id="id" type="hidden" name="id" :value="old('id') ? old('id') : $data->id"/>
                    
                    <!-- Nome -->
                    <div>
                        <x-input-label for="name" :value="__('Nome')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ? old('name') : $data->name"  />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    
                    {{-- Tipo de pagamento --}}
                    <div>
                        <x-input-label for="tipo_pagamento" :value="__('Tipo de pagamento')" />
                        <x-select-input id="tipo_pagamento" class="block mt-1 w-full" name="tipo_pagamento" :value="old('tipo_pagamento')">
                            <option value="boleto" @if ($data->tipo_pagamento == 'boleto') @selected(true) @endif>Boleto</option>
                            <option value="crédito" @if ($data->tipo_pagamento == 'crédito') @selected(true) @endif>Crédito</option>
                            <option value="débito" @if ($data->tipo_pagamento == 'débito') @selected(true) @endif>Débito</option>
                            <option value="depósito" @if ($data->tipo_pagamento == 'depósito') @selected(true) @endif>Depósito</option>
                            <option value="dinheiro" @if ($data->tipo_pagamento == 'dinheiro') @selected(true) @endif>Dinheiro</option>
                            <option value="pix" @if ($data->tipo_pagamento == 'pix') @selected(true) @endif>Pix</option>
                            <option value="transferência" @if ($data->tipo_pagamento == 'transferência') @selected(true) @endif>Transferência</option>
                        </x-select-input>
                        <x-input-error :messages="$errors->get('tipo_pagamento')" class="mt-2" />
                    </div>
                    
                    <!-- Valor -->
                    <div>
                        <x-input-label for="valor" :value="__('Valor')" />
                        <x-text-input id="valor" class="block mt-1 w-full totalizador" type="text" name="valor" :value="old('valor') ? old('valor') : $data->valor" />
                        <x-input-error :messages="$errors->get('valor')" class="mt-2" />
                    </div>
                    
                    <!-- Parcelas -->
                    <div>
                        <x-input-label for="quantidade" :value="__('Parcela')" />
                        <x-text-input id="quantidade" class="block mt-1 w-full totalizador" type="text" name="quantidade" :value="old('quantidade') ? old('quantidade') : $data->parcela"   />
                        <x-input-error :messages="$errors->get('quantidade')" class="mt-2" />
                    </div>
        
                    <!-- Vencimento -->
                    <div>
                        <x-input-label for="data" :value="__('Vencimento')" />
                        <x-text-input id="data" class="block mt-1 w-full totalizador" type="text" name="data" :value="old('data') ? old('data') : $data->data"  />
                        <x-input-error :messages="$errors->get('data')" class="mt-2" />
                    </div>
                    
                    {{-- Controle --}}
                    <div>
                        <x-input-label for="controle" :value="__('Controle')" />
                        <x-select-input id="controle" class="block mt-1 w-full" name="controle" :value="old('controle')">
                            <option value="pendente" @if ($data->controle == 'pendente') @selected(true) @endif>Pendente</option>
                            <option value="pago" @if ($data->controle == 'pago') @selected(true) @endif>Pago</option>
                            <option value="cancelado" @if ($data->controle == 'cancelado') @selected(true) @endif>Cancelado</option>
                        </x-select-input>
                        <x-input-error :messages="$errors->get('controle')" class="mt-2" />
                    </div>
                    
                    {{-- Controle --}}
                    <div>
                        <x-input-label for="banco_id" :value="__('Banco')" />
                        <x-select-input id="banco_id" class="block mt-1 w-full" name="banco_id" :value="old('banco_id')">
                            <option></option>
                            @foreach ($bancos as $item)
                                <option value="{{$item->id}}" @if ($data->banco_id == $item->id) @selected(true) @endif>{{$item->name." - ".$item->agencia."-".$item->conta}}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('banco_id')" class="mt-2" />
                    </div>
            
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Salvar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        //pagamento_data
        let data = document.getElementById("data");
        data.addEventListener("input", function (e) {
            let data_value = e.target.value.replace(/\D/g, "");
            if (data_value.length > 2) data_value = data_value.substring(0,2) + "/" + data_value.substring(2);
            if (data_value.length > 5) data_value = data_value.substring(0,5) + "/" + data_value.substring(5,9);
            e.target.value = data_value;
        });

        let data_oldValue = "{{ $data->data }}";
        if (data_oldValue) {
            let data_date = new Date(data_oldValue);
            if (!isNaN(data_date)) {
                data.value = data_date.toLocaleDateString("pt-BR");
            }
        }
    });

    $(document).ready(function () {

    });
</script>