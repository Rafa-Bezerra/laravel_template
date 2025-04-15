<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Filtro') }}
        </h2>
    </header>
    
    <form id="filtroForm">
        @csrf
        <!-- Nome da obra -->
        <div>
            <x-input-label for="filtro_observacao" :value="__('Nome da obra')" />
            <x-text-input id="filtro_observacao" class="block mt-1 w-full" type="text" name="filtro_observacao" />
            <x-input-error :messages="$errors->get('filtro_observacao')" class="mt-2" />
        </div>

        <div class="flex gap-4">
            <!-- Compra de -->
            <div>
                <x-input-label for="filtro_data_de" :value="__('Compra de')" />
                <x-text-input id="filtro_data_de" class="block mt-1 w-full" type="text" name="filtro_data_de" />
                <x-input-error :messages="$errors->get('filtro_data_de')" class="mt-2" />
            </div>

            <!-- até -->
            <div>
                <x-input-label for="filtro_data_ate" :value="__('Compra até')" />
                <x-text-input id="filtro_data_ate" class="block mt-1 w-full" type="text" name="filtro_data_ate" />
                <x-input-error :messages="$errors->get('filtro_data_ate')" class="mt-2" />
            </div>
        </div>

        <div class="flex gap-4">
            <!-- Prazo de -->
            <div>
                <x-input-label for="filtro_data_prazo_de" :value="__('Prazo de')" />
                <x-text-input id="filtro_data_prazo_de" class="block mt-1 w-full" type="text" name="filtro_data_prazo_de" />
                <x-input-error :messages="$errors->get('filtro_data_prazo_de')" class="mt-2" />
            </div>

            <!-- até -->
            <div>
                <x-input-label for="filtro_data_prazo_ate" :value="__('Prazo até')" />
                <x-text-input id="filtro_data_prazo_ate" class="block mt-1 w-full" type="text" name="filtro_data_prazo_ate" />
                <x-input-error :messages="$errors->get('filtro_data_prazo_ate')" class="mt-2" />
            </div>
        </div>

        <div class="flex gap-4">
            <!-- Entrega de -->
            <div>
                <x-input-label for="filtro_data_entrega_de" :value="__('Entrega de')" />
                <x-text-input id="filtro_data_entrega_de" class="block mt-1 w-full" type="text" name="filtro_data_entrega_de" />
                <x-input-error :messages="$errors->get('filtro_data_entrega_de')" class="mt-2" />
            </div>

            <!-- até -->
            <div>
                <x-input-label for="filtro_data_entrega_ate" :value="__('Entrega até')" />
                <x-text-input id="filtro_data_entrega_ate" class="block mt-1 w-full" type="text" name="filtro_data_entrega_ate" />
                <x-input-error :messages="$errors->get('filtro_data_entrega_ate')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button type="button" class="ms-4" id="limpar_filtro">{{ __('Limpar') }}</x-primary-button>
            <x-primary-button class="ms-4">{{ __('Filtrar') }}</x-primary-button>
        </div>
    </form>
</section>