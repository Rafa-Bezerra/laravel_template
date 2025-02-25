<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Compras;
use App\Models\Materiais;

class ComprasItens extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that should be hidden for serialization.
    *
    * @var list<string>
    */
    protected $table = 'compras_itens';

    /**
     * The attributes that are mass assignable.
    *
    * @var list<string>
    */
    protected $fillable = [
        'compra_id',
        'data',
        'material_id',
        'quantidade',
        'preco_unitario',
        'valor_desconto',
        'valor_total',
        'observacao',
    ];

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compras::class);
    }

    public function material()
    {
        return $this->belongsTo(Materiais::class);
    }
}
