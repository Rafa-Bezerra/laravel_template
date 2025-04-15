<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Empresas;
use App\Models\EmpresasEnderecos;

class Orcamentos extends BaseModel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $table = 'orcamentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'empresa_id',
        'empresas_endereco_id',
        'controle',
        'data_venda',
        'data_prazo',
        'data_entrega',
        'valor_itens',
        'valor_desconto',
        'valor_total',
        'valor_servicos',
        'valor_orcamento',
        'valor_impostos',
        'valor_saldo',
        'observacao',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresas::class);
    }

    public function endereco()
    {
        return $this->belongsTo(EmpresasEnderecos::class, 'empresas_endereco_id', 'id');
    }
}
