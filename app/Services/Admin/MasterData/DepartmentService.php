<?php

namespace App\Services\Admin\MasterData;

use App\Models\Department;
use App\Repositories\Admin\MasterData\DepartmentRepository;
use Illuminate\Support\Facades\DB;

class DepartmentService
{
    private $repository;

    public function __construct(DepartmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function data()
    {
        return $this->repository->department();
    }

    public function storeData($code ,$nama, $info,$id = null)
    {
        try {
            if ($this->repository->checkCode($code) > 0 && $id == null) {
                return response()->json([
                    'status' => 'terpakai',
                    'message' => 'Kode sudah terpakai!'
                ]);
            } else {
                DB::beginTransaction();

                if ($id == null) {
                    $data = new Department();
                } else {
                    $data = Department::find($id);
                }
                $data->code = $code;
                $data->name = $nama;
                $data->info = $info;
                $data->save();

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'redirect' => route('admin.master-data.department.view.index')
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }

    public function indexEditData($id)
    {
        return [
            'data' => $this->repository->department($id)->first()
        ];
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            Department::find($id)->delete();

            DB::commit();

            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }
}
