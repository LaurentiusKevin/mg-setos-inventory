<?php

namespace App\Services\Admin\MasterData;

use App\Models\Department;
use App\Models\StoreRequisitionVerificator;
use App\Repositories\Admin\MasterData\StoreRequisitionVerificatorRepository;
use Illuminate\Support\Facades\DB;

class StoreRequisitionVerificatorService
{
    private $repository;

    public function __construct(StoreRequisitionVerificatorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function data()
    {
        return $this->repository->department();
    }

    public function listUser()
    {
        return $this->repository->listUser();
    }

    public function storeData($user_id)
    {
        try {
            DB::beginTransaction();

            $data = new StoreRequisitionVerificator();
            $data->user_id = $user_id;
            $data->save();

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

            StoreRequisitionVerificator::find($id)->delete();

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
