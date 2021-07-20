<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static find(int|null $department_id)
 */
class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy('name');
    }
}
