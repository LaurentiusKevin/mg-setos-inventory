<?php

namespace App\Http\Controllers\Admin\SystemUtility;

use App\Http\Controllers\Controller;
use App\Models\SysMenuGroup;
use App\Services\Admin\SystemUtility\MenuGroupService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MenuGroupController extends Controller
{
    private $service;

    public function __construct(MenuGroupService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.system-utility.menu-group.index');
    }

    public function data()
    {
        try {
            return DataTables::of(
                SysMenuGroup::all()
            )->addColumn('action', function ($data) {
                return view('admin.system-utility.menu-group.action');
            })->make();
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function indexCreate()
    {
        return view('admin.system-utility.menu-group.create');
    }

    public function storeData(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'segment_name' => 'required',
            'icon' => 'required',
            'order' => 'required',
        ]);

        $name = $request->get('name');
        $segment_name = $request->get('segment_name');
        $icon = $request->get('icon');
        $order = $request->get('order');

        return $this->service->storeData($name,$segment_name,$icon,$order);
    }

    public function editIndex($id)
    {
        return view('admin.system-utility.menu-group.edit',$this->service->indexEditData($id));
    }

    public function storeEdit(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'segment_name' => 'required',
            'icon' => 'required',
            'order' => 'required',
        ]);

        $id = $request->get('id');
        $name = $request->get('name');
        $segment_name = $request->get('segment_name');
        $icon = $request->get('icon');
        $order = $request->get('order');

        return $this->service->storeData($name,$segment_name,$icon,$order,$id);
    }

    public function showUnshow(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->get('id');

        return $this->service->showUnShow($id);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->get('id');

        return $this->service->delete($id);
    }
}
