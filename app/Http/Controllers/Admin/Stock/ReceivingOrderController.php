<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\Controller;
use App\Models\ReceivingOrderInfo;
use App\Services\Admin\Stock\ReceivingOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $data = DB::table('receiving_order_infos')
            ->select([
                'receiving_order_infos.id',
                'receiving_order_infos.user_id',
                'receiving_order_infos.purchase_order_info_id',
                'suppliers.name AS supplier_name',
                'users.name AS penginput',
                'receiving_order_infos.invoice_number',
                'receiving_order_infos.supplier_invoice_number',
                'receiving_order_infos.total_price',
                'receiving_order_infos.catatan',
                'receiving_order_infos.created_at',
                'receiving_order_infos.updated_at',
            ])
            ->join('purchase_order_infos','receiving_order_infos.purchase_order_info_id','=','purchase_order_infos.id')
            ->join('suppliers','purchase_order_infos.supplier_id','=','suppliers.id')
            ->join('users','receiving_order_infos.user_id','=','users.id')
            ->orderBy('created_at','desc');

        try {
            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    return view('admin.stock.receiving-order.action');
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
            $data = $this->service->indexInfoData($id);
            $invoice_number = str_replace('/','-',$data['invoice_number']);

            $pdf = PDF::loadView('admin.stock.receiving-order.pdf',$data)->setPaper('a4','portrait');
            return $pdf->stream("receiving_order_{$invoice_number}.pdf");
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'details' => $th
            ]);
        }
    }
}
