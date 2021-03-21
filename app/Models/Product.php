<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static find(mixed $id)
 */
class Product extends Model
{
    use HasFactory,SoftDeletes;

    public function satuan()
    {
        return $this->hasOne(Satuan::class,'id','satuan_id');
    }
}
