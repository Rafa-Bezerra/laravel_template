<?php

use Carbon\Carbon;

if (!function_exists('formatar_data')) {
    function formatar_data($data, $formato = 'd/m/Y')
    {
        return $data ? Carbon::parse($data)->format($formato) : null;
    }
}
