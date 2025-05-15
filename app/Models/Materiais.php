<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\GruposDeMaterial;

class Materiais extends BaseModel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $table = 'materiais';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'unidade_de_medida',
        'grupo_de_material_id',
        'observacao',
    ];

    public function grupo_de_material()
    {
        return $this->belongsTo(GruposDeMaterial::class);
    }

    public function estoques()
    {
        return $this->hasMany(Estoque::class, 'material_id');
    }

    public function getDisponibilidadeGeral(): float
    {
        return $this->estoques->sum('quantidade');
    }
    
    public function getDisponibilidadeLocal(int $empresa_id): float
    {
        return $this->estoques->where('empresa_id', $empresa_id)->sum('quantidade');
    }
}
