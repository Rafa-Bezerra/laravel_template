<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Orcamentos;
use App\Models\Empresas;

class Compras extends BaseModel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $table = 'compras';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'orcamento_id',
        'data_compra',
        'data_prazo',
        'data_entrega',
        'valor_itens',
        'valor_desconto',
        'valor_total',
        'observacao',
        'empresa_id',
    ];

    public function orcamento()
    {
        return $this->belongsTo(Orcamentos::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresas::class);
    }
}
