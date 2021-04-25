<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\Controller;
use App\Services\Admin\Stock\StoreRequisitionService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;

class StoreRequisitionController extends Controller
{
    private $service;

    public function __construct(StoreRequisitionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.stock.store-requisition.index');
    }

    public function data()
    {
        try {
            return DataTables::of($this->service->getData())
                ->addColumn('action', function ($data) {
                    return view('admin.stock.store-requisition.action',[
                        'data' => $data,
                        'verification' => $this->service->statusVerification($data->id)
                    ]);
                })
                ->make(true);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function indexCreate()
    {
        return view('admin.stock.store-requisition.create',$this->service->indexData());
    }

    public function getProductList(Request $request)
    {
        $selected_product_id = $request->get('selected_product_id');

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
            'department' => 'required',
            'product' => 'required',
            'info_penggunaan' => 'required',
        ]);

        $department_id = $request->get('department');
        $product = $request->get('product');
        $info_penggunaan = $request->get('info_penggunaan');
        $catatan = $request->get('catatan') ?? null;

        return $this->service->store($department_id,$product,$info_penggunaan,$catatan);
    }

    public function storeEdit(Request $request)
    {
        $request->validate([
            'store_requisition_info_id' => 'required',
            'department' => 'required',
            'product' => 'required',
            'info_penggunaan' => 'required',
        ]);

        $store_requisition_info_id = $request->get('store_requisition_info_id');
        $department_id = $request->get('department');
        $product = $request->get('product');
        $info_penggunaan = $request->get('info_penggunaan');
        $catatan = $request->get('catatan') ?? null;

        return $this->service->storeEdit($store_requisition_info_id,$department_id,$product,$info_penggunaan,$catatan);
    }

    public function indexInfo($id)
    {
        return view('admin.stock.store-requisition.info',$this->service->indexInfoData($id));
    }

    public function indexVerification($id)
    {
        return view('admin.stock.store-requisition.verification',$this->service->indexInfoData($id));
    }

    public function indexEdit($id)
    {
        return view('admin.stock.store-requisition.edit',$this->service->indexEditData($id));
    }

    public function getStoredProduct(Request $request)
    {
        $request->validate([
            'store_requisition_info_id' => 'required'
        ]);

        $store_requisition_info_id = $request->get('store_requisition_info_id');
        $edit_process = $request->get('edit_process') ?? false;

        return $this->service->getStoredProduct($store_requisition_info_id,$edit_process);
    }

    public function storeCatatan(Request $request)
    {
        $request->validate([
            'catatan' => 'required',
            'store_requisition_info_id' => 'required'
        ]);

        $catatan = $request->get('catatan');
        $store_requisition_info_id = $request->get('store_requisition_info_id');

        return $this->service->storeCatatan($store_requisition_info_id,$catatan);
    }

    public function storeVerification(Request $request)
    {
        $request->validate([
            'store_requisition_info_id' => 'required'
        ]);

        $store_requisition_info_id = $request->get('store_requisition_info_id');

        return $this->service->storeVerification($store_requisition_info_id);
    }

    public function indexPdf($id)
    {
        try {
            $pdf = PDF::loadView('admin.stock.store-requisition.pdf',$this->service->indexInfoData($id))->setPaper('a4','portrait');
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
