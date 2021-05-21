<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseOrderInfo;
use App\Services\Admin\Stock\PurchaseOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use PDF;
use function Sentry\captureException;

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
        try {
            return DataTables::of($this->service->data())
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

        try {
            return DataTables::of($this->service->getProduct($selected_product))
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
            $data = $this->service->indexEditData($id);
            $no_invoice = str_replace('/','-',$data['invoice_number']);

            $pdf = PDF::loadView('admin.stock.purchase-order.pdf',$data)->setPaper('a4','portrait');
            return $pdf->stream("purchase_order_{$no_invoice}.pdf");
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }
}
