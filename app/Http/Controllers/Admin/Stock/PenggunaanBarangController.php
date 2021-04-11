<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\Controller;
use App\Services\Admin\Stock\PenggunaanBarangService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;

class PenggunaanBarangController extends Controller
{
    private $service;

    public function __construct(PenggunaanBarangService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.stock.penggunaan-barang.index');
    }

    public function data()
    {
        try {
            return DataTables::of($this->service->getData())
                ->addColumn('action', function ($data) {
                    return view('admin.stock.penggunaan-barang.action');
                })
                ->make(true);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function indexCreate()
    {
        return view('admin.stock.penggunaan-barang.create');
    }

    public function getProductList(Request $request)
    {
        $selected_product_id = $request->selected_product_id;

        try {
            return DataTables::of($this->service->getProductList($selected_product_id))
                ->make(true);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'product' => 'required',
            'info_penggunaan' => 'required',
        ]);

        $product = $request->get('product');
        $info_penggunaan = $request->get('info_penggunaan');
        $catatan = $request->get('catatan') ?? null;

        return $this->service->store($product,$info_penggunaan,$catatan);
    }

    public function indexInfo($id)
    {
        return view('admin.stock.penggunaan-barang.info',$this->service->indexInfoData($id));
    }

    public function indexPdf($id)
    {
        try {
            $pdf = PDF::loadView('admin.stock.penggunaan-barang.pdf',$this->service->indexInfoData($id))->setPaper('a4','portrait');
            return $pdf->stream('invoice.pdf');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }
}
