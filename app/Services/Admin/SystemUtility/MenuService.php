<?php

namespace App\Services\Admin\SystemUtility;

use App\Models\SysMenu;
use App\Models\SysMenuGroup;
use App\Repositories\Admin\SystemUtility\MenuRepository;
use Illuminate\Support\Facades\DB;

class MenuService
{
    private $repository;

    public function __construct(MenuRepository $repository)
    {
        $this->repository = $repository;
    }

    public function indexData()
    {
        return [
            'group' => SysMenuGroup::all()
        ];
    }

    public function storeData($group_menu,$name,$segment_name,$order,$url,$id = null)
    {
        try {
            DB::beginTransaction();

            if ($id == null) {
                $data = new SysMenu();
            } else {
                $data = SysMenu::find($id);
            }
            $data->sys_menu_group_id = $group_menu;
            $data->name = $name;
            $data->segment_name = $segment_name;
            $data->ord = $order;
            $data->url = $url;
            $data->save();

            DB::commit();

            return response()->json(['status' => 'success', 'redirect' => route('admin.system-utility.menu.view.index')]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ],500);
        }
    }

    public function indexEditData($id)
    {
        return [
            'data' => SysMenu::find($id),
            'group' => SysMenuGroup::all()
        ];
    }

    public function showUnShow($id)
    {
        try {
            DB::beginTransaction();

            $data = SysMenu::find($id);
            if ($data->is_private == 1) {
                $data->is_private = 0;
            } else {
                $data->is_private = 1;
            }
            $data->save();

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

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            SysMenu::find($id)->delete();

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
