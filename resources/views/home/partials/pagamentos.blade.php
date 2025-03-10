<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Pagamentos') }}
        </h2>
    </header>

    <table id="minhaTabelaPagamentos" class="table table-striped datatable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Espécie</th>
                <th>Orçamento</th>
                <th>Compra</th>
                <th>Valor</th>
                <th>Parcela</th>
                <th>Vencimento</th>
                <th>Controle</th>
            </tr>
        </thead>
    </table>
</section>
<script>
    $(document).ready(function () {
        $('#minhaTabelaPagamentos').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('pagamentos.json') }}",
                "type": "GET",
            },
            "columns": [
                { "data": "id" },
                { "data": "especie" },
                { "data": "orcamento_id", 
                    "render": function (data, type, row) {
                        if(data != null) {
                            return `<a href="/orcamentos/edit/${data}" target="_blank">${data}</a>`;
                        } else {
                            return '';
                        }
                    } 
                },
                { "data": "compra_id", 
                    "render": function (data, type, row) {
                        if(data != null) {
                            return `<a href="/compras/edit/${data}" target="_blank">${data}</a>`;
                        } else {
                            return '';
                        }
                    } 
                },
                { "data": "valor" },
                { "data": "parcela" },
                { "data": "data" ,
                    "render": function (data, type, row) {
                        return data ? new Date(data).toLocaleDateString('pt-BR') : "";
                    } 
                },
                { "data": "controle"},
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });
    });
</script>