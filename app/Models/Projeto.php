<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    /** @use HasFactory<\Database\Factories\ProjetoFactory> */
    use HasFactory;

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
        return $this->hasManyThrough(ProjetoAtividade::class, ProjetoMembro::class, 'projeto_id', 'projeto_membro_id');
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
    

}
