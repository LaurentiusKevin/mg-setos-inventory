<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use App\Services\Admin\MasterData\DepartmentService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    private $service;

    public function __construct(DepartmentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.master-data.department.index');
    }

    public function data()
    {
        try {
            return DataTables::of(
                $this->service->data()
            )->addColumn('action', function ($data) {
                return view('admin.master-data.department.action');
            })->make(true);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }

    public function indexCreate()
    {
        return view('admin.master-data.department.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'nama' => 'required',
        ]);

        $code = $request->get('code');
        $nama = $request->get('nama');
        $info = $request->get('info');
        $id = $request->get('id') ?? null;

        return $this->service->storeData($code,$nama,$info,$id);
    }

    public function indexEdit($id)
    {
        return view('admin.master-data.department.edit',$this->service->indexEditData($id));
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
