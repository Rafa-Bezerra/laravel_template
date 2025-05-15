<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
        
        <!-- SweetAlert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>            
        </div>
    </body>
</html>
<script>
    function parseToFloatBr(value) {
        if (value == null || value === '') return 0;

        // Converte para string antes de aplicar replace
        value = String(value).replace(/[^0-9.,-]/g, '');

        if (value.includes(',') && value.includes('.')) {
            value = value.replace(/\./g, '').replace(',', '.');
        } else if (value.includes(',')) {
            value = value.replace(',', '.');
        }

        return parseFloat(value) || 0;
    }

    function formatNumerico(value) {
        const num = parseToFloatBr(value);
        return num.toFixed(2)
            .replace('.', ',')
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function formatMonetario(value) {
        const num = parseToFloatBr(value);
        return 'R$ ' + num.toFixed(2)
            .replace('.', ',')
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function formatPercentual(value) {
        const num = parseToFloatBr(value);
        return num.toFixed(2)
            .replace('.', ',')
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' %';
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input.monetario').forEach(input => {
            // Formata ao carregar a página
            input.value = formatMonetario(input.value);

            input.addEventListener('blur', () => {
                input.value = formatMonetario(input.value);
            });
        });
        
        document.querySelectorAll('input.quantidade').forEach(input => {
            // Formata ao carregar a página
            input.value = formatNumerico(input.value);

            input.addEventListener('blur', () => {
                input.value = formatNumerico(input.value);
            });
        });

        document.querySelectorAll('input.percentual').forEach(input => {
            // Formata ao carregar a página
            input.value = formatPercentual(input.value);

            input.addEventListener('blur', () => {
                input.value = formatPercentual(input.value);
            });
        });
    });

    $(document).ready(function() {
        $("input[type='text'], input[type='email'], input[type='number'], input[type='search']").focus(function() {
            $(this).select();
        });
        
        $('.select2').select2({
            placeholder: 'Selecione uma opção',
            allowClear: true,
            width: 'resolve'
        });
       
    });
</script>