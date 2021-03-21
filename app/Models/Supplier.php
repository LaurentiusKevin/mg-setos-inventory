<?php

namespace App\Models;

use App\Casts\EncryptCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(mixed $id)
 */
class Supplier extends Model
{
    use HasFactory;

    protected $casts = [
        'logo' => EncryptCast::class,
    ];
}
