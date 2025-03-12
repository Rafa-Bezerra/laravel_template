<?php

namespace App\Http\Controllers;
use App\Models\User;

abstract class Controller
{
    function hasPermission(String $permission, String $tittle = null, $view = false) {
        $perm = User::hasPermission($permission);
        if ($view) {
            if (! $perm) return view('forbbiden', ['tittle' => $tittle]);
        } else {
            return $perm;
        }
    }
}
