<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">    
                <form method="POST" action="{{ route('materiais.update') }}">
                    @csrf
                    <x-text-input id="id" type="hidden" name="id" :value="old('id') ? old('id') : $data->id"/>
                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Nome')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ? old('name') : $data->name" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
            
                    <!-- Unidade de medida -->
                    <div>
                        <x-input-label for="unidade_de_medida" :value="__('Unidade de medida')" />
                        <x-text-input id="unidade_de_medida" class="block mt-1 w-full" type="text" name="unidade_de_medida" :value="old('unidade_de_medida') ? old('unidade_de_medida') : $data->unidade_de_medida" required autofocus />
                        <x-input-error :messages="$errors->get('unidade_de_medida')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="grupo_de_material_id" :value="__('Grupo de material')" />
                        <x-select-input id="grupo_de_material_id" class="block mt-1 w-full" name="grupo_de_material_id" :value="old('grupo_de_material_id') ? old('grupo_de_material_id') : $data->grupo_de_material_id">
                            <option></option>
                            @foreach ($grupos_de_material as $item)
                                <option value="{{$item->id}}" @if ($item->id == $data->grupo_de_material_id) @selected(true) @endif>{{$item->name}}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('grupo_de_material_id')" class="mt-2" />
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