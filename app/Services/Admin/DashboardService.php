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
        $groupData = null;
        $menuData = null;
        $menu = array();
        $sidebar = [];

        $groupData = SysMenuGroup::hasMenu()->get();

        foreach ($groupData AS $item) {
            $menu[$item->id] = SysMenu::where('sys_menu_group_id','=',$item->id)->get()->toArray();
        }

        $groupData = $groupData->toArray();

//        $user = Auth::user();
//        $username = $user->username;
//        $groupData = null;
//        $menuData = null;
//        $menu = array();
//        $sidebar = [];
//
//        if ($username == 'superadmin') {
//            $groupData = SysMenuGroup::hasMenu()->get();
//
//            foreach ($groupData AS $item) {
//                $menu[$item->id] = SysMenu::where('sys_menu_group_id','=',$item->id)->get()->toArray();
//            }
//
//            $groupData = $groupData->toArray();
//        } else {
//            $role_id = $user->role_id;
//
//            $groupData = DB::select("
//                SELECT
//                    DISTINCT
//                    smg.*
//                FROM
//                    role_menus rm
//                        JOIN sys_menus sm on rm.sys_menus_id = sm.id
//                        JOIN sys_menu_groups smg on sm.sys_menu_group_id = smg.id
//                WHERE
//                      rm.view = 1
//                  AND rm.roles_id = ?
//                ORDER BY
//                    smg.id,
//                    smg.ord
//            ",[$role_id]);
//
//            foreach ($groupData AS $item) {
//                $id = $item->id;
//                $menu[$id] = SysMenu::where('sys_menu_group_id','=',$id)->get()->toArray();
//            }
//
//            foreach ($groupData AS $key => $item) {
//                $groupData[$key]->id = $item->id;
//            }
//
//            $groupData = array_map(function ($value) {
//                return (array) $value;
//            }, $groupData);
//        }

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
