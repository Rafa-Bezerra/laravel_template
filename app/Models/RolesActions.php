<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Database\Factories\RolesActionsFactory;

class RolesActions extends Authenticatable
{
    use HasFactory;
    protected $table = "roles_actions";
    protected $fillable = ['role_id','action_id'];

    protected static function newFactory(): Factory
    {
        return RolesActionsFactory::new();
    }
}
