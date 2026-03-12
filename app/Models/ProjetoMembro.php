<?php

namespace App\Models;

use DB;
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
    ];

    protected $casts = [
        'lider_equipe' => 'boolean',
        'gerente_projeto' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($membro) {
            // Se está criando um novo membro como líder, revoga outros líderes da equipe
            if ($membro->lider_equipe) {
                $membro->equipe->membros()->where('lider_equipe', true)
                ->where('user_id','!=',$membro->user_id)->get()
                    ->each(function ($membro_equipe) use ($membro) {
                        $membro_equipe->lider_equipe = false;
                        if($membro_equipe->gerente_projeto && $membro->gerente_projeto){
                            $membro_equipe->gerente_projeto = false;
                        }
                        $membro_equipe->save();
                    });
            }else if ($membro->gerente_projeto) {
                $membro->projeto->membros()->where('gerente_projeto', true)
                ->where('user_id','!=',$membro->user_id)->get()
                    ->each(function ($membro_projeto) use ($membro) {
                        $membro_projeto->gerente_projeto = false;
                        $membro_projeto->save();
                    });
            }
            return true;
        });
        static::updating(function ($membro) {
            // Se está promovendo a líder - apenas remove outros líderes
            $register = $membro->replicate();
            if ($membro->isDirty('lider_equipe') && $membro->lider_equipe) {
                $membro->equipe->revogueLider();
                $register->lider_equipe = true;
            }

            // Se está promovendo a gerente - apenas remove outros gerentes
            if ($membro->isDirty('gerente_projeto') && $membro->gerente_projeto) {
                // Exemplo: Validação - não pode ser gerente se já é líder
                $membro->projeto->revogueGerente();
                // cria um copia do registro atual para manter o histórico
                $register->gerente_projeto = true;
            }
            $register->save();
            $membro->delete(); // Soft delete do registro antigo para manter o histórico 

            // Se chegou até aqui, permite o update
            return false; // ✅ Ou simplesmente não retorne nada
        });
    }

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

    public function getNomeAttribute()
    {
        return $this->user->name;
    }
    public function getEmailAttribute()
    {
        return $this->user->email;
    }
}

