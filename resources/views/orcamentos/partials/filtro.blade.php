<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Filtro') }}
        </h2>
    </header>
    
    <form id="filtroForm">
        @csrf
        <div class="flex gap-4">
            {{-- Empresas --}}
            <div>
                <x-input-label for="filtro_empresa_id" :value="__('Empresa')" />
                <x-select-input id="filtro_empresa_id" class="select2 block mt-1 w-full" name="filtro_empresa_id" :value="old('filtro_empresa_id')">
                    <option></option>
                    @foreach ($empresas as $item)
                        <option value="{{$item->name}}">{{$item->name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error :messages="$errors->get('filtro_empresa_id')" class="mt-2" />
            </div>
        
            <!-- Data de -->
            <div>
                <x-input-label for="filtro_data_de" :value="__('Data de')" />
                <x-text-input id="filtro_data_de" class="block mt-1 w-full" type="text" name="filtro_data_de" />
                <x-input-error :messages="$errors->get('filtro_data_de')" class="mt-2" />
            </div>

            <!-- Data até -->
            <div>
                <x-input-label for="filtro_data_ate" :value="__('Data até')" />
                <x-text-input id="filtro_data_ate" class="block mt-1 w-full" type="text" name="filtro_data_ate" />
                <x-input-error :messages="$errors->get('filtro_data_ate')" class="mt-2" />
            </div>

            <!-- Controle -->
            <div>
                <x-input-label for="filtro_controle" :value="__('Controle')" />
                <x-select-input id="filtro_controle" class="block mt-1 w-full" name="filtro_controle" :value="old('filtro_controle')">
                    <option></option>
                    <option value="pendente">Pendente</option>
                    <option value="iniciado">Iniciado</option>
                    <option value="finalizado">Finalizado</option>
                    <option value="cancelado">Cancelado</option>
                </x-select-input>
                <x-input-error :messages="$errors->get('filtro_controle')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button type="button" class="ms-4" id="limpar_filtro">{{ __('Limpar') }}</x-primary-button>
            <x-primary-button class="ms-4">{{ __('Filtrar') }}</x-primary-button>
        </div>
    </form>
</section>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        //filtro_data_de
        let filtro_data_de = document.getElementById("filtro_data_de");
        filtro_data_de.addEventListener("input", function (e) {
            let filtro_data_de_value = e.target.value.replace(/\D/g, "");
            if (filtro_data_de_value.length > 2) filtro_data_de_value = filtro_data_de_value.substring(0,2) + "/" + filtro_data_de_value.substring(2);
            if (filtro_data_de_value.length > 5) filtro_data_de_value = filtro_data_de_value.substring(0,5) + "/" + filtro_data_de_value.substring(5,9);
            e.target.value = filtro_data_de_value;
        });

        //filtro_data_ate
        let filtro_data_ate = document.getElementById("filtro_data_ate");
        filtro_data_ate.addEventListener("input", function (e) {
            let filtro_data_ate_value = e.target.value.replace(/\D/g, "");
            if (filtro_data_ate_value.length > 2) filtro_data_ate_value = filtro_data_ate_value.substring(0,2) + "/" + filtro_data_ate_value.substring(2);
            if (filtro_data_ate_value.length > 5) filtro_data_ate_value = filtro_data_ate_value.substring(0,5) + "/" + filtro_data_ate_value.substring(5,9);
            e.target.value = filtro_data_ate_value;
        });
    });
</script>