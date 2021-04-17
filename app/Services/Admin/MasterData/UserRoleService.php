<?php

namespace App\Services\Admin\MasterData;

use App\Models\Role;
use App\Models\RoleMenu;
use App\Models\SysMenu;
use App\Models\SysMenuGroup;
use App\Repositories\Admin\MasterData\UserRoleRepository;
use Illuminate\Support\Facades\DB;

class UserRoleService
{
    private $repository;

    public function __construct(UserRoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getData($id = 0)
    {
        if ($id == null) {
            return Role::all();
        } else {
            return Role::where('id','=',$id);
        }
    }

    public function menuData()
    {
        $menu = [];
        $group = SysMenuGroup::hasMenu()->where('is_private','=',0)->get();

        foreach ($group AS $item) {
            $menu[$item->id] = [
                'name' => $item->name,
                'menu' => SysMenu::where('sys_menu_group_id','=',$item->id)
                    ->where('is_private','=',0)
                    ->get()->toArray()
            ];
        }

        return $menu;
    }

    public function indexCreateData()
    {
        return [
            'menu' => $this->menuData()
        ];
    }

    public function storeData($name,$info,$view,$create,$edit,$delete,$id = null)
    {
        try {
            DB::beginTransaction();

            if ($id == null) {
                $role = new Role();
            } else {
                $role = Role::find($id);
            }
            $role->name = $name;
            $role->info = $info;
            $role->save();

            RoleMenu::where('roles_id','=',$role->id)->delete();
            $dataMenus = SysMenu::all();
            foreach ($dataMenus as $menu) {
                $roleMenu = new RoleMenu();
                $roleMenu->roles_id = $role->id;
                $roleMenu->sys_menus_id = $menu->id;
                if ($view !== null) {
                    $roleMenu->view = (in_array($menu->id,$view)) ? 1 : 0;
                }
                if ($create !== null) {
                    $roleMenu->create = (in_array($menu->id,$create)) ? 1 : 0;
                }
                if ($edit !== null) {
                    $roleMenu->edit = (in_array($menu->id,$edit)) ? 1 : 0;
                }
                if ($delete !== null) {
                    $roleMenu->delete = (in_array($menu->id,$delete)) ? 1 : 0;
                }
                $roleMenu->save();
            }

            DB::commit();
            return response()->json(['status' => 'success', 'redirect' => route('admin.master-data.user-role.view.index')]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ],500);
        }
    }

    public function masterRoleData($id)
    {
        $menu = [];
        $group = SysMenuGroup::hasMenu()->where('is_private','=',0)->get();

        foreach ($group AS $item) {
//            $list_menu = DB::table('sys_menus')
//                ->leftJoin('role_menus','sys_menus.id','=','role_menus.sys_menus_id')
//                ->addSelect(
//                    'sys_menus.id',
//                    'sys_menus.sys_menu_group_id',
//                    'sys_menus.name',
//                    'role_menus.id AS role_menus_id',
//                    'role_menus.roles_id',
//                    'role_menus.sys_menus_id',
//                    DB::raw('CASE WHEN role_menus.view IS NULL THEN 0 ELSE role_menus.view END AS view'),
//                    DB::raw('CASE WHEN role_menus.create IS NULL THEN 0 ELSE role_menus.create END AS `create`'),
//                    DB::raw('CASE WHEN role_menus.edit IS NULL THEN 0 ELSE role_menus.edit END AS edit'),
//                    DB::raw('CASE WHEN role_menus.`delete` IS NULL THEN 0 ELSE role_menus.delete END AS `delete`')
//                )
//                ->where([
//                    ['sys_menu_group_id','=',$item->id],
//                    ['sys_menus.is_private','=',0]
//                ])
//                ->orderBy('sys_menus.ord')
//                ->groupBy('sys_menus.id')
//                ->get()->toArray();

            $list_menu = DB::select("
                WITH menu AS (
                    SELECT sys_menu_group_id,
                           id AS sys_menus_id,
                           name,
                           segment_name,
                           url,
                           ord
                    FROM sys_menus
                    WHERE is_private = 0 AND sys_menu_group_id = ?
                    ORDER BY ord
                )
                , role AS (
                    SELECT rm.id AS role_menu_id,
                           roles_id,
                           sys_menus_id,
                           r.name AS role_name,
                           view,
                           `create`,
                           edit,
                           `delete`
                    FROM role_menus rm
                        JOIN roles r on rm.roles_id = r.id
                        WHERE roles_id = ?
                )
                SELECT sys_menu_group_id,
                       menu.sys_menus_id,
                       roles_id,
                       role_menu_id,
                       name,
                       role_name,
                       segment_name,
                       url,
                       ord,
                       view,
                       `create`,
                       edit,
                       `delete`
                FROM menu
                    LEFT JOIN role ON menu.sys_menus_id = role.sys_menus_id
            ",[$item->id,$id]);

            $menu[$item->id] = [
                'name' => $item->name,
                'menu' => $list_menu
            ];
        }

        return $menu;
    }

    public function editIndexData($id)
    {
        return [
            'groups' => $this->masterRoleData($id),
            'data' => Role::find($id)
        ];
    }

    public function deleteData($id)
    {
        try {
            DB::beginTransaction();

            $role = Role::find($id);
            RoleMenu::where('roles_id','=',$role->id)->delete();
            $role->delete();

            DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ],500);
        }
    }
}
