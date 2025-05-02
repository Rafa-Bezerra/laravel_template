<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Materiais;

class GruposDeMaterial extends BaseModel
{
    use HasFactory, Notifiable;
    protected $table = "grupos_de_material";
    protected $fillable = ['name'];

    public function materiais()
    {
        return $this->hasMany(Materiais::class, 'grupo_de_material_id');
    }
}
