<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjetoMembro extends Model
{
    /** @use HasFactory<\Database\Factories\ProjetoMembroFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'projetos_membros';

    protected $fillable = [
        'projeto_id',
        'equipe_id',
        'user_id',
        'lider_equipe',
        'lider_projeto',
    ];

    protected $casts = [
        'lider_equipe' => 'boolean',
        'lider_projeto' => 'boolean',
    ];


    public function equipe()
    {
        return $this->belongsTo(ProjetoEquipe::class, 'equipe_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function projeto()
    {
        return $this->belongsTo(Projeto::class, 'projeto_id');
    }

    public function atividades()
    {
        return $this->hasMany(ProjetoAtividade::class, 'projeto_membro_id');
        
    }
}
