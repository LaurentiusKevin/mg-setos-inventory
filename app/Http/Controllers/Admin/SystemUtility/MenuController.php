<?php

namespace App\Http\Controllers\Admin\SystemUtility;

use App\Http\Controllers\Controller;
use App\Models\SysMenu;
use App\Services\Admin\SystemUtility\MenuService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    private $service;

    public function __construct(MenuService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.system-utility.menu.index');
    }

    public function data()
    {
        try {
            return DataTables::of(
                SysMenu::withGroup()->get()
            )->addColumn('action', function ($data) {
                return view('admin.system-utility.menu.action');
            })->make();
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function createIndex()
    {
        return view('admin.system-utility.menu.create',$this->service->indexData());
    }

    public function storeData(Request $request)
    {
        $request->validate([
            'group_menu' => 'required',
            'name' => 'required',
            'segment_name' => 'required',
            'order' => 'required',
            'url' => 'required',
        ]);

        $id = $request->get('id') ?? null;
        $group_menu = $request->get('group_menu');
        $name = $request->get('name');
        $segment_name = $request->get('segment_name');
        $order = $request->get('order');
        $url = $request->get('url');

        return $this->service->storeData($group_menu,$name,$segment_name,$order,$url,$id);
    }

    public function editIndex($id)
    {
        return view('admin.system-utility.menu.edit',$this->service->indexEditData($id));
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
