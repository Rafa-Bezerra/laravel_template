<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Novas ações') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">    
                <form method="POST" action="{{ route('roles.update_actions') }}">
                    @csrf

                    <x-text-input id="role_id" type="hidden" name="role_id" :value="$role_id"/>

                    @foreach ($actions as $item)
                        <div class="flex items-center mt-4">
                            <input id="action_{{ $item->id }}" type="checkbox" name="{{ $item->id }}" @checked($item->checked)/>
                            <x-input-label for="action_{{ $item->id }}" :value="__($item->name)" />
                            <x-input-error :messages="$errors->get($item->id)" class="mt-2" />
                        </div>
                    @endforeach
            
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