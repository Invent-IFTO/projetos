<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = [
        'model_type',
        'label',
        'chave',
        'valor',
    ];

    public static function get($model_type, $chave, $default = null)
    {
        $config = self::where('model_type', $model_type)->where('chave', $chave)->first();
        return $config ? $config->valor : $default;
    }

    public static function set($model_type, $label, $chave, $valor)
    {
        return self::updateOrCreate(
            [
                'model_type' => $model_type,
                'chave' => $chave
            ],
            ['valor' => $valor, 'label' => $label]
        );
    }
}
