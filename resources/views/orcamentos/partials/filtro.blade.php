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
                <x-select-input id="filtro_empresa_id" class="block mt-1 w-full" name="filtro_empresa_id" :value="old('filtro_empresa_id')">
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
            <x-primary-button class="ms-4">
                {{ __('Filtrar') }}
            </x-primary-button>
        </div>
    </form>
</section>