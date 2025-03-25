<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class GruposDeMaterial extends BaseModel
{
    use HasFactory, Notifiable;
    protected $table = "grupos_de_material";
    protected $fillable = ['name'];
}
