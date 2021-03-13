<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;

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
 * @method static withRoles(Integer $sys_menu_group_id, Integer $roles_id)
 */
class SysMenu extends Model
{
    use HasFactory;

    public static function booted()
    {
        static::addGlobalScope('ordered', function (Builder $builder) {
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

    public function scopeWithRoles($query,$sys_menu_group_id,$roles_id)
    {
        return $query
            ->leftJoin('role_menus','sys_menus.id','=','role_menus.sys_menus_id')
            ->addSelect(
                'sys_menus.id',
                'sys_menus.sys_menu_group_id',
                'sys_menus.name',
                'sys_menus.ord',
                'sys_menus.is_private',
                'role_menus.id AS role_menus_id',
                'role_menus.roles_id',
                'role_menus.sys_menus_id',
                DB::raw('CASE WHEN role_menus.view IS NULL THEN 0 ELSE role_menus.view END AS view'),
                DB::raw('CASE WHEN role_menus.create IS NULL THEN 0 ELSE role_menus.create END AS `create`'),
                DB::raw('CASE WHEN role_menus.edit IS NULL THEN 0 ELSE role_menus.edit END AS edit'),
                DB::raw('CASE WHEN role_menus.`delete` IS NULL THEN 0 ELSE role_menus.delete END AS `delete`')
            )
            ->where([
                ['sys_menu_group_id','=',$sys_menu_group_id],
                ['roles_id','=',$roles_id],
                ['sys_menus.is_private','=',0],
            ]);
    }
}
