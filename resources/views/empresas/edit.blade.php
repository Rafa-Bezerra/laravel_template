<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">    
                <form id="empresaForm">
                    @csrf
                    <x-text-input id="id" type="hidden" name="id" :value="old('id') ? old('id') : $data->id"/>

                    <div>
                        <x-input-label for="divisao_id" :value="__('Divisão')" />
                        <x-select-input id="divisao_id" class="block mt-1 w-full" name="divisao_id" :value="old('divisao_id')">
                            <option></option>
                            @foreach ($divisoes as $item)
                                <option value="{{$item->id}}" @if ($item->id == $data->divisao_id) @selected(true) @endif>{{$item->name}}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('divisao_id')" class="mt-2" />
                    </div>

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Nome')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ? old('name') : $data->name" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
            
                    <!-- Fantasia -->
                    <div>
                        <x-input-label for="fantasia" :value="__('Fantasia')" />
                        <x-text-input id="fantasia" class="block mt-1 w-full" type="text" name="fantasia" :value="old('fantasia') ? old('fantasia') : $data->fantasia"  />
                        <x-input-error :messages="$errors->get('fantasia')" class="mt-2" />
                    </div>
            
                    <!-- Pessoa -->
                    <div>
                        <x-input-label for="pessoa" :value="__('Pessoa')" />
                        <x-select-input id="pessoa" class="block mt-1 w-full" name="pessoa" :value="old('pessoa') ? old('pessoa') : $data->pessoa">
                            <option></option>
                            <option value="cpf" @if ("cpf" == $data->pessoa) @selected(true) @endif>Física</option>
                            <option value="cnpj" @if ("cnpj" == $data->pessoa) @selected(true) @endif>Jurídica</option>
                        </x-select-input>
                        <x-input-error :messages="$errors->get('pessoa')" class="mt-2" />
                    </div>
            
                    <!-- CPF -->
                    <div>
                        <x-input-label for="cpf" :value="__('CPF')" />
                        <x-text-input id="cpf" class="block mt-1 w-full" type="text" name="cpf" :value="old('cpf') ? old('cpf') : $data->cpf" />
                        <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
                    </div>
            
                    <!-- CNPJ -->
                    <div>
                        <x-input-label for="cnpj" :value="__('CNPJ')" />
                        <x-text-input id="cnpj" class="block mt-1 w-full" type="text" name="cnpj" :value="old('cnpj') ? old('cnpj') : $data->cnpj"/>
                        <x-input-error :messages="$errors->get('cnpj')" class="mt-2" />
                    </div>
            
                    <!-- Observação -->
                    <div>
                        <x-input-label for="observacao" :value="__('Observação')" />
                        <x-text-input id="observacao" class="block mt-1 w-full" type="text" name="observacao" :value="old('observacao') ? old('observacao') : $data->observacao" />
                        <x-input-error :messages="$errors->get('observacao')" class="mt-2" />
                    </div>
            
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Salvar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">@include('empresas.partials.contatos')</div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">@include('empresas.partials.enderecos')</div>
        </div>
    </div>
</x-app-layout>
<script>
    $(document).ready(function () {
        $('#empresaForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('empresas.update') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                complete: function (response) {

                }
            });
        });
    });
</script>