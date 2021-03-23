<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseOrderInfo;
use App\Services\Admin\Stock\PurchaseOrderService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;

class PurchaseOrderController extends Controller
{
    private $service;

    public function __construct(PurchaseOrderService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.stock.purchase-order.index');
    }

    public function data()
    {
        $data = PurchaseOrderInfo::with(['supplier','products'])
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
        return view('admin.stock.purchase-order.create',$this->service->indexCreateData());
    }

    public function getProductList(Request $request)
    {
        $selected_product = $request->selected_product_id ?? [];

        $data = Product::with('satuan')
            ->whereNotIn('id',$selected_product);

        try {
            return DataTables::of($data)
                ->make(true);
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
            'supplier_id' => 'required',
            'product' => 'required'
        ]);

        $supplier_id = $request->get('supplier_id');
        $product = $request->get('product');
        $catatan = $request->get('catatan');

        return $this->service->storeData($supplier_id,$product,$catatan);
    }

    public function indexInfo($id)
    {
        return view('admin.stock.purchase-order.info',$this->service->indexEditData($id));
    }

    public function indexPdf($id)
    {
        try {
            $pdf = PDF::loadView('admin.stock.purchase-order.pdf',$this->service->indexEditData($id))->setPaper('a4','portrait');
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
