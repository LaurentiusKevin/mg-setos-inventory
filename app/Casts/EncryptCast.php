<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class EncryptCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return is_null($value) ? null : encrypt($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return is_null($value) ? null : decrypt($value);
    }
}
