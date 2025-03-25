<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cadastros') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <section>
                        <header><h2 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ __('Empresas') }}</h2></header>
                    </section>
                    <x-nav-link :href="route('divisoes.index')">{{ __('Divisões') }}</x-nav-link>
                    <x-nav-link :href="route('bancos')">{{ __('Bancos') }}</x-nav-link>
                    <x-nav-link :href="route('empresas.index')">{{ __('Empresas') }}</x-nav-link>
                    <x-nav-link :href="route('despesas')">{{ __('Despesas') }}</x-nav-link>
                </div>
            </div>
            <br>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <section>
                        <header><h2 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ __('Materiais') }}</h2></header>
                    </section>
                    <x-nav-link :href="route('grupos_de_material.index')">{{ __('Grupos de Material') }}</x-nav-link>
                    <x-nav-link :href="route('materiais.index')">{{ __('Materiais') }}</x-nav-link>
                    <x-nav-link :href="route('estoque')">{{ __('Estoque') }}</x-nav-link>
                </div>
            </div>
            <br>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <section>
                        <header><h2 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ __('Usuários') }}</h2></header>
                    </section>
                    <x-nav-link :href="route('roles.index')">{{ __('Acessos') }}</x-nav-link>
                    <x-nav-link :href="route('actions.index')">{{ __('Ações') }}</x-nav-link>
                    <x-nav-link :href="route('usuarios.index')">{{ __('Usuários') }}</x-nav-link>
                </div>
            </div>
            <br>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <section>
                        <header><h2 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ __('Obras') }}</h2></header>
                    </section>
                    <x-nav-link :href="route('servicos')">{{ __('Serviços') }}</x-nav-link>
                    <x-nav-link :href="route('comissoes')">{{ __('Comissões') }}</x-nav-link>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
