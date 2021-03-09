<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use App\Services\Admin\MasterData\SatuanProdukService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SatuanProductController extends Controller
{
    private $service;

    public function __construct(SatuanProdukService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.master-data.satuan-product.index');
    }

    public function data()
    {
        try {
            return DataTables::of(
                Satuan::all()
            )->addColumn('action', function ($data) {
                return view('admin.master-data.satuan-product.action');
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
        return view('admin.master-data.satuan-product.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        $nama = $request->get('nama');
        $id = $request->get('id') ?? null;

        return $this->service->storeData($nama,$id);
    }

    public function indexEdit($id)
    {
        return view('admin.master-data.satuan-product.edit',$this->service->indexEditData($id));
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
