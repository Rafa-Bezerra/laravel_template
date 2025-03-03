<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($tittle) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-x-auto">    
                <table class="table table-striped datatable w-full">
                    <thead>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Endereço</th>
                        <th class="px-4 py-2">DT. Venda</th>
                        <th class="px-4 py-2">DT. Prazo</th>
                        <th class="px-4 py-2">DT. Entrega</th>
                        <th class="px-4 py-2">VL. Itens</th>
                        <th class="px-4 py-2">VL. Desconto</th>
                        <th class="px-4 py-2">VL. Total</th>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            {{-- <section>
                                <header><h2 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ __($item->name) }}</h2></header>
                            </section> --}}
                            <tr>
                                <td colspan=8>{{ __($item->name) }}</td>
                            </tr>
                            @foreach ($item->orcamentos as $orcamento)
                                <tr>
                                    <td class="px-4 py-2">{{ $orcamento->id }}</td>
                                    <td class="px-4 py-2">{{ $orcamento->endereco->rua.' - '.$orcamento->endereco->numero }}</td>
                                    <td class="px-4 py-2">{{ formatar_data($orcamento->data_venda) }}</td>
                                    <td class="px-4 py-2">{{ formatar_data($orcamento->data_prazo) }}</td>
                                    <td class="px-4 py-2">{{ formatar_data($orcamento->data_entrega) }}</td>
                                    <td class="px-4 py-2">{{ 'R$ '.number_format($orcamento->valor_itens, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2">{{ 'R$ '.number_format($orcamento->valor_desconto, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2">{{ 'R$ '.number_format($orcamento->valor_total, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>                    
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    $(document).ready(function () {

    });
</script>