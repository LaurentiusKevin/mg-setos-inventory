<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string name
 * @property string segment_name
 * @property string icon
 * @property integer ord
 * @property boolean is_private
 * @method static hasMenu()
 * @method static find(mixed $id)
 */
class SysMenuGroup extends Model
{
    use HasFactory;

    public static function booted()
    {
        static::addGlobalScope('ord', function (Builder $builder) {
            $builder->orderBy('ord');
        });
    }

    public function scopeHasMenu($query)
    {
        return $query->whereIn('id',
            SysMenu::select('sys_menu_group_id')->distinct()->get()
        );
    }
}
