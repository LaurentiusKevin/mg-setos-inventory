<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\MasterData\StoreRequisitionVerificatorService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StoreRequisitionVerificatorController extends Controller
{
    private $service;

    public function __construct(StoreRequisitionVerificatorService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.master-data.sr-verificator.index');
    }

    public function data()
    {
        try {
            return DataTables::of(
                $this->service->data()
            )->addColumn('action', function ($data) {
                return view('admin.master-data.sr-verificator.action',[
                    'data' => $data
                ]);
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
        return view('admin.master-data.sr-verificator.create');
    }

    public function listUser()
    {
        try {
            return DataTables::of(
                $this->service->listUser()
            )->addColumn('action', function ($data) {
                return view('admin.master-data.sr-verificator.action-add');
            })->make(true);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user_id = $request->get('user_id');

        return $this->service->storeData($user_id);
    }

    public function indexEdit($id)
    {
        return view('admin.master-data.sr-verificator.edit',$this->service->indexEditData($id));
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->get('id');

        return $this->service->delete($id);
    }

    public function setPrimary(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->get('id');

        return $this->service->setPrimary($id);
    }
}
