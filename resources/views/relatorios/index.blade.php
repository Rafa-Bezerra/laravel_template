<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <section>
                        <header><h2 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ __('Obras') }}</h2></header>
                    </section>
                    <x-nav-link :href="route('orcamentos_por_cliente')">{{ __('Por cliente') }}</x-nav-link>
                    <x-nav-link :href="route('orcamentos_por_cliente')">{{ __('Comissões') }}</x-nav-link>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <section>
                        <header><h2 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ __('Compras') }}</h2></header>
                    </section>
                    <x-nav-link :href="route('compras_por_material')">{{ __('Por material') }}</x-nav-link>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <section>
                        <header><h2 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ __('Estoque') }}</h2></header>
                    </section>
                    <x-nav-link :href="route('movimentacoes_de_estoque')">{{ __('Movimentações') }}</x-nav-link>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    $(document).ready(function () {
        
    });
</script>