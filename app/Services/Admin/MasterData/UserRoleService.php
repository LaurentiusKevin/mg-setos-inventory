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
            return Role::where('id','=',$id)->first();
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
        $group = SysMenuGroup::hasMenu()->get();

        foreach ($group AS $item) {
            if ($item->is_private == 0) {
                $menu[$item->id] = [
                    'name' => $item->name,
                    'menu' => SysMenu::withRoles($item->id, $id)->where('sys_menu_group_id','=',$item->id)->get()->toArray()
                ];
            }
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
