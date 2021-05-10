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
                SELECT sys_menus.id AS sys_menus_id,
                       rm.id AS role_menu_id,
                       r.id AS roles_id,
                       sys_menus.sys_menu_group_id,
                       r.name AS role_name,
                       sys_menus.name,
                       sys_menus.segment_name,
                       sys_menus.url,
                       sys_menus.ord,
                       rm.view,
                       rm.`create`,
                       rm.edit,
                       rm.`delete`
                FROM sys_menus
                    LEFT JOIN role_menus rm on sys_menus.id = rm.sys_menus_id AND rm.roles_id = ?
                    LEFT JOIN roles r on rm.roles_id = r.id
                WHERE is_private = 0
                  AND sys_menu_group_id = ?
                ORDER BY ord
            ",[$id,$item->id]);

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
