<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Novo banco') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">    
                <form method="POST" action="{{ route('bancos.insert') }}">
                    @csrf
            
                    <!-- Nome -->
                    <div>
                        <x-input-label for="name" :value="__('Nome')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    
                    <!-- Conta -->
                    <div>
                        <x-input-label for="conta" :value="__('Conta')" />
                        <x-text-input id="conta" class="block mt-1 w-full" type="text" name="conta" :value="old('conta')" required />
                        <x-input-error :messages="$errors->get('conta')" class="mt-2" />
                    </div>
            
                    <!-- Agência -->
                    <div>
                        <x-input-label for="agencia" :value="__('Agência')" />
                        <x-text-input id="agencia" class="block mt-1 w-full" type="text" name="agencia" :value="old('agencia')" required />
                        <x-input-error :messages="$errors->get('agencia')" class="mt-2" />
                    </div>
                    
                    <!-- Tipo -->
                    <div>
                        <x-input-label for="tipo" :value="__('Tipo')" />
                        <x-text-input id="tipo" class="block mt-1 w-full" type="text" name="tipo" :value="old('tipo')" required />
                        <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
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