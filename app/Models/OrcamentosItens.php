<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Orcamentos;
use App\Models\Materiais;

class OrcamentosItens extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $table = 'orcamentos_itens';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'orcamento_id',
        'data',
        'material_id',
        'quantidade',
        'preco_unitario',
        'valor_desconto',
        'valor_total',
        'observacao',
    ];

    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamentos::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Materiais::class);
    }
}
