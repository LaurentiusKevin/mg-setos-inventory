<?php

namespace App\Services\Admin\MasterData;

use App\Models\Satuan;
use App\Repositories\Admin\MasterData\SatuanProductRepository;
use Illuminate\Support\Facades\DB;

class SatuanProdukService
{
    private $repository;

    public function __construct(SatuanProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function storeData($nama, $id = null)
    {
        try {
            DB::beginTransaction();

            if ($id == null) {
                $data = new Satuan();
            } else {
                $data = Satuan::find($id);
            }
            $data->nama = $nama;
            $data->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'redirect' => route('admin.master-data.satuan-produk.view.index')
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
            'data' => Satuan::find($id)
        ];
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            Satuan::find($id)->delete();

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
