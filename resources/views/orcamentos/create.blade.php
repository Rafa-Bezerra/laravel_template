<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">    
                <form method="POST" action="{{ route('orcamentos.insert') }}">
                    @csrf
                    
                    <!-- Empresa -->
                    <div>
                        <x-input-label for="empresa_id" :value="__('Empresa')" />
                        <x-select-input id="empresa_id" class="select2 block mt-1 w-full" name="empresa_id" :value="old('empresa_id')">
                            <option></option>
                            @foreach ($empresas as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('empresa_id')" class="mt-2" />
                    </div>

                    <!-- Endereço -->
                    <div>
                        <x-input-label for="empresas_endereco_id" :value="__('Endereço')" />
                        <x-select-input id="empresas_endereco_id" class="block mt-1 w-full" name="empresas_endereco_id" :value="old('empresas_endereco_id')">
                            <option></option>
                        </x-select-input>
                        <x-input-error :messages="$errors->get('empresas_endereco_id')" class="mt-2" />
                    </div>

                    <!-- Observação -->
                    <div>
                        <x-input-label for="observacao" :value="__('Observação')" />
                        <x-text-input id="observacao" class="block mt-1 w-full" type="text" name="observacao" :value="old('observacao')" />
                        <x-input-error :messages="$errors->get('observacao')" class="mt-2" />
                    </div>

                    <!-- DT. Venda -->
                    <div>
                        <x-input-label for="data_venda" :value="__('DT. Venda')" />
                        <x-text-input id="data_venda" class="block mt-1 w-full" type="text" name="data_venda" :value="old('data_venda') ? old('data_venda') : date('d/m/Y')"  />
                        <x-input-error :messages="$errors->get('data_venda')" class="mt-2" />
                    </div>

                    <!-- DT. Prazo -->
                    <div>
                        <x-input-label for="data_prazo" :value="__('DT. Prazo')" />
                        <x-text-input id="data_prazo" class="block mt-1 w-full" type="text" name="data_prazo" :value="old('data_prazo') ? old('data_prazo') : date('d/m/Y', strtotime('+7 days'))"  />
                        <x-input-error :messages="$errors->get('data_prazo')" class="mt-2" />
                    </div>

                    <!-- Orçamento -->
                    <div>
                        <x-input-label for="valor_orcamento" :value="__('Orçamento')" />
                        <x-money-input id="valor_orcamento" class="block mt-1 w-full" type="text" name="valor_orcamento" :value="old('valor_orcamento')" />
                        <x-input-error :messages="$errors->get('valor_orcamento')" class="mt-2" />
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
        //data_venda
        let data_venda = document.getElementById("data_venda");
        data_venda.addEventListener("input", function (e) {
            let data_venda_value = e.target.value.replace(/\D/g, "");
            if (data_venda_value.length > 2) data_venda_value = data_venda_value.substring(0,2) + "/" + data_venda_value.substring(2);
            if (data_venda_value.length > 5) data_venda_value = data_venda_value.substring(0,5) + "/" + data_venda_value.substring(5,9);
            e.target.value = data_venda_value;
        });
        let data_venda_oldValue = "{{ old('data_venda') }}";
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
        let data_prazo_oldValue = "{{ old('data_prazo') }}";
        if (data_prazo_oldValue) {
            let data_prazo_date = new Date(data_prazo_oldValue);
            if (!isNaN(data_prazo_date)) {
                data_prazo.value = data_prazo_date.toLocaleDateString("pt-BR");
            }
        }
    });

    $(document).ready(function () {
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