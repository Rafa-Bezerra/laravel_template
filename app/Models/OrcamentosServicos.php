<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Orcamentos;
use App\Models\Servicos;

class OrcamentosServicos extends BaseModel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $table = 'orcamentos_servicos';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'orcamento_id',
        'servico_id',
        'data',
        'preco',
    ];

    public function orcamento()
    {
        return $this->belongsTo(Orcamentos::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servicos::class);
    }
}
