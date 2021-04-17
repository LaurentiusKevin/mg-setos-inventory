<?php

namespace App\Services\Admin;

use App\Models\SysMenu;
use App\Models\SysMenuGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function sidebar(): array
    {
        $username = Auth::user()->username;
        $role_id = Auth::user()->role_id;
        $groupData = null;
        $menuData = null;
        $menu = array();
        $sidebar = [];

        if ($username == 'superadmin') {
            $groupData = SysMenuGroup::hasMenu()->get();

            foreach ($groupData AS $item) {
                $menu[$item->id] = SysMenu::where('sys_menu_group_id','=',$item->id)
                    ->orderBy('ord')
                    ->get();
            }

            $groupData = $groupData->toArray();
        } else {
            $groupData = DB::select("
                SELECT
                    DISTINCT
                    smg.*
                FROM role_menus rm
                    JOIN sys_menus sm on rm.sys_menus_id = sm.id
                    JOIN sys_menu_groups smg on sm.sys_menu_group_id = smg.id
                WHERE rm.view = 1
                  AND rm.roles_id = ?
                ORDER BY
                    smg.id,
                    smg.ord
            ",[$role_id]);

            foreach ($groupData AS $item) {
                $id = $item->id;
                $menu[$id] = DB::table('sys_menus')
                    ->select('sys_menus.*')
                    ->join('role_menus','sys_menus.id','=','role_menus.sys_menus_id')
                    ->where([
                        ['role_menus.view','=',1],
                        ['role_menus.roles_id','=',$role_id],
                        ['sys_menus.sys_menu_group_id','=',$id]
                    ])
                    ->orderBy('ord')
                    ->get()->toArray();
            }

            foreach ($groupData AS $key => $item) {
                $groupData[$key]->id = $item->id;
            }

            $groupData = array_map(function ($value) {
                return (array) $value;
            }, $groupData);
        }

        foreach ($groupData AS $item) {
            $id = $item['id'];

            array_push($sidebar, [
                'group' => $item,
                'menu' => $menu[$id]
            ]);
        }

        return [
            'sidebar' => $sidebar
        ];
    }
}
