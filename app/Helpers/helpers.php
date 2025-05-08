<?php

use Carbon\Carbon;

if (!function_exists('formatar_data_dmY')) {
    function formatar_data_dmY($data, $formato = 'd/m/Y')
    {
        return $data ? Carbon::parse($data)->format($formato) : null;
    }
}

if (!function_exists('formatar_data_Ymd')) {
    function formatar_data_Ymd($data, $formato = 'Y-m-d')
    {
        return $data ? Carbon::parse($data)->format($formato) : null;
    }
}

if (!function_exists('formatar_moeda')) {
    function formatar_moeda($data)
    {
        if (!$data) {
            return null;
        }
        $valor = floatval(str_replace(['.', ','], ['', '.'], $data));
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }
}

if (!function_exists('desformatarNumerico')) {
    function desformatarNumerico(string $valor): float
    {
        return (float) str_replace(',', '.', str_replace('.', '', trim($valor)));
    }
}

if (!function_exists('desformatarDinheiro')) {
    function desformatarDinheiro(string $valor): float
    {
        return (float) str_replace(',', '.', str_replace(['R$', '.'], '', trim($valor)));
    }
}

if (!function_exists('desformatarPercentual')) {
    function desformatarPercentual(string $valor): float
    {
        return (float) str_replace(',', '.', str_replace(['%', '.'], '', trim($valor)));
    }
}