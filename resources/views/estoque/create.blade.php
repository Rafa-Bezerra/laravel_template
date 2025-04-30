<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">    
                <form method="POST" action="{{ route('estoque.insert') }}">
                    @csrf 

                    {{-- Material --}}
                    <div>
                        <x-input-label for="material_id" :value="__('Material')" />
                        <x-select-input id="material_id" class="block mt-1 w-full" name="material_id" :value="old('material_id')">
                            <option></option>
                            @foreach ($materiais as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('material_id')" class="mt-2" />
                    </div>
                    
                    <!-- Quantidade -->
                    <div>
                        <x-input-label for="quantidade" :value="__('Quantidade')" />
                        <x-text-input id="quantidade" class="block mt-1 w-full" type="text" name="quantidade" :value="old('quantidade')" />
                        <x-input-error :messages="$errors->get('quantidade')" class="mt-2" />
                    </div>
                    
                    <!-- Valor -->
                    <div>
                        <x-input-label for="valor" :value="__('Valor')" />
                        <x-text-input id="valor" class="block mt-1 w-full" type="text" name="valor" :value="old('valor')" />
                        <x-input-error :messages="$errors->get('valor')" class="mt-2" />
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
    $(document).ready(function () {

    });
</script>