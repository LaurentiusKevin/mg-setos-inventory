<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Services\Admin\MasterData\UserRoleService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserRoleController extends Controller
{
    private $service;

    public function __construct(UserRoleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.master-data.user-role.index');
    }

    public function data()
    {
        try {
            return DataTables::of(
                $this->service->getData()
            )->addColumn('action', function ($data) {
                return view('admin.master-data.user-role.action');
            })->make();
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function createIndex()
    {
        return view('admin.master-data.user-role.create',$this->service->indexCreateData());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $name = $request->get('name');
        $info = $request->get('info') ?? null;
        $view = $request->get('view');
        $create = $request->get('create');
        $edit = $request->get('edit');
        $delete = $request->get('delete');
        $id = $request->get('id') ?? null;

        return $this->service->storeData($name,$info,$view,$create,$edit,$delete,$id);
    }

    public function editIndex($id)
    {
        return view('admin.master-data.user-role.edit',$this->service->editIndexData($id));
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->get('id');

        return $this->service->deleteData($id);
    }
}
