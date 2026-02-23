<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjetoAtividade extends Model
{
    /** @use HasFactory<\Database\Factories\ProjetoAtividadeFactory> */
    use HasFactory, SoftDeletes;

    public $table = 'projetos_atividades';

    public $fillable = [
        'projeto_membro_id',
        'anterior',
        'atual',
        'problemas',
        'status',
        'avaliacao',
    ];

    protected $casts = [
        'avaliacao' => 'float',
    ];

    public function membro()
    {
        return $this->belongsTo(ProjetoMembro::class, 'projeto_membro_id');
    }

    public function getUsuarioAttribute()
    {
        return $this->membro->user;
    }

    public function projeto()
    {
        return $this->membro->projeto();
    }
}
