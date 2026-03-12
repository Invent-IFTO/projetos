<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjetoConvite extends Model
{
    /** @use HasFactory<\Database\Factories\ConviteFactory> */
    use HasFactory, SoftDeletes, HasUuids;

    public $fillable = [
        'equipe_id',
        'responsavel_convite_id',
        'email',
    ];
    public $table = 'projetos_convites';

    public $hidden = [
        'token',
    ];

    public $casts = [
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function hash()
    {
        return hash('sha256', $this->token);
    }

    public function update( array $attributes = [], array $options = [])
    {
    }

    public static function create( array $attributes = [] )
    {
        $attributes['token'] = bin2hex(random_bytes(16));
        $attributes['expires_at'] = now()->addHours(Projeto::getConfig('hours_expire',48));
        return static::query()->create($attributes);
    }

    public function save(array $options = []){}

    /**
     * Relacionamento com a equipe do projeto
     */
    public function equipe()
    {
        return $this->belongsTo(ProjetoEquipe::class, 'equipe_id');
    }

    /**
     * Relacionamento com o usuário responsável pelo convite
     */
    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_convite_id');
    }
}
