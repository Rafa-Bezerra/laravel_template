<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar usuário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">    
                <form method="POST" action="{{ route('usuarios.update') }}">
                    @csrf
                    <x-text-input id="id" type="hidden" name="id" :value="old('id') ? old('id') : $data->id"/>
                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Nome')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ? old('name') : $data->name" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    
                    <!-- Action -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email') ? old('email') : $data->email" required autofocus autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
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