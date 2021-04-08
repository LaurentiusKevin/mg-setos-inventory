<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\Controller;
use App\Models\ReceivingOrderInfo;
use App\Services\Admin\Stock\ReceivingOrderService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;

class ReceivingOrderController extends Controller
{
    private $service;

    public function __construct(ReceivingOrderService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.stock.receiving-order.index');
    }

    public function data()
    {
        $data = ReceivingOrderInfo::with([
            'supplier',
            'products',
            'user'
        ])
            ->withSum('products','quantity')
            ->orderBy('created_at','desc');

        try {
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    return view('admin.stock.purchase-order.action');
                })
                ->make(true);
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
        return view('admin.stock.receiving-order.create');
    }

    public function dataPoPending()
    {
        try {
            return DataTables::of(
                $this->service->getDataPoPending()
            )->make(true);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }

    public function dataPoPendingProducts(Request $request)
    {
        $request->validate([
            'purchase_order_info_id' => 'required'
        ]);

        $purchase_order_info_id = $request->purchase_order_info_id;

        try {
            return $this->service->getDataPoPendingProducts($purchase_order_info_id);
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
            'purchase_order_info_id' => 'required',
            'product' => 'required',
            'catatan' => 'required',
        ]);

        $purchase_order_info_id = $request->get('purchase_order_info_id');
        $product = $request->get('product');
        $catatan = $request->get('catatan');

        return $this->service->storeData($purchase_order_info_id,$product,$catatan);
    }

    public function indexInfo($id)
    {
        return view('admin.stock.receiving-order.info',$this->service->indexInfoData($id));
    }

    public function indexPdf($id)
    {
        try {
            $pdf = PDF::loadView('admin.stock.receiving-order.pdf',$this->service->indexInfoData($id))->setPaper('a4','portrait');
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
