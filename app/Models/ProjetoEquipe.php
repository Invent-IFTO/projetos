<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjetoEquipe extends Model
{
    /** @use HasFactory<\Database\Factories\ProjetoEquipeFactory> */
    use HasFactory, SoftDeletes;
    protected $table = 'projetos_equipes';
    protected $fillable = [
        'projeto_id',
        'descricao',
    ];

    public function projeto()
    {
        return $this->belongsTo(Projeto::class, 'projeto_id');
    }

    public function membros(){
        return $this->hasMany(ProjetoMembro::class, 'equipe_id');
    }

    public function lider(){
        return $this->membros()->where('lider_equipe', true)->first();
    }
    public function getLiderAttribute(){
        return $this->lider();
    }
}
