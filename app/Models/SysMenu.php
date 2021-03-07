<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property integer id
 * @property integer sys_menu_group_id
 * @property string name
 * @property string segment_name
 * @property string url
 * @property integer ord
 * @property boolean is_private
 * @method static select(mixed $columns)
 * @method static where(string $columns, string $operator, mixed $value)
 * @method static withGroup()
 * @method static find(mixed $id)
 */
class SysMenu extends Model
{
    use HasFactory;

    public static function apply()
    {
        static::addGlobalScope('ord', function (Builder $builder) {
            $builder->orderBy('ord');
        });
    }

    public function scopeWithGroup($query)
    {
        return $query
            ->leftJoin('sys_menu_groups','sys_menu_groups.id','=','sys_menus.sys_menu_group_id')
            ->addSelect([
                'sys_menus.id',
                'sys_menus.name',
                'sys_menus.segment_name',
                'sys_menus.url',
                DB::raw("concat_ws('.',sys_menu_groups.ord,sys_menus.ord) AS ord"),
                'sys_menus.is_private',
                'sys_menu_groups.name AS group_name'
            ]);
    }

    public function scopeWithRoles($query)
    {
        return $query
            ->leftJoin('roles_menus','sys_menus.id','=','roles_menus.sys_menus_id')
            ->addSelect([
                'sys_menus.id',
                'sys_menus.name',
                'sys_menus.segment_name',
                'sys_menus.url',
                'roles_menus.view',
                'roles_menus.create',
                'roles_menus.edit',
                'roles_menus.delete',
                'sys_menus.is_private'
            ]);
    }
}
