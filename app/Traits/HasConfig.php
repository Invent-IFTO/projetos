<?php

namespace App\Traits;

trait HasConfig
{
    public static function getConfig($chave, $default = null)
    {
        return \App\Models\Config::get(static::class, $chave, $default);
    }
    public static function setConfig($label, $chave, $valor)
    {
        return \App\Models\Config::set(static::class, $label, $chave, $valor);
    }
}
