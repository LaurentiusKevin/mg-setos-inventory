<?php

namespace App\Services\Admin\SystemUtility;

use App\Models\SysMenuGroup;
use App\Repositories\Admin\SystemUtility\MenuGroupRepository;
use Illuminate\Support\Facades\DB;

class MenuGroupService
{
    private $repository;

    public function __construct(MenuGroupRepository $repository)
    {
        $this->repository = $repository;
    }

    public function storeData($name,$segment_name,$icon,$order,$id = null)
    {
        try {
            DB::beginTransaction();

            if ($id == null) {
                $data = new SysMenuGroup();
            } else {
                $data = SysMenuGroup::find($id);
            }
            $data->name = $name;
            $data->segment_name = $segment_name;
            $data->icon = $icon;
            $data->ord = $order;
            $data->save();

            DB::commit();

            return response()->json(['status' => 'success', 'redirect' => route('admin.system-utility.menu-group.view.index')]);
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
            'data' => SysMenuGroup::find($id)
        ];
    }

    public function showUnShow($id)
    {
        try {
            DB::beginTransaction();

            $data = SysMenuGroup::find($id);
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

            SysMenuGroup::find($id)->delete();

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
