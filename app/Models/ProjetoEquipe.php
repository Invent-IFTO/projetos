<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    public function membros()
    {
        return $this->hasMany(ProjetoMembro::class, 'equipe_id');
    }

    public function lider()
    {
        return $this->membros()->where('lider_equipe', true)->first();
    }
    public function getLiderAttribute()
    {
        return $this->lider();
    }

    /**
     * Retorna o histórico completo de liderança da equipe
     * Inclui tanto registros ativos quanto soft-deleted
     */
    public function historicoLideranca()
    {
        return ProjetoMembro::withTrashed()
            ->where('equipe_id', $this->id)
            ->where('lider_equipe', true)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Retorna todos os membros da equipe, incluindo histórico (soft-deleted)
     */
    public function todosOsMembros()
    {
        return ProjetoMembro::withTrashed()
            ->where('equipe_id', $this->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function revogueLider()
    {
        $this->membros()->where('lider_equipe', true)->get()
        ->each(function ($membro) {
            $membro->lider_equipe = false;
            $membro->save();
        });
    }

    public function convites()
    {
        return $this->hasMany(ProjetoConvite::class, 'equipe_id');
    }

    public function atividades()
    {
        return $this->hasManyThrough(ProjetoAtividade::class, ProjetoMembro::class, 'equipe_id', 'projeto_membro_id');
    }

    
}
