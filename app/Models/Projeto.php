<?php

namespace App\Models;

use App\Traits\HasConfig;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    /** @use HasFactory<\Database\Factories\ProjetoFactory> */
    use HasFactory, HasConfig;

    protected $fillable = [
        'titulo',
        'descricao',
        'criador_id',
    ];

    public function criador()
    {
        return $this->belongsTo(User::class, 'criador_id');
    }

    public function equipes()
    {
        return $this->hasMany(ProjetoEquipe::class, 'projeto_id');
    }

    public function membros()
    {
        return $this->hasMany(ProjetoMembro::class, 'projeto_id');
    }

    public function atividades()
    {
        return $this->hasManyThrough(ProjetoAtividade::class, ProjetoMembro::class, 'projeto_id', 'projeto_membro_id')->withTrashedParents();
    }

    public function membroId($user_id)
    {
        return $this->membros()->where('user_id', $user_id)->first()?->id;
    } 
    
    public function gerente()
    {
        return $this->membros()->where('gerente_projeto', true)->first();
    }

    public function getGerenteAttribute()
    {
        return $this->gerente()?->user;
    }

    public function revogueGerente()
    {
        $this->membros()->where('gerente_projeto', true)->get()->each(
            function ($membro) {
            $membro->gerente_projeto = false;
            $membro->save();
        });
    }

    public function convites()
    {
          return $this->hasManyThrough(ProjetoConvite::class, ProjetoEquipe::class, 'projeto_id', 'equipe_id');
    }
    

}
